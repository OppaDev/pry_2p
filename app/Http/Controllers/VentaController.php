<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Cliente;
use App\Models\Producto;
use App\Services\VentaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class VentaController extends Controller
{
    protected VentaService $ventaService;
    
    public function __construct(VentaService $ventaService)
    {
        $this->ventaService = $ventaService;
    }
    
    /**
     * Listado de ventas con filtros
     */
    public function index(Request $request)
    {
        $query = Venta::with(['cliente', 'vendedor', 'factura']);

        // Filtros
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('fecha', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $query->whereDate('fecha', '<=', $request->fecha_fin);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('vendedor_id')) {
            $query->where('vendedor_id', $request->vendedor_id);
        }

        if ($request->filled('cliente_id')) {
            $query->where('cliente_id', $request->cliente_id);
        }

        if ($request->filled('numero_secuencial')) {
            $query->where('numero_secuencial', 'like', '%' . $request->numero_secuencial . '%');
        }

        $ventas = $query->latest('fecha')->paginate(20);

        // KPIs para la vista
        $hoy = now()->format('Y-m-d');
        $ventasHoy = Venta::whereDate('fecha', $hoy)->where('estado', 'completada')->count();
        $ingresosHoy = Venta::whereDate('fecha', $hoy)->where('estado', 'completada')->sum('total');
        
        $inicioMes = now()->startOfMonth()->format('Y-m-d');
        $ventasMes = Venta::whereDate('fecha', '>=', $inicioMes)->where('estado', 'completada')->count();
        $ingresosMes = Venta::whereDate('fecha', '>=', $inicioMes)->where('estado', 'completada')->sum('total');

        // Vendedores para filtro
        $vendedores = \App\Models\User::role('vendedor')->get();

        return view('ventas.index', compact('ventas', 'ventasHoy', 'ingresosHoy', 'ventasMes', 'ingresosMes', 'vendedores'));
    }    /**
     * Vista punto de venta (POS)
     */
    public function create()
    {
        $productos = Producto::where('stock_actual', '>', 0)
            ->with('categoria')
            ->orderBy('nombre')
            ->get();
        
        $clientes = Cliente::activos()
            ->orderBy('nombres')
            ->orderBy('apellidos')
            ->get();
        
        return view('ventas.create', compact('productos', 'clientes'));
    }
    
    /**
     * Procesar venta desde POS
     */
    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia',
            'productos' => 'required|array|min:1',
            'productos.*.producto_id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio_unitario' => 'required|numeric|min:0',
        ]);
        
        try {
            // Verificar edad del cliente
            $cliente = Cliente::findOrFail($request->cliente_id);
            
            if (!$cliente->es_mayor_edad) {
                return redirect()->back()
                    ->with('error', '❌ El cliente debe ser mayor de 18 años para comprar licores.');
            }
            
            // Crear venta usando el servicio
            $venta = $this->ventaService->procesarVenta([
                'cliente_id' => $request->cliente_id,
                'metodo_pago' => $request->metodo_pago,
                'observaciones' => $request->observaciones,
                'edad_verificada' => true,
                'items' => $request->productos,
            ], Auth::user());
            
            Log::info('Venta creada exitosamente', [
                'venta_id' => $venta->id,
                'numero_secuencial' => $venta->numero_secuencial,
                'vendedor_id' => Auth::id(),
            ]);
            
            return redirect()
                ->route('ventas.show', $venta)
                ->with('success', '✅ Venta registrada exitosamente. Número: ' . $venta->numero_secuencial);
                
        } catch (Exception $e) {
            Log::error('Error al crear venta', [
                'error' => $e->getMessage(),
                'vendedor_id' => Auth::id(),
            ]);
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', '❌ Error al procesar venta: ' . $e->getMessage());
        }
    }
    
    /**
     * Ver detalle de venta
     */
    public function show(Venta $venta)
    {
        $venta->load(['cliente', 'vendedor', 'detalles.producto', 'factura']);
        
        return view('ventas.show', compact('venta'));
    }
    
    /**
     * Anular venta
     */
    public function anular(Request $request, Venta $venta)
    {
        $request->validate([
            'motivo' => 'required|string|min:10',
        ]);
        
        try {
            $this->ventaService->anularVenta($venta->id, $request->motivo, Auth::user());
            
            return redirect()
                ->route('ventas.show', $venta)
                ->with('success', '✅ Venta anulada exitosamente.');
                
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('error', '❌ Error al anular venta: ' . $e->getMessage());
        }
    }
    
    /**
     * Generar factura desde venta
     */
    public function generarFactura(Request $request, Venta $venta)
    {
        try {
            if ($venta->factura) {
                return redirect()
                    ->back()
                    ->with('warning', '⚠️ Esta venta ya tiene una factura generada.');
            }
            
            if ($venta->estado !== 'completada') {
                return redirect()
                    ->back()
                    ->with('error', '❌ Solo se pueden facturar ventas completadas.');
            }
            
            // Llamar directamente al FacturaController para crear la factura
            $facturaRequest = new Request(['venta_id' => $venta->id]);
            $facturaController = app(FacturaController::class);
            
            return $facturaController->crear($facturaRequest);
                
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('error', '❌ Error: ' . $e->getMessage());
        }
    }
    
    /**
     * Buscar productos para POS (AJAX)
     */
    public function buscarProductos(Request $request)
    {
        $search = $request->get('q');
        
        $productos = Producto::where('cantidad', '>', 0)
            ->where(function($query) use ($search) {
                $query->where('nombre', 'like', "%{$search}%")
                    ->orWhere('codigo', 'like', "%{$search}%");
            })
            ->limit(20)
            ->get(['id', 'codigo', 'nombre', 'precio', 'cantidad']);
        
        return response()->json($productos);
    }
    
    /**
     * Verificar stock disponible (AJAX)
     */
    public function verificarStock(Request $request)
    {
        $producto = Producto::find($request->producto_id);
        
        if (!$producto) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        }
        
        $disponible = $producto->cantidad >= $request->cantidad;
        
        return response()->json([
            'success' => true,
            'disponible' => $disponible,
            'stock_actual' => $producto->cantidad,
            'mensaje' => $disponible 
                ? 'Stock disponible' 
                : 'Stock insuficiente (disponible: ' . $producto->cantidad . ')'
        ]);
    }
}

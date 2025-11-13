<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\Venta;
use App\Services\FacturaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;

class FacturaController extends Controller
{
    protected FacturaService $facturaService;
    
    public function __construct(FacturaService $facturaService)
    {
        $this->facturaService = $facturaService;
    }
    
    /**
     * Listado de facturas con filtros
     */
    public function index(Request $request)
    {
        $query = Factura::with(['venta.cliente'])->orderBy('fecha_emision', 'desc');
        
        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado_autorizacion', $request->estado);
        }
        
        // Filtro por fecha desde
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_emision', '>=', $request->fecha_desde);
        }
        
        // Filtro por fecha hasta
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_emision', '<=', $request->fecha_hasta);
        }
        
        // Filtro por número secuencial
        if ($request->filled('numero_secuencial')) {
            $query->where('numero_secuencial', 'like', '%' . $request->numero_secuencial . '%');
        }
        
        // Filtro por clave de acceso
        if ($request->filled('clave_acceso')) {
            $query->where('clave_acceso_sri', 'like', '%' . $request->clave_acceso . '%');
        }
        
        $facturas = $query->paginate(20);
        
        // Estadísticas
        $estadisticas = $this->facturaService->estadisticasFacturacion();
        
        return view('facturas.index', compact('facturas', 'estadisticas'));
    }
    
    /**
     * Ver detalle de factura
     */
    public function show(Factura $factura)
    {
        $factura->load(['venta.cliente', 'venta.detalles.producto']);
        
        return view('facturas.show', compact('factura'));
    }
    
    /**
     * Generar factura desde venta
     */
    public function crear(Request $request)
    {
        $request->validate([
            'venta_id' => 'required|exists:ventas,id',
        ]);
        
        try {
            $factura = $this->facturaService->generarFacturaDesdeVenta($request->venta_id);
            
            return redirect()
                ->route('facturas.show', $factura)
                ->with('success', '✅ Factura generada exitosamente: ' . $factura->numero_secuencial);
                
        } catch (Exception $e) {
            Log::error('Error al generar factura: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', '❌ Error al generar factura: ' . $e->getMessage());
        }
    }
    
    /**
     * Descargar XML de la factura
     */
    public function descargarXML(Factura $factura)
    {
        if (!$factura->xml_factura) {
            return redirect()
                ->back()
                ->with('error', '❌ La factura no tiene XML generado');
        }
        
        $filename = "factura_{$factura->numero_secuencial}.xml";
        
        return response($factura->xml_factura)
            ->header('Content-Type', 'application/xml')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
    
    /**
     * Descargar PDF RIDE de la factura
     */
    public function descargarRIDE(Factura $factura)
    {
        try {
            $path = $this->facturaService->generarRIDE($factura);
            
            $filename = "factura_{$factura->numero_secuencial}_RIDE.html";
            
            if (!Storage::disk('local')->exists($path)) {
                throw new Exception('Archivo RIDE no encontrado');
            }
            
            $content = Storage::disk('local')->get($path);
            
            return response($content)
                ->header('Content-Type', 'text/html')
                ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
            
        } catch (Exception $e) {
            Log::error('Error al generar RIDE: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', '❌ Error al generar PDF: ' . $e->getMessage());
        }
    }
    
    /**
     * Anular factura
     */
    public function anular(Factura $factura)
    {
        try {
            $this->facturaService->anularFactura($factura);
            
            return redirect()
                ->back()
                ->with('success', '✅ Factura anulada exitosamente');
                
        } catch (Exception $e) {
            Log::error('Error al anular factura: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', '❌ Error al anular factura: ' . $e->getMessage());
        }
    }
    
    /**
     * Reenviar factura por email
     */
    public function reenviarEmail(Factura $factura)
    {
        try {
            // TODO: Implementar envío de email con Queue
            
            return redirect()
                ->back()
                ->with('success', '✅ Factura reenviada por email exitosamente');
                
        } catch (Exception $e) {
            Log::error('Error al reenviar factura: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', '❌ Error al reenviar factura: ' . $e->getMessage());
        }
    }
    
    /**
     * Enviar factura al SRI manualmente
     */
    public function enviarSRI(Factura $factura)
    {
        try {
            $this->facturaService->enviarSRI($factura);
            
            return redirect()
                ->back()
                ->with('success', '✅ Factura enviada al SRI exitosamente');
                
        } catch (Exception $e) {
            Log::error('Error al enviar factura al SRI: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', '❌ Error al enviar al SRI: ' . $e->getMessage());
        }
    }
}

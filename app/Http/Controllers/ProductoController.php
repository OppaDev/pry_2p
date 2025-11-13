<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidarStoreProducto;
use App\Http\Requests\ValidarEditProducto;
use App\Models\Producto;
use App\Models\User;
use App\Models\MovimientoInventario;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Stmt\TryCatch;

class ProductoController extends Controller
{
    use AuthorizesRequests;
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Producto::class);
        
        $request->validate([
            'per_page' => 'nullable|integer|in:5,10,15,25,50',
            'search' => 'nullable|string|max:255',
            'categoria_id' => 'nullable|exists:categorias,id',
            'estado' => 'nullable|in:activo,inactivo',
            'bajo_stock' => 'nullable|boolean'
        ], [
            'per_page.integer' => 'El valor debe ser un número entero.',
            'per_page.in' => 'El valor debe ser uno de los siguientes: 5, 10, 15, 25, 50.',
            'search.string' => 'El término de búsqueda debe ser una cadena de texto.',
            'search.max' => 'El término de búsqueda no puede tener más de 255 caracteres.'
        ]);
        
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search');
        
        $query = Producto::with('categoria')
            ->select(['id', 'nombre', 'codigo', 'stock_actual', 'precio', 'stock_minimo', 'estado', 'categoria_id', 'created_at', 'updated_at']);
        
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'LIKE', '%' . $search . '%')
                  ->orWhere('codigo', 'LIKE', '%' . $search . '%')
                  ->orWhere('marca', 'LIKE', '%' . $search . '%');
            });
        }
        
        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }
        
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        } else {
            $query->activos();
        }
        
        if ($request->boolean('bajo_stock')) {
            $query->bajoStock();
        }

        $query->orderBy('nombre');
        
        $categorias = \App\Models\Categoria::orderBy('nombre')->get();
        
        $data = array(
            'productos' => $query->paginate($perPage)->withQueryString(),
            'perPage' => $perPage,
            'search' => $search,
            'categorias' => $categorias
        );
        return view('productos.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('productos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ValidarStoreProducto $request)
    {
        $this->authorize('create', Producto::class);
        
        try {
            DB::beginTransaction();
            
            // Crear producto sin sufijo aleatorio
            $producto = Producto::create($request->validated());

            DB::commit();
            
            Log::info('Producto creado', [
                'producto_id' => $producto->id,
                'usuario_id' => Auth::id()
            ]);
            
            return redirect()->route('productos.index')->with('success', 'Producto creado exitosamente.');

        } catch (\Throwable $th) {
            DB::rollBack();
            
            Log::error('Error al crear producto', [
                'error' => $th->getMessage(),
                'usuario_id' => Auth::id()
            ]);
            
            return redirect()->route('productos.create')->with('error', 'Error al crear producto.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        // Obtener auditorías del producto con información del usuario que hizo el cambio
        $audits = $producto->audits()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('productos.show', compact('producto', 'audits'));
    }

    /**
     * Display audit history for a specific product.
     */
    public function auditHistory(Producto $producto, Request $request)
    {
        $request->validate([
            'per_page' => 'nullable|integer|in:5,10,15,25,50',
            'event' => 'nullable|string|in:created,updated,deleted,restored'
        ]);

        $perPage = $request->get('per_page', 10);
        $eventFilter = $request->get('event');

        $query = $producto->audits()->with('user');

        if ($eventFilter) {
            $query->where('event', $eventFilter);
        }

        $audits = $query->orderBy('created_at', 'desc')
                       ->paginate($perPage)
                       ->withQueryString();

        return view('productos.audit-history', compact('producto', 'audits', 'eventFilter', 'perPage'));
    }

    /**
     * Display a listing of deleted products.
     */
    public function deletedProducts(Request $request)
    {
        $request->validate([
            'per_page' => 'nullable|integer|in:5,10,15,25,50',
            'search' => 'nullable|string|max:255'
        ], [
            'per_page.integer' => 'El valor debe ser un número entero.',
            'per_page.in' => 'El valor debe ser uno de los siguientes: 5, 10, 15, 25, 50.',
            'search.string' => 'El término de búsqueda debe ser una cadena de texto.',
            'search.max' => 'El término de búsqueda no puede tener más de 255 caracteres.'
        ]);
        
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search');
        
        $query = Producto::onlyTrashed()->select(['id', 'nombre', 'codigo', 'stock_actual', 'precio', 'created_at', 'updated_at', 'deleted_at']);
        
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'LIKE', '%' . $search . '%')
                  ->orWhere('codigo', 'LIKE', '%' . $search . '%');
            });
        }

        $query->orderBy('deleted_at', 'desc');
        
        $data = array(
            'productos' => $query->paginate($perPage)->withQueryString(),
            'perPage' => $perPage,
            'search' => $search
        );
        return view('productos.deleteProducts', $data);
    }

    /**
     * Restore a soft deleted product.
     */
    public function restore($id, Request $request)
    {
        $request->validate([
            'motivo' => 'required|string|max:255',
            'password' => 'required|string'
        ], [
            'motivo.required' => 'El motivo de restauración es obligatorio.',
            'motivo.max' => 'El motivo no puede exceder 255 caracteres.',
            'password.required' => 'La contraseña es obligatoria para confirmar la restauración.'
        ]);

        // Verificar la contraseña del usuario actual
        if (!Hash::check($request->password, Auth::user()->password)) {
            return redirect()->back()
                ->with('error', 'La contraseña ingresada es incorrecta.')
                ->withInput();
        }

        try {
            $producto = Producto::onlyTrashed()->findOrFail($id);
            
            DB::beginTransaction();
            
            // Registrar el motivo en los metadatos de auditoría
            $producto->auditComment = $request->motivo;
            $producto->restore();
            
            DB::commit();
            
            return redirect()->route('productos.deleted')->with('success', 'Producto restaurado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al restaurar producto: ' . $e->getMessage());
            return redirect()->route('productos.deleted')->with('error', 'Error al restaurar el producto: ' . $e->getMessage());
        }
    }

    /**
     * Permanently delete a product.
     */
    public function forceDelete($id, Request $request)
    {
        $request->validate([
            'motivo' => 'required|string|min:10|max:255',
            'password' => 'required|string'
        ], [
            'motivo.required' => 'El comentario es obligatorio para eliminar permanentemente.',
            'motivo.min' => 'El comentario debe tener al menos 10 caracteres.',
            'motivo.max' => 'El comentario no puede exceder 255 caracteres.',
            'password.required' => 'La contraseña es obligatoria para confirmar la eliminación permanente.'
        ]);

        try {
            $producto = Producto::onlyTrashed()->findOrFail($id);
            
            // Verificar contraseña del usuario logueado
            if (!Hash::check($request->password, Auth::user()->password)) {
                return redirect()->route('productos.deleted')->with('error', 'Contraseña incorrecta. No se puede eliminar permanentemente.');
            }
            
            DB::beginTransaction();
            
            // Crear un registro de auditoría manual antes de la eliminación permanente
            \OwenIt\Auditing\Models\Audit::create([
                'user_type' => get_class(Auth::user()),
                'user_id' => Auth::id(),
                'event' => 'force_deleted',
                'auditable_type' => get_class($producto),
                'auditable_id' => $producto->id,
                'old_values' => $producto->toArray(),
                'new_values' => [],
                'url' => $request->url(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'tags' => json_encode([
                    'motivo:' . $request->motivo, 
                    'accion:eliminacion_permanente',
                    'password_verificada:true'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            Log::info('Producto eliminado permanentemente', [
                'deleted_producto_id' => $producto->id,
                'deleted_producto_codigo' => $producto->codigo,
                'deleted_producto_nombre' => $producto->nombre,
                'admin_user_id' => Auth::id(),
                'admin_user_name' => Auth::user()->name,
                'motivo' => $request->motivo,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            $producto->forceDelete();
            
            DB::commit();
            
            return redirect()->route('productos.deleted')->with('success', 'Producto eliminado permanentemente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar permanentemente producto: ' . $e->getMessage(), [
                'producto_id' => $id,
                'user_id' => Auth::id(),
                'motivo' => $request->motivo ?? 'N/A'
            ]);
            
            return redirect()->route('productos.deleted')->with('error', 'Error al eliminar permanentemente el producto: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        $this->authorize('update', $producto);
        
        $categorias = \App\Models\Categoria::where('estado', 'activo')
            ->orderBy('nombre')
            ->get();
        
        return view('productos.edit', compact('producto', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ValidarEditProducto $request, Producto $producto)
    {
        $this->authorize('update', $producto);
        
        try {
            DB::beginTransaction();
            
            // Actualizar sin agregar sufijo aleatorio
            $producto->update($request->validated());
            
            DB::commit();
            
            Log::info('Producto actualizado', [
                'producto_id' => $producto->id,
                'usuario_id' => Auth::id()
            ]);
            
            return redirect()->route('productos.index')->with('success', 'Producto actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar producto: ' . $e->getMessage());
            return redirect()->route('productos.edit', $producto)->with('error', 'Error al actualizar el producto.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto, Request $request)
    {
        $this->authorize('delete', $producto);
        
        $request->validate([
            'motivo' => 'required|string|max:255',
            'password' => 'required|string'
        ], [
            'motivo.required' => 'El motivo de eliminación es obligatorio.',
            'motivo.max' => 'El motivo no puede exceder 255 caracteres.',
            'password.required' => 'La contraseña es obligatoria para confirmar la eliminación.'
        ]);

        // Verificar la contraseña del usuario actual
        if (!Hash::check($request->password, Auth::user()->password)) {
            return redirect()->back()
                ->with('error', 'La contraseña ingresada es incorrecta.')
                ->withInput();
        }

        try {
            DB::beginTransaction();
            
            // Registrar el motivo en los metadatos de auditoría
            $producto->auditComment = $request->motivo;
            $producto->delete();
            
            DB::commit();
            
            return redirect()->route('productos.index')
                ->with('success', 'Producto eliminado exitosamente.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar producto: ' . $e->getMessage(), [
                'producto_id' => $producto->id,
                'user_id' => Auth::id()
            ]);
            
            return redirect()->route('productos.index')
                ->with('error', 'Error al eliminar el producto.');
        }
    }
    
    /**
     * Mostrar productos con bajo stock.
     */
    public function bajosEnStock(Request $request)
    {
        $this->authorize('viewStock', Producto::class);
        
        $query = Producto::with('categoria')
            ->bajoStock()
            ->activos();
        
        // Filtro por categoría
        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }
        
        $productos = $query->orderBy('stock_actual')->paginate(20)->withQueryString();
        
        $categorias = \App\Models\Categoria::orderBy('nombre')->get();
        
        return view('productos.bajos-stock', compact('productos', 'categorias'));
    }
    
    /**
     * Ajustar el stock de un producto.
     */
    public function ajustarStock(Request $request, Producto $producto)
    {
        $this->authorize('adjustStock', Producto::class);
        
        $request->validate([
            'tipo_movimiento' => 'required|in:entrada,salida,ajuste',
            'cantidad' => 'required|integer|min:1',
            'descripcion' => 'required|string|max:500',
        ], [
            'tipo_movimiento.required' => 'El tipo de movimiento es obligatorio.',
            'cantidad.required' => 'La cantidad es obligatoria.',
            'cantidad.integer' => 'La cantidad debe ser un número entero.',
            'cantidad.min' => 'La cantidad debe ser al menos 1.',
            'descripcion.required' => 'La descripción es obligatoria.',
        ]);
        
        try {
            DB::beginTransaction();
            
            $stockAnterior = $producto->stock_actual;
            $cantidad = $request->cantidad;
            
            switch ($request->tipo_movimiento) {
                case 'entrada':
                    MovimientoInventario::registrarIngreso(
                        $producto,
                        $cantidad,
                        Auth::user(),
                        $request->descripcion
                    );
                    break;
                    
                case 'salida':
                    if ($producto->stock_actual < $cantidad) {
                        return redirect()->back()
                            ->with('error', 'No hay suficiente stock para realizar la salida.')
                            ->withInput();
                    }
                    
                    MovimientoInventario::registrarSalida(
                        $producto,
                        $cantidad,
                        Auth::user(),
                        $request->descripcion
                    );
                    break;
                    
                case 'ajuste':
                    MovimientoInventario::registrarAjuste(
                        $producto,
                        $cantidad,
                        Auth::user(),
                        $request->descripcion
                    );
                    break;
            }
            
            DB::commit();
            
            Log::info('Stock ajustado', [
                'producto_id' => $producto->id,
                'tipo' => $request->tipo_movimiento,
                'cantidad' => $cantidad,
                'stock_anterior' => $stockAnterior,
                'stock_nuevo' => $producto->fresh()->stock_actual,
                'usuario_id' => Auth::id()
            ]);
            
            return redirect()
                ->route('productos.show', $producto)
                ->with('success', 'Stock ajustado exitosamente.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al ajustar stock', [
                'producto_id' => $producto->id,
                'error' => $e->getMessage(),
                'usuario_id' => Auth::id()
            ]);
            
            return redirect()
                ->back()
                ->with('error', 'Error al ajustar el stock.')
                ->withInput();
        }
    }
    
    /**
     * Ver movimientos de inventario de un producto.
     */
    public function movimientos(Producto $producto, Request $request)
    {
        $this->authorize('view', $producto);
        
        $request->validate([
            'tipo' => 'nullable|in:entrada,salida,ajuste',
            'fecha_desde' => 'nullable|date',
            'fecha_hasta' => 'nullable|date|after_or_equal:fecha_desde',
        ]);
        
        $query = $producto->movimientosInventario()
            ->with('responsable');
        
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha', '>=', $request->fecha_desde);
        }
        
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha', '<=', $request->fecha_hasta);
        }
        
        $movimientos = $query->orderBy('fecha', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->appends($request->except('page'));
        
        return view('productos.movimientos', compact('producto', 'movimientos'));
    }
    
    /**
     * Exportar inventario a CSV/Excel.
     */
    public function exportarInventario()
    {
        $this->authorize('viewAny', Producto::class);
        
        $productos = Producto::with('categoria')
            ->activos()
            ->orderBy('categoria_id')
            ->orderBy('nombre')
            ->get();
        
        $filename = 'inventario_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($productos) {
            $file = fopen('php://output', 'w');
            
            // BOM para UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Encabezados
            fputcsv($file, [
                'Código',
                'Nombre',
                'Marca',
                'Categoría',
                'Presentación',
                'Capacidad (ml)',
                'Stock Actual',
                'Stock Mínimo',
                'Precio',
                'Estado',
            ]);
            
            // Datos
            foreach ($productos as $producto) {
                fputcsv($file, [
                    $producto->codigo,
                    $producto->nombre,
                    $producto->marca,
                    $producto->categoria?->nombre,
                    $producto->presentacion,
                    $producto->volumen_ml,
                    $producto->stock_actual,
                    $producto->stock_minimo,
                    number_format((float)$producto->precio, 2),
                    $producto->estado,
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}

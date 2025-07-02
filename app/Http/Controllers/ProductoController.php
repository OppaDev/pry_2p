<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidarStoreProducto;
use App\Http\Requests\ValidarEditProducto;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Stmt\TryCatch;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
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
        
        $query = Producto::select(['id', 'nombre', 'codigo', 'cantidad', 'precio', 'created_at', 'updated_at']);
        
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'LIKE', '%' . $search . '%')
                  ->orWhere('codigo', 'LIKE', '%' . $search . '%');
            });
        }

        $query->orderBy('created_at', 'desc');
        
        $data = array(
            'productos' => $query->paginate($perPage)->withQueryString(),
            'perPage' => $perPage,
            'search' => $search
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
        try {
            DB::beginTransaction();
            
            // Crear producto dentro de la transacción
            $producto = Producto::create($request->only(['nombre', 'codigo', 'cantidad', 'precio']));
            $producto->nombre = $request->nombre;
            $producto->save();

            // $user = Auth::user();
            // $user->name="OppaDev";
            // $user->save();

            DB::commit();
            
            return redirect()->route('productos.index')->with('success', 'Producto creado exitosamente.');

        } catch (\Throwable $th) {
            DB::rollBack();
            
            return redirect()->route('productos.create')->with('error', 'Error al crear producto: ');
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
        
        $query = Producto::onlyTrashed()->select(['id', 'nombre', 'codigo', 'cantidad', 'precio', 'created_at', 'updated_at', 'deleted_at']);
        
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
            'motivo' => 'required|string|max:255'
        ], [
            'motivo.required' => 'El motivo de restauración es obligatorio.',
            'motivo.max' => 'El motivo no puede exceder 255 caracteres.'
        ]);

        try {
            $producto = Producto::onlyTrashed()->findOrFail($id);
            
            // Registrar el motivo en los metadatos de auditoría
            $producto->auditComment = $request->motivo;
            $producto->restore();
            
            return redirect()->route('productos.deleted')->with('success', 'Producto restaurado exitosamente.');
        } catch (\Exception $e) {
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
            
            return redirect()->route('productos.deleted')->with('success', 'Producto eliminado permanentemente.');
        } catch (\Exception $e) {
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
        return view('productos.edit', compact('producto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ValidarEditProducto $request, Producto $producto)
    {
        // Actualizar el producto con los datos validados
        $producto->update($request->only(['nombre', 'codigo', 'cantidad', 'precio']));
        $producto->codigo = $producto->codigo . rand(100, 999);
        $producto->save();

        
        return redirect()->route('productos.index')->with('success', 'Producto actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto, Request $request)
    {
        $request->validate([
            'motivo' => 'required|string|max:255'
        ], [
            'motivo.required' => 'El motivo de eliminación es obligatorio.',
            'motivo.max' => 'El motivo no puede exceder 255 caracteres.'
        ]);

        try {
            
            // if ($producto->hasRelatedData()) {
            //     return redirect()->route('productos.index')
            //         ->with('error', 'No se puede eliminar el producto porque tiene datos relacionados.');
            // }
            
            // Registrar el motivo en los metadatos de auditoría
            $producto->auditComment = $request->motivo;
            $producto->delete();
            
            return redirect()->route('productos.index')
                ->with('success', 'Producto eliminado exitosamente.');
                
        } catch (\Exception $e) {
            Log::error('Error al eliminar producto: ' . $e->getMessage(), [
                'producto_id' => $producto->id,
                'user_id' => Auth::id()
            ]);
            
            return redirect()->route('productos.index')
                ->with('error', 'Error al eliminar el producto. Por favor, intenta nuevamente.');
        }
    }
}

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
            $producto->nombre = $request->nombre . rand(1, 10);
            $producto->save();

            $user = Auth::user();
            $user->name="Acutualizado";
            $user->save();

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
        //
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
    public function restore($id)
    {
        try {
            $producto = Producto::onlyTrashed()->findOrFail($id);
            $producto->restore();
            
            return redirect()->route('productos.deleted')->with('success', 'Producto restaurado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('productos.deleted')->with('error', 'Error al restaurar el producto: ' . $e->getMessage());
        }
    }

    /**
     * Permanently delete a product.
     */
    public function forceDelete($id)
    {
        try {
            $producto = Producto::onlyTrashed()->findOrFail($id);
            $producto->forceDelete();
            
            return redirect()->route('productos.deleted')->with('success', 'Producto eliminado permanentemente.');
        } catch (\Exception $e) {
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
    public function destroy(Producto $producto)
    {
        try {
            // Opcional: Verificar si el producto tiene dependencias
            // if ($producto->hasRelatedData()) {
            //     return redirect()->route('productos.index')
            //         ->with('error', 'No se puede eliminar el producto porque tiene datos relacionados.');
            // }
            
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

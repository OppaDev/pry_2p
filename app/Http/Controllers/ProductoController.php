<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidarStoreProducto;
use App\Http\Requests\ValidarEditProducto;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        //
    }
}

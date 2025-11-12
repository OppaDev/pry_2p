<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidarStoreCategoria;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CategoriaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Categoria::class);

        $query = Categoria::withCount('productos');

        // Búsqueda por nombre
        if ($request->filled('buscar')) {
            $query->where('nombre', 'LIKE', '%' . $request->buscar . '%')
                ->orWhere('descripcion', 'LIKE', '%' . $request->buscar . '%');
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        } else {
            // Por defecto, solo activas
            $query->where('estado', 'activo');
        }

        $categorias = $query->orderBy('nombre')
            ->paginate(15)
            ->appends($request->except('page'));

        return view('categorias.index', compact('categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Categoria::class);

        return view('categorias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ValidarStoreCategoria $request)
    {
        try {
            $categoria = Categoria::create($request->validated());

            Log::info('Categoría creada', [
                'categoria_id' => $categoria->id,
                'usuario_id' => Auth::id()
            ]);

            return redirect()
                ->route('categorias.show', $categoria)
                ->with('success', 'Categoría creada exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al crear categoría', [
                'error' => $e->getMessage(),
                'usuario_id' => Auth::id()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al crear la categoría. Por favor intente nuevamente.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Categoria $categoria)
    {
        $this->authorize('view', $categoria);

        // Cargar productos de la categoría
        $productos = $categoria->productos()
            ->withCount('detallesVenta')
            ->paginate(12);

        // Estadísticas
        $estadisticas = [
            'total_productos' => $categoria->productos()->count(),
            'productos_activos' => $categoria->productos()->activos()->count(),
            'productos_bajo_stock' => $categoria->productos()->bajoStock()->count(),
        ];

        return view('categorias.show', compact('categoria', 'productos', 'estadisticas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categoria $categoria)
    {
        $this->authorize('update', $categoria);

        return view('categorias.edit', compact('categoria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ValidarStoreCategoria $request, Categoria $categoria)
    {
        try {
            $categoria->update($request->validated());

            Log::info('Categoría actualizada', [
                'categoria_id' => $categoria->id,
                'usuario_id' => Auth::id()
            ]);

            return redirect()
                ->route('categorias.show', $categoria)
                ->with('success', 'Categoría actualizada exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar categoría', [
                'categoria_id' => $categoria->id,
                'error' => $e->getMessage(),
                'usuario_id' => Auth::id()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al actualizar la categoría. Por favor intente nuevamente.');
        }
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy(Categoria $categoria)
    {
        $this->authorize('delete', $categoria);

        try {
            // Verificar si tiene productos asociados
            if ($categoria->productos()->count() > 0) {
                return redirect()
                    ->back()
                    ->with('warning', 'No se puede eliminar la categoría porque tiene productos asociados. Se desactivará en su lugar.');
            }

            $categoria->delete();

            Log::warning('Categoría eliminada', [
                'categoria_id' => $categoria->id,
                'usuario_id' => Auth::id()
            ]);

            return redirect()
                ->route('categorias.index')
                ->with('success', 'Categoría eliminada exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al eliminar categoría', [
                'categoria_id' => $categoria->id,
                'error' => $e->getMessage(),
                'usuario_id' => Auth::id()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Error al eliminar la categoría. Por favor intente nuevamente.');
        }
    }

    /**
     * Restore a soft-deleted category.
     */
    public function restore($id)
    {
        $categoria = Categoria::withTrashed()->findOrFail($id);
        
        $this->authorize('restore', $categoria);

        try {
            $categoria->restore();
            $categoria->update(['estado' => 'activo']);

            Log::info('Categoría restaurada', [
                'categoria_id' => $categoria->id,
                'usuario_id' => Auth::id()
            ]);

            return redirect()
                ->route('categorias.show', $categoria)
                ->with('success', 'Categoría restaurada exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al restaurar categoría', [
                'categoria_id' => $categoria->id,
                'error' => $e->getMessage(),
                'usuario_id' => Auth::id()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Error al restaurar la categoría.');
        }
    }
}

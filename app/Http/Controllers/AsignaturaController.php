<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class AsignaturaController extends Controller
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

        $query = Asignatura::select(['id', 'nombre', 'codigo', 'created_at', 'updated_at']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'LIKE', '%' . $search . '%')
                  ->orWhere('codigo', 'LIKE', '%' . $search . '%');
            });
        }

        $query->orderBy('created_at', 'desc');

        $data = [
            'asignaturas' => $query->paginate($perPage)->withQueryString(),
            'perPage' => $perPage,
            'search' => $search
        ];

        return view('asignaturas.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('asignaturas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:50|unique:asignaturas,codigo'
        ]);

        try {
            DB::beginTransaction();

            $asignatura = Asignatura::create($request->only(['nombre', 'codigo']));

            DB::commit();

            return redirect()->route('asignaturas.index')->with('success', 'Asignatura creada exitosamente.');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->route('asignaturas.create')->with('error', 'Error al crear asignatura: ' . $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Asignatura $asignatura)
    {
        return view('asignaturas.show', compact('asignatura'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asignatura $asignatura)
    {
        return view('asignaturas.edit', compact('asignatura'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asignatura $asignatura)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:50|unique:asignaturas,codigo,' . $asignatura->id
        ]);

        try {
            DB::beginTransaction();

            $asignatura->update($request->only(['nombre', 'codigo']));

            DB::commit();

            return redirect()->route('asignaturas.index')->with('success', 'Asignatura actualizada exitosamente.');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->route('asignaturas.edit', $asignatura)->with('error', 'Error al actualizar asignatura: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asignatura $asignatura)
    {
        try {
            DB::beginTransaction();

            $asignatura->delete();

            DB::commit();

            return redirect()->route('asignaturas.index')->with('success', 'Asignatura eliminada exitosamente.');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->route('asignaturas.index')->with('error', 'Error al eliminar asignatura: ' . $th->getMessage());
        }
    }
}

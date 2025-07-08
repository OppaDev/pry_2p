<?php

namespace App\Http\Controllers;

use App\Models\Nota;
use App\Models\User;
use App\Models\Asignatura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Si es estudiante, solo ver sus propias notas
        if ($user->esEstudiante()) {
            return $this->indexEstudiante($request);
        }

        // Si es docente, ver notas de sus asignaturas
        if ($user->esDocente()) {
            return $this->indexDocente($request);
        }

        abort(403, 'No tienes permisos para acceder a esta sección.');
    }

    /**
     * Vista de notas para estudiante
     */
    private function indexEstudiante(Request $request)
    {
        $user = Auth::user();

        $notas = Nota::with(['asignatura'])
            ->delEstudiante($user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('notas.estudiante.index', compact('notas'));
    }

    /**
     * Vista de notas para docente
     */
    private function indexDocente(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'asignatura_id' => 'nullable|exists:asignaturas,id',
            'search' => 'nullable|string|max:255'
        ]);

        // Obtener asignaturas del docente
        $asignaturasDocente = $user->asignaturas;

        $query = Nota::with(['estudiante', 'asignatura'])
            ->whereHas('asignatura', function($q) use ($user) {
                $q->whereHas('docentes', function($subQ) use ($user) {
                    $subQ->where('user_id', $user->id);
                });
            });

        // Filtrar por asignatura si se especifica
        if ($request->filled('asignatura_id')) {
            $query->where('asignatura_id', $request->asignatura_id);
        }

        // Filtrar por nombre de estudiante si se especifica
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('estudiante', function($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('email', 'LIKE', '%' . $search . '%');
            });
        }

        $notas = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('notas.docente.index', compact('notas', 'asignaturasDocente'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = Auth::user();

        if (!$user->esDocente()) {
            abort(403, 'Solo los docentes pueden crear notas.');
        }

        // Obtener asignaturas del docente
        $asignaturas = $user->asignaturas;

        // Obtener estudiantes
        $estudiantes = User::whereHas('roles', function($q) {
            $q->where('name', 'estudiante');
        })->get();

        $asignaturaSeleccionada = $request->get('asignatura_id');

        return view('notas.create', compact('asignaturas', 'estudiantes', 'asignaturaSeleccionada'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->esDocente()) {
            abort(403, 'Solo los docentes pueden crear notas.');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'asignatura_id' => 'required|exists:asignaturas,id',
            'nota_1' => 'required|numeric|min:0|max:20',
            'nota_2' => 'required|numeric|min:0|max:20',
            'nota_3' => 'required|numeric|min:0|max:20'
        ], [
            'user_id.required' => 'Debe seleccionar un estudiante.',
            'user_id.exists' => 'El estudiante seleccionado no existe.',
            'asignatura_id.required' => 'Debe seleccionar una asignatura.',
            'asignatura_id.exists' => 'La asignatura seleccionada no existe.',
            'nota_1.required' => 'La nota 1 es obligatoria.',
            'nota_1.numeric' => 'La nota 1 debe ser un número.',
            'nota_1.min' => 'La nota 1 debe ser mayor o igual a 0.',
            'nota_1.max' => 'La nota 1 debe ser menor o igual a 20.',
            'nota_2.required' => 'La nota 2 es obligatoria.',
            'nota_2.numeric' => 'La nota 2 debe ser un número.',
            'nota_2.min' => 'La nota 2 debe ser mayor o igual a 0.',
            'nota_2.max' => 'La nota 2 debe ser menor o igual a 20.',
            'nota_3.required' => 'La nota 3 es obligatoria.',
            'nota_3.numeric' => 'La nota 3 debe ser un número.',
            'nota_3.min' => 'La nota 3 debe ser mayor o igual a 0.',
            'nota_3.max' => 'La nota 3 debe ser menor o igual a 20.'
        ]);

        // Verificar que el docente está asignado a la asignatura
        $asignatura = Asignatura::findOrFail($request->asignatura_id);
        if (!$asignatura->tieneDocente($user->id)) {
            abort(403, 'No tienes permisos para registrar notas en esta asignatura.');
        }

        // Verificar que el estudiante no tenga ya notas en esta asignatura
        $notaExistente = Nota::where('user_id', $request->user_id)
            ->where('asignatura_id', $request->asignatura_id)
            ->first();

        if ($notaExistente) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'El estudiante ya tiene notas registradas en esta asignatura.']);
        }

        try {
            DB::beginTransaction();

            $nota = Nota::create([
                'user_id' => $request->user_id,
                'asignatura_id' => $request->asignatura_id,
                'nota_1' => $request->nota_1,
                'nota_2' => $request->nota_2,
                'nota_3' => $request->nota_3
                // promedio y estado_final se calculan automáticamente
            ]);

            DB::commit();

            return redirect()->route('notas.index')
                ->with('success', 'Notas registradas exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear nota: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al registrar las notas. Por favor, inténtalo de nuevo.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Nota $nota)
    {
        $user = Auth::user();

        // Verificar permisos
        if ($user->esEstudiante() && $nota->user_id !== $user->id) {
            abort(403, 'No puedes ver notas de otros estudiantes.');
        }

        if ($user->esDocente() && !$nota->asignatura->tieneDocente($user->id)) {
            abort(403, 'No puedes ver notas de asignaturas que no tienes asignadas.');
        }

        $nota->load(['estudiante', 'asignatura']);

        return view('notas.show', compact('nota'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Nota $nota)
    {
        $user = Auth::user();

        if (!$user->esDocente()) {
            abort(403, 'Solo los docentes pueden editar notas.');
        }

        // Verificar que el docente está asignado a la asignatura
        if (!$nota->asignatura->tieneDocente($user->id)) {
            abort(403, 'No puedes editar notas de asignaturas que no tienes asignadas.');
        }

        return view('notas.edit', compact('nota'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Nota $nota)
    {
        $user = Auth::user();

        if (!$user->esDocente()) {
            abort(403, 'Solo los docentes pueden editar notas.');
        }

        // Verificar que el docente está asignado a la asignatura
        if (!$nota->asignatura->tieneDocente($user->id)) {
            abort(403, 'No puedes editar notas de asignaturas que no tienes asignadas.');
        }

        $request->validate([
            'nota_1' => 'required|numeric|min:0|max:20',
            'nota_2' => 'required|numeric|min:0|max:20',
            'nota_3' => 'required|numeric|min:0|max:20',
            'motivo' => 'required|string|min:10|max:500'
        ], [
            'nota_1.required' => 'La nota 1 es obligatoria.',
            'nota_1.numeric' => 'La nota 1 debe ser un número.',
            'nota_1.min' => 'La nota 1 debe ser mayor o igual a 0.',
            'nota_1.max' => 'La nota 1 debe ser menor o igual a 20.',
            'nota_2.required' => 'La nota 2 es obligatoria.',
            'nota_2.numeric' => 'La nota 2 debe ser un número.',
            'nota_2.min' => 'La nota 2 debe ser mayor o igual a 0.',
            'nota_2.max' => 'La nota 2 debe ser menor o igual a 20.',
            'nota_3.required' => 'La nota 3 es obligatoria.',
            'nota_3.numeric' => 'La nota 3 debe ser un número.',
            'nota_3.min' => 'La nota 3 debe ser mayor o igual a 0.',
            'nota_3.max' => 'La nota 3 debe ser menor o igual a 20.',
            'motivo.required' => 'El motivo de la modificación es obligatorio.',
            'motivo.min' => 'El motivo debe tener al menos 10 caracteres.',
            'motivo.max' => 'El motivo no puede exceder 500 caracteres.'
        ]);

        try {
            DB::beginTransaction();

            // Establecer el motivo para la auditoría
            $nota->auditComment = $request->motivo;

            $nota->update([
                'nota_1' => $request->nota_1,
                'nota_2' => $request->nota_2,
                'nota_3' => $request->nota_3
                // promedio y estado_final se recalculan automáticamente
            ]);

            DB::commit();

            return redirect()->route('notas.index')
                ->with('success', 'Notas actualizadas exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar nota: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar las notas. Por favor, inténtalo de nuevo.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Nota $nota)
    {
        $user = Auth::user();

        if (!$user->esDocente()) {
            abort(403, 'Solo los docentes pueden eliminar notas.');
        }

        // Verificar que el docente está asignado a la asignatura
        if (!$nota->asignatura->tieneDocente($user->id)) {
            abort(403, 'No puedes eliminar notas de asignaturas que no tienes asignadas.');
        }

        $request->validate([
            'motivo' => 'required|string|min:10|max:500'
        ], [
            'motivo.required' => 'El motivo de la eliminación es obligatorio.',
            'motivo.min' => 'El motivo debe tener al menos 10 caracteres.',
            'motivo.max' => 'El motivo no puede exceder 500 caracteres.'
        ]);

        try {
            DB::beginTransaction();

            // Establecer el motivo para la auditoría
            $nota->auditComment = $request->motivo;

            $nota->delete();

            DB::commit();

            return redirect()->route('notas.index')
                ->with('success', 'Notas eliminadas exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar nota: ' . $e->getMessage());

            return redirect()->route('notas.index')
                ->with('error', 'Error al eliminar las notas. Por favor, inténtalo de nuevo.');
        }
    }
}

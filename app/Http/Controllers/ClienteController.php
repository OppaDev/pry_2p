<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidarStoreCliente;
use App\Http\Requests\ValidarEditCliente;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ClienteController extends Controller
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
        $this->authorize('viewAny', Cliente::class);

        $query = Cliente::query();

        // Búsqueda por identificación
        if ($request->filled('buscar_identificacion')) {
            $query->where('identificacion', 'LIKE', '%' . $request->buscar_identificacion . '%');
        }

        // Búsqueda por nombre/apellido
        if ($request->filled('buscar_nombre')) {
            $search = $request->buscar_nombre;
            $query->where(function ($q) use ($search) {
                $q->where('nombres', 'LIKE', '%' . $search . '%')
                    ->orWhere('apellidos', 'LIKE', '%' . $search . '%');
            });
        }

        // Filtro por tipo de identificación
        if ($request->filled('tipo_identificacion')) {
            $query->where('tipo_identificacion', $request->tipo_identificacion);
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        } else {
            // Por defecto, solo activos
            $query->where('estado', 'activo');
        }

        // Filtro para mayores de edad (clientes válidos para comprar licor)
        if ($request->boolean('solo_mayores_edad')) {
            $query->mayoresDeEdad();
        }

        $clientes = $query->orderBy('apellidos')
            ->orderBy('nombres')
            ->paginate(15)
            ->appends($request->except('page'));

        return view('clientes.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Cliente::class);

        return view('clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ValidarStoreCliente $request)
    {
        try {
            $cliente = Cliente::create($request->validated());

            Log::info('Cliente creado', [
                'cliente_id' => $cliente->id,
                'usuario_id' => Auth::id()
            ]);

            return redirect()
                ->route('clientes.show', $cliente)
                ->with('success', 'Cliente registrado exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al crear cliente', [
                'error' => $e->getMessage(),
                'usuario_id' => Auth::id()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al registrar el cliente. Por favor intente nuevamente.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente)
    {
        $this->authorize('view', $cliente);

        // Cargar relaciones necesarias
        $cliente->load(['ventas' => function ($query) {
            $query->latest()->take(10);
        }]);

        // Estadísticas del cliente
        $estadisticas = [
            'total_compras' => $cliente->ventas()->count(),
            'monto_total' => $cliente->ventas()->sum('total'),
            'ultima_compra' => $cliente->ventas()->latest()->first()?->fecha,
        ];

        return view('clientes.show', compact('cliente', 'estadisticas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente)
    {
        $this->authorize('update', $cliente);

        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ValidarEditCliente $request, Cliente $cliente)
    {
        try {
            $cliente->update($request->validated());

            Log::info('Cliente actualizado', [
                'cliente_id' => $cliente->id,
                'usuario_id' => Auth::id()
            ]);

            return redirect()
                ->route('clientes.show', $cliente)
                ->with('success', 'Cliente actualizado exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar cliente', [
                'cliente_id' => $cliente->id,
                'error' => $e->getMessage(),
                'usuario_id' => Auth::id()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al actualizar el cliente. Por favor intente nuevamente.');
        }
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy(Request $request, Cliente $cliente)
    {
        $this->authorize('delete', $cliente);

        try {
            // Verificar si tiene ventas asociadas
            if ($cliente->ventas()->count() > 0) {
                return redirect()
                    ->back()
                    ->with('warning', 'No se puede eliminar el cliente porque tiene ventas registradas. Se desactivará en su lugar.');
            }

            $cliente->delete();

            Log::warning('Cliente eliminado', [
                'cliente_id' => $cliente->id,
                'motivo' => $request->input('motivo_eliminacion'),
                'usuario_id' => Auth::id()
            ]);

            return redirect()
                ->route('clientes.index')
                ->with('success', 'Cliente eliminado exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al eliminar cliente', [
                'cliente_id' => $cliente->id,
                'error' => $e->getMessage(),
                'usuario_id' => Auth::id()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Error al eliminar el cliente. Por favor intente nuevamente.');
        }
    }

    /**
     * Restore a soft-deleted client.
     */
    public function restore($id)
    {
        $cliente = Cliente::withTrashed()->findOrFail($id);
        
        $this->authorize('restore', $cliente);

        try {
            $cliente->restore();
            $cliente->update(['estado' => 'activo']);

            Log::info('Cliente restaurado', [
                'cliente_id' => $cliente->id,
                'usuario_id' => Auth::id()
            ]);

            return redirect()
                ->route('clientes.show', $cliente)
                ->with('success', 'Cliente restaurado exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al restaurar cliente', [
                'cliente_id' => $cliente->id,
                'error' => $e->getMessage(),
                'usuario_id' => Auth::id()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Error al restaurar el cliente.');
        }
    }

    /**
     * Display audit history for a client.
     */
    public function auditHistory(Cliente $cliente)
    {
        $this->authorize('view', $cliente);

        $audits = $cliente->audits()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('clientes.audit-history', compact('cliente', 'audits'));
    }

    /**
     * Show only deleted clients.
     */
    public function deletedClientes()
    {
        $this->authorize('viewAny', Cliente::class);

        $clientes = Cliente::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->paginate(15);

        return view('clientes.deleted', compact('clientes'));
    }

    /**
     * Verificar si el cliente es mayor de edad.
     */
    public function verificarEdad(Request $request)
    {
        $this->authorize('verifyAge', Cliente::class);

        $request->validate([
            'identificacion' => 'required|string',
        ]);

        $cliente = Cliente::porIdentificacion($request->identificacion)->first();

        if (!$cliente) {
            return response()->json([
                'success' => false,
                'message' => 'Cliente no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'es_mayor_edad' => $cliente->es_mayor_edad,
            'edad' => $cliente->edad,
            'nombre_completo' => $cliente->nombre_completo
        ]);
    }
}

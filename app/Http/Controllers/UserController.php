<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\ValidarEditUser;
use App\Http\Requests\ValidarStoreUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
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
        
        $query = User::select(['id', 'name', 'email', 'email_verified_at', 'created_at', 'updated_at']);
        
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('email', 'LIKE', '%' . $search . '%');
            });
        }

        $query->orderBy('created_at', 'desc');
        
        $data = array(
            'usuarios' => $query->paginate($perPage)->withQueryString(),
            'perPage' => $perPage,
            'search' => $search
        );
        return view('users.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ValidarStoreUser $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Obtener auditorías del usuario con información del usuario que hizo el cambio
        $audits = $user->audits()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('users.show', compact('user', 'audits'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ValidarEditUser $request, User $user)
    {
        // Preparar datos para actualizar
        $data = $request->only(['name', 'email']);
        
        // Si se proporciona una nueva contraseña, la hasheamos
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        
        // Actualizar el usuario con los datos validados
        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, Request $request)
    {
        $request->validate([
            'motivo' => 'required|string|max:255'
        ], [
            'motivo.required' => 'El motivo de eliminación es obligatorio.',
            'motivo.max' => 'El motivo no puede exceder 255 caracteres.'
        ]);

        try {
            // Verificar que no sea el usuario autenticado
            if (Auth::id() === $user->id) {
                return redirect()->route('users.index')->with('error', 'No puedes eliminar tu propia cuenta.');
            }

            // Registrar el motivo en los metadatos de auditoría
            $user->auditComment = $request->motivo;
            $user->delete();
            
            return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', 'Error al eliminar el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Display audit history for a specific user.
     */
    public function auditHistory(User $user, Request $request)
    {
        $request->validate([
            'per_page' => 'nullable|integer|in:5,10,15,25,50',
            'event' => 'nullable|string|in:created,updated,deleted,restored'
        ]);

        $perPage = $request->get('per_page', 10);
        $eventFilter = $request->get('event');

        $query = $user->audits()->with('user');

        if ($eventFilter) {
            $query->where('event', $eventFilter);
        }

        $audits = $query->orderBy('created_at', 'desc')
                       ->paginate($perPage)
                       ->withQueryString();

        return view('users.audit-history', compact('user', 'audits', 'eventFilter', 'perPage'));
    }
    
    /**
     * Display a listing of deleted users.
     */
    public function deletedUsers(Request $request)
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
        
        $query = User::onlyTrashed()->select(['id', 'name', 'email', 'email_verified_at', 'created_at', 'updated_at', 'deleted_at']);
        
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('email', 'LIKE', '%' . $search . '%');
            });
        }

        $query->orderBy('deleted_at', 'desc');
        
        $data = array(
            'usuarios' => $query->paginate($perPage)->withQueryString(),
            'perPage' => $perPage,
            'search' => $search
        );
        return view('users.deleteUsers', $data);
    }

    /**
     * Restore a soft deleted user.
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
            $user = User::onlyTrashed()->findOrFail($id);
            
            // Verificar que no sea el usuario autenticado
            if (Auth::id() === $user->id) {
                return redirect()->route('users.deleted')->with('error', 'No puedes restaurar tu propia cuenta mientras estás autenticado.');
            }
            
            // Registrar el motivo en los metadatos de auditoría
            $user->auditComment = $request->motivo;
            $user->restore();
            
            return redirect()->route('users.deleted')->with('success', 'Usuario restaurado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('users.deleted')->with('error', 'Error al restaurar el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Permanently delete a user.
     */
    public function forceDelete($id)
    {
        try {
            $user = User::onlyTrashed()->findOrFail($id);
            
            // Verificar que no sea el usuario autenticado
            if (Auth::id() === $user->id) {
                return redirect()->route('users.deleted')->with('error', 'No puedes eliminar permanentemente tu propia cuenta.');
            }
            
            $user->forceDelete();
            
            return redirect()->route('users.deleted')->with('success', 'Usuario eliminado permanentemente.');
        } catch (\Exception $e) {
            return redirect()->route('users.deleted')->with('error', 'Error al eliminar permanentemente el usuario: ' . $e->getMessage());
        }
    }
}

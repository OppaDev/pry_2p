<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\ValidarEditUser;
use App\Http\Requests\ValidarStoreUser;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
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
        abort_unless(Auth::user()->can('usuarios.ver'), 403);
        
        $request->validate([
            'per_page' => 'nullable|integer|in:5,10,15,25,50',
            'search' => 'nullable|string|max:255',
            'role' => 'nullable|exists:roles,name'
        ], [
            'per_page.integer' => 'El valor debe ser un número entero.',
            'per_page.in' => 'El valor debe ser uno de los siguientes: 5, 10, 15, 25, 50.',
            'search.string' => 'El término de búsqueda debe ser una cadena de texto.',
            'search.max' => 'El término de búsqueda no puede tener más de 255 caracteres.'
        ]);
        
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search');
        
        $query = User::with('roles')
            ->select(['id', 'name', 'email', 'cedula', 'email_verified_at', 'created_at', 'updated_at']);
        
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('email', 'LIKE', '%' . $search . '%')
                  ->orWhere('cedula', 'LIKE', '%' . $search . '%');
            });
        }
        
        // Filtrar por rol
        if ($request->filled('role')) {
            $query->role($request->role);
        }

        $query->orderBy('created_at', 'desc');
        
        $roles = \Spatie\Permission\Models\Role::all();
        
        $data = array(
            'usuarios' => $query->paginate($perPage)->withQueryString(),
            'perPage' => $perPage,
            'search' => $search,
            'roles' => $roles
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
        try {
            DB::beginTransaction();
            
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'cedula' => $request->cedula,
                'password' => Hash::make($request->password),
            ]);

            DB::commit();
            
            Log::info('Usuario creado exitosamente', [
                'user_id' => $user->id,
                'created_by' => Auth::id()
            ]);
            
            return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear usuario: ' . $e->getMessage(), [
                'created_by' => Auth::id()
            ]);
            return redirect()->route('users.create')->with('error', 'Error al crear el usuario. Por favor, inténtalo de nuevo.');
        }
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
        try {
            DB::beginTransaction();
            
            // Preparar datos para actualizar
            $data = $request->only(['name', 'email', 'cedula']);
            
            // Si se proporciona una nueva contraseña, la hasheamos
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }
            
            // Actualizar el usuario con los datos validados
            $user->update($data);

            DB::commit();
            
            Log::info('Usuario actualizado exitosamente', [
                'user_id' => $user->id,
                'updated_by' => Auth::id()
            ]);
            
            return redirect()->route('users.index')->with('success', 'Usuario actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar usuario: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'updated_by' => Auth::id()
            ]);
            return redirect()->route('users.edit', $user)->with('error', 'Error al actualizar el usuario. Por favor, inténtalo de nuevo.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, Request $request)
    {
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
            // Verificar que no sea el usuario autenticado
            if (Auth::id() === $user->id) {
                return redirect()->route('users.index')->with('error', 'No puedes eliminar tu propia cuenta.');
            }

            DB::beginTransaction();
            
            // Registrar el motivo en los metadatos de auditoría
            $user->auditComment = $request->motivo;
            $user->delete();
            
            DB::commit();
            
            return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar usuario: ' . $e->getMessage());
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
            $user = User::onlyTrashed()->findOrFail($id);
            
            // Verificar que no sea el usuario autenticado
            if (Auth::id() === $user->id) {
                return redirect()->route('users.deleted')->with('error', 'No puedes restaurar tu propia cuenta mientras estás autenticado.');
            }
            
            DB::beginTransaction();
            
            // Registrar el motivo en los metadatos de auditoría
            $user->auditComment = $request->motivo;
            $user->restore();
            
            DB::commit();
            
            return redirect()->route('users.deleted')->with('success', 'Usuario restaurado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al restaurar usuario: ' . $e->getMessage());
            return redirect()->route('users.deleted')->with('error', 'Error al restaurar el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Permanently delete a user.
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
            $user = User::onlyTrashed()->findOrFail($id);
            
            // Verificar que no sea el usuario autenticado
            if (Auth::id() === $user->id) {
                return redirect()->route('users.deleted')->with('error', 'No puedes eliminar permanentemente tu propia cuenta.');
            }
            
            // Verificar contraseña del usuario logueado
            if (!Hash::check($request->password, Auth::user()->password)) {
                return redirect()->route('users.deleted')->with('error', 'Contraseña incorrecta. No se puede eliminar permanentemente.');
            }
            
            DB::beginTransaction();
            
            // Crear un registro de auditoría manual antes de la eliminación permanente
            \OwenIt\Auditing\Models\Audit::create([
                'user_type' => get_class(Auth::user()),
                'user_id' => Auth::id(),
                'event' => 'force_deleted',
                'auditable_type' => get_class($user),
                'auditable_id' => $user->id,
                'old_values' => $user->toArray(),
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
            
            Log::info('Usuario eliminado permanentemente', [
                'deleted_user_id' => $user->id,
                'deleted_user_email' => $user->email,
                'deleted_user_name' => $user->name,
                'admin_user_id' => Auth::id(),
                'admin_user_name' => Auth::user()->name,
                'motivo' => $request->motivo,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            $user->forceDelete();
            
            DB::commit();
            
            return redirect()->route('users.deleted')->with('success', 'Usuario eliminado permanentemente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar permanentemente usuario: ' . $e->getMessage(), [
                'user_id' => $id,
                'admin_user_id' => Auth::id(),
                'motivo' => $request->motivo ?? 'N/A'
            ]);
            
            return redirect()->route('users.deleted')->with('error', 'Error al eliminar permanentemente el usuario: ' . $e->getMessage());
        }
    }
    
    /**
     * Mostrar formulario para asignar roles a un usuario.
     */
    public function editRoles(User $user)
    {
        abort_unless(Auth::user()->can('usuarios.asignar_rol'), 403);
        
        $roles = \Spatie\Permission\Models\Role::all();
        $userRoles = $user->roles->pluck('name')->toArray();
        
        return view('users.edit-roles', compact('user', 'roles', 'userRoles'));
    }
    
    /**
     * Actualizar roles de un usuario.
     */
    public function updateRoles(Request $request, User $user)
    {
        abort_unless(Auth::user()->can('usuarios.asignar_rol'), 403);
        
        $request->validate([
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,name'
        ], [
            'roles.required' => 'Debe seleccionar al menos un rol.',
            'roles.array' => 'Los roles deben ser un arreglo.',
            'roles.min' => 'Debe seleccionar al menos un rol.',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Sincronizar roles (elimina los anteriores y asigna los nuevos)
            $user->syncRoles($request->roles);
            
            DB::commit();
            
            Log::info('Roles actualizados', [
                'user_id' => $user->id,
                'roles' => $request->roles,
                'admin_id' => Auth::id()
            ]);
            
            return redirect()
                ->route('users.show', $user)
                ->with('success', 'Roles actualizados exitosamente.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar roles', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()
                ->back()
                ->with('error', 'Error al actualizar los roles.');
        }
    }
    
    /**
     * Ver permisos de un usuario.
     */
    public function showPermissions(User $user)
    {
        abort_unless(Auth::user()->can('usuarios.ver'), 403);
        
        // Permisos directos
        $directPermissions = $user->permissions;
        
        // Permisos a través de roles
        $rolePermissions = $user->getPermissionsViaRoles();
        
        // Todos los permisos (combinados)
        $allPermissions = $user->getAllPermissions();
        
        return view('users.permissions', compact('user', 'directPermissions', 'rolePermissions', 'allPermissions'));
    }
}

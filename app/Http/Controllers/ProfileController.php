<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $roles = Role::all();
        $userRole = $user->getRoleNames()->first(); // Obtiene el primer rol del usuario
        $isAdmin = $user->hasRole('admin');

        return view('profile.edit', [
            'user' => $user,
            'roles' => $roles,
            'userRole' => $userRole,
            'isAdmin' => $isAdmin,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            
            $request->user()->fill($request->validated());

            if ($request->user()->isDirty('email')) {
                $request->user()->email_verified_at = null;
            }

            $request->user()->save();
            
            DB::commit();

            return Redirect::route('profile.edit')->with('status', 'profile-updated');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar perfil: ' . $e->getMessage());
            return Redirect::route('profile.edit')->with('error', 'Error al actualizar el perfil. Por favor, inténtalo de nuevo.');
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        try {
            DB::beginTransaction();
            
            $user = $request->user();

            Auth::logout();

            $user->delete();

            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            DB::commit();

            return Redirect::to('/');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar cuenta de usuario: ' . $e->getMessage());
            return Redirect::route('profile.edit')->with('error', 'Error al eliminar la cuenta. Por favor, inténtalo de nuevo.');
        }
    }

    /**
     * Update user role (only for admins).
     */
    public function updateRole(Request $request): RedirectResponse
    {
        // Verificar que el usuario actual sea administrador
        if (!$request->user()->hasRole('admin')) {
            return Redirect::route('profile.edit')->with('error', 'No tienes permisos para cambiar roles.');
        }

        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'role' => ['required', 'exists:roles,name'],
        ]);

        try {
            DB::beginTransaction();
            
            $targetUser = \App\Models\User::findOrFail($request->user_id);
            
            // Remover todos los roles actuales y asignar el nuevo
            $targetUser->syncRoles([$request->role]);
            
            DB::commit();

            return Redirect::route('profile.edit')->with('status', 'role-updated');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar rol: ' . $e->getMessage());
            return Redirect::route('profile.edit')->with('error', 'Error al actualizar el rol. Por favor, inténtalo de nuevo.');
        }
    }
}

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

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
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
}

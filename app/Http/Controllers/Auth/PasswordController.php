<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        try {
            DB::beginTransaction();
            
            $request->user()->update([
                'password' => Hash::make($validated['password']),
            ]);
            
            DB::commit();

            return back()->with('status', 'password-updated');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar contraseña: ' . $e->getMessage());
            return back()->with('error', 'Error al actualizar la contraseña. Por favor, inténtalo de nuevo.');
        }
    }
}

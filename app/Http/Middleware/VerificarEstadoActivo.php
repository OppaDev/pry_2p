<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerificarEstadoActivo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario está autenticado
        if (Auth::check()) {
            $user = Auth::user();

            // Verificar si el usuario está inactivo
            if ($user->estado !== 'activo') {
                // Obtener el motivo de inactivación
                $motivo = $user->motivo_inactivo ?? 'Sin motivo especificado';

                // Cerrar sesión del usuario
                Auth::logout();

                // Invalidar la sesión
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Redirigir al login con mensaje de error
                return redirect()->route('login')
                    ->withErrors(['email' => "Tu cuenta está desactivada. Motivo: {$motivo}"]);
            }
        }

        return $next($request);
    }
}

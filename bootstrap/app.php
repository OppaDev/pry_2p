<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\VerificarEstadoActivo;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'verificar.estado' => VerificarEstadoActivo::class,
        ]);

        // Aplicar el middleware globalmente a todas las rutas web autenticadas
        // $middleware->web([
        //     VerificarEstadoActivo::class,
        // ]);

        // $middleware->group('auth', [
        //     'auth',
        //     'verificar.estado',
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

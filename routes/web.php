<?php

use App\Http\Controllers\AsignaturaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\NotaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified', 'verificar.estado'])->name('dashboard');

//Verificar el estado de los usuarios
// Las rutas de autenticación están protegidas por el middleware VerificarEstadoActivo
Route::middleware(['auth', 'verificar.estado'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/role', [ProfileController::class, 'updateRole'])->name('profile.update-role');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas para auditorías generales
    Route::get('auditorias', [AuditController::class, 'auditsByUser'])->name('audits.by-user');
    Route::get('auditorias/{audit}', [AuditController::class, 'show'])->name('audits.show');

    // Rutas resource para usuarios
    Route::resource('users', UserController::class);

    // Rutas adicionales para auditoría de usuarios
    Route::get('users/{user}/audit-history', [UserController::class, 'auditHistory'])->name('users.audit-history');

    // Rutas adicionales para usuarios eliminados
    Route::get('usuarios-eliminados', [UserController::class, 'deletedUsers'])->name('users.deleted');
    Route::patch('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::delete('users/{id}/force-delete', [UserController::class, 'forceDelete'])->name('users.forceDelete');

    // Rutas para activar/desactivar usuarios
    Route::patch('users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
    Route::patch('users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');

    Route::resource('asignaturas', AsignaturaController::class)->middleware(['role:docente|admin']);
});

require __DIR__ . '/auth.php';

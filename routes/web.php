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

    // Rutas resource para productos
    Route::resource('productos', ProductoController::class);

    // Rutas adicionales para auditoría de productos
    Route::get('productos/{producto}/audit-history', [ProductoController::class, 'auditHistory'])->name('productos.audit-history');

    // Rutas adicionales para productos eliminados
    Route::get('productos-eliminados', [ProductoController::class, 'deletedProducts'])->name('productos.deleted');
    Route::patch('productos/{id}/restore', [ProductoController::class, 'restore'])->name('productos.restore');
    Route::delete('productos/{id}/force-delete', [ProductoController::class, 'forceDelete'])->name('productos.forceDelete');
    Route::post('asignaturas/{asignatura}/assign-users', [AsignaturaController::class, 'assignUsers'])->name('asignaturas.assign-users');

    Route::resource('asignaturas', AsignaturaController::class);

    // Rutas resource para notas
    Route::resource('notas', NotaController::class);
    
    // Rutas adicionales para auditoría de notas
    Route::get('notas/{nota}/audit-history', [NotaController::class, 'auditHistory'])->name('notas.audit-history');

});

require __DIR__ . '/auth.php';

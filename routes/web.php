<?php

use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuditController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas para auditorías generales
    Route::get('auditorias', [AuditController::class, 'auditsByUser'])->name('audits.by-user');
    Route::get('auditorias/{audit}', [AuditController::class, 'show'])->name('audits.show');

    // Rutas resource para usuarios (plural para consistencia con el layout)
    Route::resource('users', UserController::class);
    
    // Rutas adicionales para auditoría de usuarios
    Route::get('users/{user}/audit-history', [UserController::class, 'auditHistory'])->name('users.audit-history');
    
    // Rutas adicionales para usuarios eliminados
    Route::get('usuarios-eliminados', [UserController::class, 'deletedUsers'])->name('users.deleted');
    Route::patch('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::delete('users/{id}/force-delete', [UserController::class, 'forceDelete'])->name('users.forceDelete');
    
    // Rutas resource para productos (plural para consistencia con el layout)
    Route::resource('productos', ProductoController::class);
    
    // Rutas adicionales para auditoría de productos
    Route::get('productos/{producto}/audit-history', [ProductoController::class, 'auditHistory'])->name('productos.audit-history');
    
    // Rutas adicionales para productos eliminados
    Route::get('productos-eliminados', [ProductoController::class, 'deletedProducts'])->name('productos.deleted');
    Route::patch('productos/{id}/restore', [ProductoController::class, 'restore'])->name('productos.restore');
    Route::delete('productos/{id}/force-delete', [ProductoController::class, 'forceDelete'])->name('productos.forceDelete');
    
    
});

require __DIR__.'/auth.php';

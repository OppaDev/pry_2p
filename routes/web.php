<?php

use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CategoriaController;
use Illuminate\Support\Facades\Route;

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

    // Rutas resource para usuarios 
    Route::resource('users', UserController::class);
    
    // Rutas adicionales para auditoría de usuarios
    Route::get('users/{user}/audit-history', [UserController::class, 'auditHistory'])->name('users.audit-history');
    
    // Rutas adicionales para usuarios eliminados
    Route::get('usuarios-eliminados', [UserController::class, 'deletedUsers'])->name('users.deleted');
    Route::patch('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::delete('users/{id}/force-delete', [UserController::class, 'forceDelete'])->name('users.forceDelete');
    
    // Rutas adicionales para gestión de roles
    Route::get('users/{user}/roles', [UserController::class, 'editRoles'])->name('users.edit-roles');
    Route::patch('users/{user}/roles', [UserController::class, 'updateRoles'])->name('users.update-roles');
    Route::get('users/{user}/permissions', [UserController::class, 'showPermissions'])->name('users.show-permissions');
    
    // Rutas resource para productos 
    Route::resource('productos', ProductoController::class);
    
    // Rutas adicionales para auditoría de productos
    Route::get('productos/{producto}/audit-history', [ProductoController::class, 'auditHistory'])->name('productos.audit-history');
    
    // Rutas adicionales para productos eliminados
    Route::get('productos-eliminados', [ProductoController::class, 'deletedProducts'])->name('productos.deleted');
    Route::patch('productos/{id}/restore', [ProductoController::class, 'restore'])->name('productos.restore');
    Route::delete('productos/{id}/force-delete', [ProductoController::class, 'forceDelete'])->name('productos.forceDelete');
    
    // Rutas adicionales para inventario
    Route::get('productos/bajo-stock/lista', [ProductoController::class, 'bajosEnStock'])->name('productos.bajos-stock');
    Route::post('productos/{producto}/ajustar-stock', [ProductoController::class, 'ajustarStock'])->name('productos.ajustar-stock');
    Route::get('productos/{producto}/movimientos', [ProductoController::class, 'movimientos'])->name('productos.movimientos');
    Route::get('inventario/exportar', [ProductoController::class, 'exportarInventario'])->name('inventario.exportar');
    
    // Rutas resource para clientes
    Route::resource('clientes', ClienteController::class);
    
    // Rutas adicionales para clientes
    Route::get('clientes/{cliente}/audit-history', [ClienteController::class, 'auditHistory'])->name('clientes.audit-history');
    Route::get('clientes-eliminados', [ClienteController::class, 'deletedClientes'])->name('clientes.deleted');
    Route::patch('clientes/{id}/restore', [ClienteController::class, 'restore'])->name('clientes.restore');
    Route::post('clientes/verificar-edad', [ClienteController::class, 'verificarEdad'])->name('clientes.verificar-edad');
    
    // Rutas resource para categorías
    Route::resource('categorias', CategoriaController::class);
    
    // Rutas adicionales para categorías
    Route::patch('categorias/{id}/restore', [CategoriaController::class, 'restore'])->name('categorias.restore');
    
});

require __DIR__.'/auth.php';

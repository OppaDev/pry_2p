<?php

use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\VentaController;
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
    
    // Rutas para ventas (Punto de Venta)
    Route::resource('ventas', VentaController::class)->except(['edit', 'update', 'destroy']);
    Route::post('ventas/{venta}/anular', [VentaController::class, 'anular'])->name('ventas.anular');
    Route::post('ventas/{venta}/generar-factura', [VentaController::class, 'generarFactura'])->name('ventas.generar-factura');
    Route::get('api/productos/buscar', [VentaController::class, 'buscarProductos'])->name('api.productos.buscar');
    Route::post('api/productos/verificar-stock', [VentaController::class, 'verificarStock'])->name('api.productos.verificar-stock');
    
    // Rutas para reportes
    Route::prefix('reportes')->name('reportes.')->group(function () {
        Route::get('/', [ReporteController::class, 'index'])->name('index');
        Route::get('ventas', [ReporteController::class, 'ventas'])->name('ventas');
        Route::get('inventario', [ReporteController::class, 'inventario'])->name('inventario');
        Route::get('productos-mas-vendidos', [ReporteController::class, 'productosMasVendidos'])->name('productos-mas-vendidos');
        Route::get('movimientos-inventario', [ReporteController::class, 'movimientosInventario'])->name('movimientos-inventario');
        Route::get('clientes', [ReporteController::class, 'clientes'])->name('clientes');
        Route::get('ventas-por-vendedor', [ReporteController::class, 'ventasPorVendedor'])->name('ventas-por-vendedor');
        Route::get('bajo-stock', [ReporteController::class, 'bajoStock'])->name('bajo-stock');
    });
    
    // Rutas para facturación electrónica
    Route::prefix('facturas')->name('facturas.')->group(function () {
        Route::get('/', [FacturaController::class, 'index'])->name('index');
        Route::get('/{factura}', [FacturaController::class, 'show'])->name('show');
        Route::post('/crear', [FacturaController::class, 'crear'])->name('crear');
        Route::get('/{factura}/xml', [FacturaController::class, 'descargarXML'])->name('descargar-xml');
        Route::get('/{factura}/ride', [FacturaController::class, 'descargarRIDE'])->name('descargar-ride');
        Route::post('/{factura}/anular', [FacturaController::class, 'anular'])->name('anular');
        Route::post('/{factura}/reenviar-email', [FacturaController::class, 'reenviarEmail'])->name('reenviar-email');
        Route::post('/{factura}/enviar-sri', [FacturaController::class, 'enviarSRI'])->name('enviar-sri');
    });
    
});

require __DIR__.'/auth.php';

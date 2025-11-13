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
    Route::resource('users', UserController::class)->middleware('permission:usuarios.ver');
    
    // Rutas adicionales para auditoría de usuarios
    Route::get('users/{user}/audit-history', [UserController::class, 'auditHistory'])->name('users.audit-history')->middleware('permission:usuarios.ver');
    
    // Rutas adicionales para usuarios eliminados
    Route::get('usuarios-eliminados', [UserController::class, 'deletedUsers'])->name('users.deleted')->middleware('permission:usuarios.ver');
    Route::patch('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore')->middleware('permission:usuarios.restaurar');
    Route::delete('users/{id}/force-delete', [UserController::class, 'forceDelete'])->name('users.forceDelete')->middleware('permission:usuarios.eliminar');
    
    // Rutas adicionales para gestión de roles
    Route::get('users/{user}/roles', [UserController::class, 'editRoles'])->name('users.edit-roles')->middleware('permission:usuarios.asignar_roles');
    Route::patch('users/{user}/roles', [UserController::class, 'updateRoles'])->name('users.update-roles')->middleware('permission:usuarios.asignar_roles');
    Route::get('users/{user}/permissions', [UserController::class, 'showPermissions'])->name('users.show-permissions')->middleware('permission:usuarios.ver');
    
    // Rutas resource para productos 
    Route::resource('productos', ProductoController::class)->middleware('permission:productos.ver');
    
    // Rutas adicionales para auditoría de productos
    Route::get('productos/{producto}/audit-history', [ProductoController::class, 'auditHistory'])->name('productos.audit-history')->middleware('permission:productos.ver');
    
    // Rutas adicionales para productos eliminados
    Route::get('productos-eliminados', [ProductoController::class, 'deletedProducts'])->name('productos.deleted')->middleware('permission:productos.ver');
    Route::patch('productos/{id}/restore', [ProductoController::class, 'restore'])->name('productos.restore')->middleware('permission:productos.restaurar');
    Route::delete('productos/{id}/force-delete', [ProductoController::class, 'forceDelete'])->name('productos.forceDelete')->middleware('permission:productos.eliminar');
    
    // Rutas adicionales para inventario
    Route::get('productos/bajo-stock/lista', [ProductoController::class, 'bajosEnStock'])->name('productos.bajos-stock')->middleware('permission:inventario.ver');
    Route::post('productos/{producto}/ajustar-stock', [ProductoController::class, 'ajustarStock'])->name('productos.ajustar-stock')->middleware('permission:productos.ajustar_stock');
    Route::get('productos/{producto}/movimientos', [ProductoController::class, 'movimientos'])->name('productos.movimientos')->middleware('permission:inventario.ver');
    Route::get('inventario/exportar', [ProductoController::class, 'exportarInventario'])->name('inventario.exportar')->middleware('permission:reportes.exportar');
    
    // Rutas resource para clientes
    Route::resource('clientes', ClienteController::class)->middleware('permission:clientes.ver');
    
    // Rutas adicionales para clientes
    Route::get('clientes/{cliente}/audit-history', [ClienteController::class, 'auditHistory'])->name('clientes.audit-history')->middleware('permission:clientes.ver');
    Route::get('clientes-eliminados', [ClienteController::class, 'deletedClientes'])->name('clientes.deleted')->middleware('permission:clientes.ver');
    Route::patch('clientes/{id}/restore', [ClienteController::class, 'restore'])->name('clientes.restore')->middleware('permission:clientes.crear');
    Route::post('clientes/verificar-edad', [ClienteController::class, 'verificarEdad'])->name('clientes.verificar-edad')->middleware('permission:clientes.verificar_edad');
    
    // Rutas resource para categorías
    Route::resource('categorias', CategoriaController::class);
    
    // Rutas adicionales para categorías
    Route::patch('categorias/{id}/restore', [CategoriaController::class, 'restore'])->name('categorias.restore');
    
    // Rutas para ventas (Punto de Venta)
    Route::resource('ventas', VentaController::class)->except(['edit', 'update', 'destroy'])->middleware('permission:ventas.ver');
    Route::post('ventas/{venta}/anular', [VentaController::class, 'anular'])->name('ventas.anular')->middleware('permission:ventas.anular');
    Route::post('ventas/{venta}/generar-factura', [VentaController::class, 'generarFactura'])->name('ventas.generar-factura')->middleware('permission:facturas.crear');
    Route::get('api/productos/buscar', [VentaController::class, 'buscarProductos'])->name('api.productos.buscar')->middleware('permission:ventas.crear');
    Route::post('api/productos/verificar-stock', [VentaController::class, 'verificarStock'])->name('api.productos.verificar-stock')->middleware('permission:ventas.crear');
    
    // Rutas para reportes
    Route::prefix('reportes')->name('reportes.')->middleware('permission:reportes.ventas|reportes.inventario')->group(function () {
        Route::get('/', [ReporteController::class, 'index'])->name('index');
        Route::get('ventas', [ReporteController::class, 'ventas'])->name('ventas')->middleware('permission:reportes.ventas');
        Route::get('inventario', [ReporteController::class, 'inventario'])->name('inventario')->middleware('permission:reportes.inventario');
        Route::get('productos-mas-vendidos', [ReporteController::class, 'productosMasVendidos'])->name('productos-mas-vendidos')->middleware('permission:reportes.ventas');
        Route::get('movimientos-inventario', [ReporteController::class, 'movimientosInventario'])->name('movimientos-inventario')->middleware('permission:reportes.inventario');
        Route::get('clientes', [ReporteController::class, 'clientes'])->name('clientes')->middleware('permission:reportes.ventas');
        Route::get('ventas-por-vendedor', [ReporteController::class, 'ventasPorVendedor'])->name('ventas-por-vendedor')->middleware('permission:reportes.ventas');
        Route::get('bajo-stock', [ReporteController::class, 'bajoStock'])->name('bajo-stock')->middleware('permission:reportes.inventario');
    });
    
    // Rutas para facturación electrónica
    Route::prefix('facturas')->name('facturas.')->middleware('permission:facturas.ver')->group(function () {
        Route::get('/', [FacturaController::class, 'index'])->name('index');
        Route::get('/{factura}', [FacturaController::class, 'show'])->name('show');
        Route::post('/crear', [FacturaController::class, 'crear'])->name('crear')->middleware('permission:facturas.crear');
        Route::get('/{factura}/xml', [FacturaController::class, 'descargarXML'])->name('descargar-xml')->middleware('permission:facturas.descargar');
        Route::get('/{factura}/ride', [FacturaController::class, 'descargarRIDE'])->name('descargar-ride')->middleware('permission:facturas.descargar');
        Route::post('/{factura}/anular', [FacturaController::class, 'anular'])->name('anular')->middleware('permission:facturas.anular');
        Route::post('/{factura}/reenviar-email', [FacturaController::class, 'reenviarEmail'])->name('reenviar-email')->middleware('permission:facturas.enviar_email');
        Route::post('/{factura}/enviar-sri', [FacturaController::class, 'enviarSRI'])->name('enviar-sri')->middleware('permission:facturas.crear');
    });
    
});

require __DIR__.'/auth.php';

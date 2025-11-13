<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        // ==================== CREAR PERMISOS ====================
        
        // Permisos de Usuarios
        $usuariosPermisos = [
            'usuarios.ver',
            'usuarios.crear',
            'usuarios.editar',
            'usuarios.eliminar',
            'usuarios.restaurar',
            'usuarios.asignar_roles',
        ];
        
        // Permisos de Clientes
        $clientesPermisos = [
            'clientes.ver',
            'clientes.crear',
            'clientes.editar',
            'clientes.eliminar',
            'clientes.restaurar',
            'clientes.verificar_edad',
        ];
        
        // Permisos de Productos
        $productosPermisos = [
            'productos.ver',
            'productos.crear',
            'productos.editar',
            'productos.eliminar',
            'productos.restaurar',
            'productos.ver_stock',
            'productos.ajustar_stock',
        ];
        
        // Permisos de Inventario
        $inventarioPermisos = [
            'inventario.ver',
            'inventario.entrada',
            'inventario.salida',
            'inventario.ajuste',
            'inventario.reportes',
        ];
        
        // Permisos de Ventas
        $ventasPermisos = [
            'ventas.ver',
            'ventas.crear',
            'ventas.anular',
            'ventas.editar',
        ];
        
        // Permisos de Facturas
        $facturasPermisos = [
            'facturas.ver',
            'facturas.crear',
            'facturas.anular',
            'facturas.descargar',
            'facturas.enviar_email',
        ];
        
        // Permisos de Reportes
        $reportesPermisos = [
            'reportes.ventas',
            'reportes.inventario',
            'reportes.auditoria',
            'reportes.exportar',
        ];
        
        // Crear todos los permisos
        $todosPermisos = array_merge(
            $usuariosPermisos,
            $clientesPermisos,
            $productosPermisos,
            $inventarioPermisos,
            $ventasPermisos,
            $facturasPermisos,
            $reportesPermisos
        );
        
        foreach ($todosPermisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }
        
        // ==================== CREAR ROLES ====================
        
        // Rol: Administrador (tiene todos los permisos)
        $rolAdmin = Role::firstOrCreate(['name' => 'administrador']);
        $rolAdmin->syncPermissions(Permission::all());
        
        // Rol: Vendedor (solo clientes, ventas y facturas)
        $rolVendedor = Role::firstOrCreate(['name' => 'vendedor']);
        $rolVendedor->syncPermissions([
            // ===== CLIENTES (COMPLETO) =====
            'clientes.ver',
            'clientes.crear',
            'clientes.editar',
            'clientes.eliminar',
            'clientes.restaurar',
            'clientes.verificar_edad',
            
            // ===== VENTAS (COMPLETO) =====
            'ventas.ver',
            'ventas.crear',
            'ventas.editar',
            'ventas.anular',
            
            // ===== FACTURAS (COMPLETO) =====
            'facturas.ver',
            'facturas.crear',
            'facturas.anular',
            'facturas.descargar',
            'facturas.enviar_email',
            
            // ===== PRODUCTOS (SOLO VER - no crear/editar/eliminar) =====
            'productos.ver',
            'productos.ver_stock',
            
            // ===== REPORTES (solo relacionados con ventas/clientes) =====
            'reportes.ventas',
            'reportes.exportar',
        ]);
        
        // Rol: Jefe de Bodega (solo inventario y productos)
        $rolJefeBodega = Role::firstOrCreate(['name' => 'jefe_bodega']);
        $rolJefeBodega->syncPermissions([
            // ===== PRODUCTOS (COMPLETO) =====
            'productos.ver',
            'productos.crear',
            'productos.editar',
            'productos.eliminar',
            'productos.restaurar',
            'productos.ver_stock',
            'productos.ajustar_stock',
            
            // ===== INVENTARIO (COMPLETO) =====
            'inventario.ver',
            'inventario.entrada',
            'inventario.salida',
            'inventario.ajuste',
            'inventario.reportes',
            
            // ===== REPORTES (solo relacionados con inventario/productos) =====
            'reportes.inventario',
            'reportes.exportar',
        ]);
        
        $this->command->info('✅ Roles y permisos creados exitosamente!');
        $this->command->info('   - Administrador: ' . $rolAdmin->permissions->count() . ' permisos');
        $this->command->info('   - Vendedor: ' . $rolVendedor->permissions->count() . ' permisos');
        $this->command->info('   - Jefe de Bodega: ' . $rolJefeBodega->permissions->count() . ' permisos');
    }
}

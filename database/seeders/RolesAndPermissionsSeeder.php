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
            Permission::create(['name' => $permiso]);
        }
        
        // ==================== CREAR ROLES ====================
        
        // Rol: Administrador (tiene todos los permisos)
        $rolAdmin = Role::create(['name' => 'administrador']);
        $rolAdmin->givePermissionTo(Permission::all());
        
        // Rol: Vendedor
        $rolVendedor = Role::create(['name' => 'vendedor']);
        $rolVendedor->givePermissionTo([
            // Clientes
            'clientes.ver',
            'clientes.crear',
            'clientes.editar',
            'clientes.verificar_edad',
            // Ventas
            'ventas.ver',
            'ventas.crear',
            // Facturas
            'facturas.ver',
            'facturas.crear',
            'facturas.descargar',
            'facturas.enviar_email',
            // Productos (solo ver y stock)
            'productos.ver',
            'productos.ver_stock',
            // Reportes ventas
            'reportes.ventas',
        ]);
        
        // Rol: Jefe de Bodega
        $rolJefeBodega = Role::create(['name' => 'jefe_bodega']);
        $rolJefeBodega->givePermissionTo([
            // Productos
            'productos.ver',
            'productos.crear',
            'productos.editar',
            'productos.eliminar',
            'productos.restaurar',
            'productos.ver_stock',
            'productos.ajustar_stock',
            // Inventario (todos)
            'inventario.ver',
            'inventario.entrada',
            'inventario.salida',
            'inventario.ajuste',
            'inventario.reportes',
            // Reportes
            'reportes.inventario',
        ]);
        
        $this->command->info('✅ Roles y permisos creados exitosamente!');
        $this->command->info('   - Administrador: ' . $rolAdmin->permissions->count() . ' permisos');
        $this->command->info('   - Vendedor: ' . $rolVendedor->permissions->count() . ' permisos');
        $this->command->info('   - Jefe de Bodega: ' . $rolJefeBodega->permissions->count() . ' permisos');
    }
}

<?php

namespace App\Policies;

use App\Models\User;

class ReportePolicy
{
    /**
     * Determinar si el usuario puede ver reportes de ventas
     */
    public function verReportesVentas(User $user): bool
    {
        // Administrador y Vendedor pueden ver reportes de ventas
        return $user->hasAnyRole(['administrador', 'vendedor']) 
            || $user->can('reportes.ventas');
    }
    
    /**
     * Determinar si el usuario puede ver reportes de inventario
     */
    public function verReportesInventario(User $user): bool
    {
        // Administrador y Jefe de Bodega pueden ver reportes de inventario
        return $user->hasAnyRole(['administrador', 'jefe_bodega']) 
            || $user->can('reportes.inventario');
    }
    
    /**
     * Determinar si el usuario puede ver reportes de auditoría
     */
    public function verReportesAuditoria(User $user): bool
    {
        // Solo Administrador puede ver auditorías
        return $user->hasRole('administrador') 
            || $user->can('reportes.auditoria');
    }
    
    /**
     * Determinar si el usuario puede exportar reportes
     */
    public function exportarReportes(User $user): bool
    {
        return $user->can('reportes.exportar');
    }
}

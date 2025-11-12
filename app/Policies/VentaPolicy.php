<?php

namespace App\Policies;

use App\Models\Venta;
use App\Models\User;

class VentaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('ventas.ver');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Venta $venta): bool
    {
        return $user->can('ventas.ver');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('ventas.crear');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Venta $venta): bool
    {
        // Solo se puede editar si está en estado pendiente
        if ($venta->estado !== 'completada') {
            return false;
        }
        return $user->can('ventas.editar');
    }

    /**
     * Determine whether the user can anular the venta.
     */
    public function anular(User $user, Venta $venta): bool
    {
        // Solo se puede anular si está completada
        if ($venta->estado !== 'completada') {
            return false;
        }
        return $user->can('ventas.anular');
    }
    
    /**
     * Determine whether the user can emit factura.
     */
    public function emitirFactura(User $user): bool
    {
        return $user->can('ventas.emitir_factura');
    }
    
    /**
     * Determine whether the user can view their own ventas.
     */
    public function viewOwn(User $user, Venta $venta): bool
    {
        // Vendedores solo pueden ver sus propias ventas
        if ($user->hasRole('vendedor')) {
            return $venta->vendedor_id === $user->id;
        }
        
        return $user->can('ventas.ver');
    }
}

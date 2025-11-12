<?php

namespace App\Policies;

use App\Models\Producto;
use App\Models\User;

class ProductoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('productos.ver');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Producto $producto): bool
    {
        return $user->can('productos.ver');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('productos.crear');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Producto $producto): bool
    {
        return $user->can('productos.editar');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Producto $producto): bool
    {
        return $user->can('productos.eliminar');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Producto $producto): bool
    {
        return $user->can('productos.restaurar');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Producto $producto): bool
    {
        return $user->can('productos.eliminar') && $user->hasRole('administrador');
    }
    
    /**
     * Determine whether the user can adjust stock.
     */
    public function adjustStock(User $user): bool
    {
        return $user->can('productos.ajustar_stock');
    }
    
    /**
     * Determine whether the user can view stock.
     */
    public function viewStock(User $user): bool
    {
        return $user->can('productos.ver_stock');
    }
}

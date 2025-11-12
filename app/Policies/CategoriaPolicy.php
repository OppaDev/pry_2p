<?php

namespace App\Policies;

use App\Models\Categoria;
use App\Models\User;

class CategoriaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Las categorías pueden ser vistas por cualquiera que tenga acceso a productos o inventario
        return $user->can('productos.ver') || $user->can('inventario.ver');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Categoria $categoria): bool
    {
        return $user->can('productos.ver') || $user->can('inventario.ver');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('productos.crear') || $user->hasRole('jefe_bodega');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Categoria $categoria): bool
    {
        return $user->can('productos.editar') || $user->hasRole('jefe_bodega');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Categoria $categoria): bool
    {
        return $user->can('productos.eliminar') || $user->hasRole('administrador');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Categoria $categoria): bool
    {
        return $user->hasRole('administrador');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Categoria $categoria): bool
    {
        return $user->hasRole('administrador');
    }
}

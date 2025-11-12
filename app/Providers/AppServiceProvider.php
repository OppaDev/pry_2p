<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Cliente;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Venta;
use App\Policies\ClientePolicy;
use App\Policies\CategoriaPolicy;
use App\Policies\ProductoPolicy;
use App\Policies\VentaPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registrar políticas
        Gate::policy(Cliente::class, ClientePolicy::class);
        Gate::policy(Categoria::class, CategoriaPolicy::class);
        Gate::policy(Producto::class, ProductoPolicy::class);
        Gate::policy(Venta::class, VentaPolicy::class);
    }
}

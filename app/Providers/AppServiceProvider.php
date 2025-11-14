<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use App\Models\Cliente;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Venta;
use App\Policies\ClientePolicy;
use App\Policies\CategoriaPolicy;
use App\Policies\ProductoPolicy;
use App\Policies\VentaPolicy;
use App\Policies\ReportePolicy;

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
        // Forzar HTTPS en producción
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
        
        // Registrar políticas
        Gate::policy(Cliente::class, ClientePolicy::class);
        Gate::policy(Categoria::class, CategoriaPolicy::class);
        Gate::policy(Producto::class, ProductoPolicy::class);
        Gate::policy(Venta::class, VentaPolicy::class);
        
        // Registrar gates para reportes (sin modelo asociado)
        $reportePolicy = new ReportePolicy();
        Gate::define('verReportesVentas', fn($user) => $reportePolicy->verReportesVentas($user));
        Gate::define('verReportesInventario', fn($user) => $reportePolicy->verReportesInventario($user));
        Gate::define('verReportesAuditoria', fn($user) => $reportePolicy->verReportesAuditoria($user));
        Gate::define('exportarReportes', fn($user) => $reportePolicy->exportarReportes($user));
    }
}

@extends('layouts.app')

@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <!-- Header -->
    <div class="flex flex-wrap -mx-3">
        <div class="w-full max-w-full px-3 mb-6 flex items-center justify-between">
            <div>
                <h1 class="mb-0 text-2xl font-bold text-slate-700">¡Bienvenido, {{ Auth::user()->name }}! 👋</h1>
                <p class="text-sm text-slate-500">{{ \Carbon\Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
            </div>
            <div class="flex space-x-2">
                <button onclick="location.reload()" class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all shadow-sm hover:shadow-md">
                    <i class="fas fa-sync-alt mr-2"></i>Actualizar
                </button>
                <a href="{{ route('reportes.index') }}" class="px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                    <i class="fas fa-chart-bar mr-2"></i>Ver Reportes
                </a>
            </div>
        </div>
    </div>

    <!-- KPIs Principales - Hoy -->
    <div class="flex flex-wrap -mx-3 mb-6">
        <div class="w-full px-3 mb-4">
            <h6 class="text-lg font-semibold text-slate-600 mb-3">
                <i class="fas fa-calendar-day mr-2 text-blue-500"></i>Ventas de Hoy
            </h6>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="flex-auto p-4">
                    <div class="flex flex-row -mx-3">
                        <div class="flex-none w-2/3 max-w-full px-3">
                            <div>
                                <p class="mb-0 font-sans font-semibold leading-normal text-sm text-slate-500">Ventas Hoy</p>
                                <h5 class="mb-0 font-bold text-slate-700">
                                    {{ number_format($dashboard['ventas_hoy']['total'] ?? 0) }}
                                </h5>
                            </div>
                        </div>
                        <div class="px-3 text-right basis-1/3">
                            <div class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-purple-500 to-pink-500 shadow-soft-2xl">
                                <i class="fas fa-shopping-cart leading-none text-lg relative top-3.5 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="flex-auto p-4">
                    <div class="flex flex-row -mx-3">
                        <div class="flex-none w-2/3 max-w-full px-3">
                            <div>
                                <p class="mb-0 font-sans font-semibold leading-normal text-sm text-slate-500">Ingresos Hoy</p>
                                <h5 class="mb-0 font-bold text-slate-700">
                                    ${{ number_format($dashboard['ventas_hoy']['ingresos'] ?? 0, 2) }}
                                </h5>
                            </div>
                        </div>
                        <div class="px-3 text-right basis-1/3">
                            <div class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-green-500 to-lime-500 shadow-soft-2xl">
                                <i class="fas fa-dollar-sign leading-none text-lg relative top-3.5 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="flex-auto p-4">
                    <div class="flex flex-row -mx-3">
                        <div class="flex-none w-2/3 max-w-full px-3">
                            <div>
                                <p class="mb-0 font-sans font-semibold leading-normal text-sm text-slate-500">Bajo Stock</p>
                                <h5 class="mb-0 font-bold">
                                    <span class="{{ ($dashboard['productos_bajo_stock'] ?? 0) > 0 ? 'text-red-600' : 'text-green-600' }}">
                                        {{ number_format($dashboard['productos_bajo_stock'] ?? 0) }}
                                    </span>
                                </h5>
                            </div>
                        </div>
                        <div class="px-3 text-right basis-1/3">
                            <div class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-red-500 to-rose-500 shadow-soft-2xl">
                                <i class="fas fa-exclamation-triangle leading-none text-lg relative top-3.5 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:w-1/4">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-card">
                <div class="flex-auto p-4">
                    <div class="flex flex-row -mx-3">
                        <div class="flex-none w-2/3 max-w-full px-3">
                            <div>
                                <p class="mb-0 font-sans font-semibold leading-normal text-sm text-slate-500">Total Productos</p>
                                <h5 class="mb-0 font-bold text-slate-700">
                                    {{ number_format($dashboard['total_productos'] ?? 0) }}
                                </h5>
                            </div>
                        </div>
                        <div class="px-3 text-right basis-1/3">
                            <div class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-blue-500 to-cyan-500 shadow-soft-2xl">
                                <i class="fas fa-boxes leading-none text-lg relative top-3.5 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- KPIs del Mes -->
    <div class="flex flex-wrap -mx-3 mb-6">
        <div class="w-full px-3 mb-4">
            <h6 class="text-lg font-semibold text-slate-600 mb-3">
                <i class="fas fa-calendar-alt mr-2 text-purple-500"></i>Resumen del Mes
            </h6>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/3">
            <div class="relative flex flex-col min-w-0 break-words bg-gradient-to-r from-purple-500 to-pink-500 shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="flex-auto p-4">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-16 h-16 mr-4 text-white bg-white bg-opacity-20 rounded-xl">
                            <i class="fas fa-chart-line text-2xl"></i>
                        </div>
                        <div class="text-white">
                            <p class="mb-0 text-sm font-semibold opacity-90">Ventas del Mes</p>
                            <h4 class="mb-0 font-bold text-2xl">{{ number_format($dashboard['ventas_mes']['total'] ?? 0) }}</h4>
                            <p class="mb-0 text-xs opacity-80">Transacciones completadas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/3">
            <div class="relative flex flex-col min-w-0 break-words bg-gradient-to-r from-green-500 to-lime-500 shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="flex-auto p-4">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-16 h-16 mr-4 text-white bg-white bg-opacity-20 rounded-xl">
                            <i class="fas fa-money-bill-wave text-2xl"></i>
                        </div>
                        <div class="text-white">
                            <p class="mb-0 text-sm font-semibold opacity-90">Ingresos del Mes</p>
                            <h4 class="mb-0 font-bold text-2xl">${{ number_format($dashboard['ventas_mes']['ingresos'] ?? 0, 2) }}</h4>
                            <p class="mb-0 text-xs opacity-80">Total recaudado</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:w-1/3">
            <div class="relative flex flex-col min-w-0 break-words bg-gradient-to-r from-blue-500 to-cyan-500 shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="flex-auto p-4">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-16 h-16 mr-4 text-white bg-white bg-opacity-20 rounded-xl">
                            <i class="fas fa-users text-2xl"></i>
                        </div>
                        <div class="text-white">
                            <p class="mb-0 text-sm font-semibold opacity-90">Total Clientes</p>
                            <h4 class="mb-0 font-bold text-2xl">{{ number_format($dashboard['total_clientes'] ?? 0) }}</h4>
                            <p class="mb-0 text-xs opacity-80">Clientes registrados</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fila de Contenido Principal -->
    <div class="flex flex-wrap -mx-3">
        <!-- Top 5 Productos Más Vendidos -->
        <div class="w-full max-w-full px-3 mb-6 lg:w-1/2 lg:flex-none">
            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                    <h6 class="mb-0 text-lg font-semibold text-slate-700">
                        <i class="fas fa-star mr-2 text-yellow-500"></i>Top 5 Productos Más Vendidos
                    </h6>
                </div>
                <div class="flex-auto p-6">
                    @if(isset($dashboard['top_productos']) && count($dashboard['top_productos']) > 0)
                        <div class="space-y-4">
                            @foreach($dashboard['top_productos'] as $index => $producto)
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                                <div class="flex items-center space-x-3">
                                    @if($index == 0)
                                        <span class="flex items-center justify-center w-8 h-8 bg-gradient-to-tl from-yellow-400 to-yellow-500 text-white font-bold rounded-full text-sm">
                                            <i class="fas fa-trophy"></i>
                                        </span>
                                    @elseif($index == 1)
                                        <span class="flex items-center justify-center w-8 h-8 bg-gradient-to-tl from-slate-300 to-slate-400 text-white font-bold rounded-full text-sm">
                                            <i class="fas fa-medal"></i>
                                        </span>
                                    @elseif($index == 2)
                                        <span class="flex items-center justify-center w-8 h-8 bg-gradient-to-tl from-orange-400 to-orange-500 text-white font-bold rounded-full text-sm">
                                            <i class="fas fa-award"></i>
                                        </span>
                                    @else
                                        <span class="flex items-center justify-center w-8 h-8 bg-slate-200 text-slate-600 font-bold rounded-full text-sm">
                                            {{ $index + 1 }}
                                        </span>
                                    @endif
                                    <div>
                                        <p class="mb-0 font-semibold text-slate-700 text-sm">{{ $producto->nombre }}</p>
                                        <p class="mb-0 text-xs text-slate-500">{{ $producto->marca }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="mb-0 font-bold text-blue-600">{{ number_format($producto->total_vendido) }}</p>
                                    <p class="mb-0 text-xs text-slate-500">unidades</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-4 text-center">
                            <a href="{{ route('reportes.productos-mas-vendidos') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                Ver reporte completo <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    @else
                        <div class="text-center py-8 text-slate-400">
                            <i class="fas fa-inbox text-4xl mb-3"></i>
                            <p class="text-sm">No hay datos de productos vendidos</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Últimas 5 Ventas -->
        <div class="w-full max-w-full px-3 mb-6 lg:w-1/2 lg:flex-none">
            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                    <h6 class="mb-0 text-lg font-semibold text-slate-700">
                        <i class="fas fa-receipt mr-2 text-green-500"></i>Últimas Ventas
                    </h6>
                </div>
                <div class="flex-auto p-6">
                    @if(isset($dashboard['ultimas_ventas']) && count($dashboard['ultimas_ventas']) > 0)
                        <div class="space-y-3">
                            @foreach($dashboard['ultimas_ventas'] as $venta)
                            <div class="flex items-center justify-between p-3 border border-slate-200 rounded-lg hover:border-slate-300 transition-colors">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <p class="mb-0 font-semibold text-slate-700 text-sm">{{ $venta->numero_secuencial }}</p>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                            @if($venta->estado == 'completada') bg-green-100 text-green-700
                                            @elseif($venta->estado == 'pendiente') bg-yellow-100 text-yellow-700
                                            @else bg-red-100 text-red-700
                                            @endif">
                                            {{ ucfirst($venta->estado) }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between text-xs text-slate-500">
                                        <span>
                                            <i class="fas fa-user mr-1"></i>{{ $venta->cliente->nombre_completo ?? 'N/A' }}
                                        </span>
                                        <span>
                                            <i class="fas fa-clock mr-1"></i>{{ \Carbon\Carbon::parse($venta->fecha)->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-4 text-right">
                                    <p class="mb-0 font-bold text-green-600">${{ number_format($venta->total, 2) }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-4 text-center">
                            <a href="{{ route('reportes.ventas') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                Ver todas las ventas <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    @else
                        <div class="text-center py-8 text-slate-400">
                            <i class="fas fa-inbox text-4xl mb-3"></i>
                            <p class="text-sm">No hay ventas registradas</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas y Accesos Rápidos -->
    <div class="flex flex-wrap -mx-3">
        <!-- Alerta Bajo Stock -->
        @if(isset($dashboard['productos_bajo_stock']) && $dashboard['productos_bajo_stock'] > 0)
        <div class="w-full max-w-full px-3 mb-6 lg:w-1/2 lg:flex-none">
            <div class="relative flex flex-col min-w-0 break-words bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="flex-auto p-6">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-12 h-12 mr-4 bg-gradient-to-tl from-red-500 to-rose-500 rounded-xl shadow-soft-2xl">
                            <i class="fas fa-exclamation-triangle text-lg text-white animate-pulse"></i>
                        </div>
                        <div class="flex-1">
                            <h6 class="mb-1 font-bold text-red-700">¡Alerta de Stock!</h6>
                            <p class="mb-0 text-sm text-red-600">
                                Hay <strong>{{ $dashboard['productos_bajo_stock'] }}</strong> productos con stock bajo o agotado.
                            </p>
                        </div>
                        <a href="{{ route('reportes.bajo-stock') }}" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                            Ver Detalles
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Accesos Rápidos -->
        <div class="w-full max-w-full px-3 mb-6 {{ isset($dashboard['productos_bajo_stock']) && $dashboard['productos_bajo_stock'] > 0 ? 'lg:w-1/2' : '' }} lg:flex-none">
            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                    <h6 class="mb-3 text-lg font-semibold text-slate-700">
                        <i class="fas fa-rocket mr-2 text-blue-500"></i>Accesos Rápidos
                    </h6>
                </div>
                <div class="flex-auto p-6">
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('productos.index') }}" class="flex items-center p-3 bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-200 rounded-lg hover:from-blue-100 hover:to-cyan-100 transition-all group">
                            <div class="flex items-center justify-center w-10 h-10 mr-3 bg-gradient-to-tl from-blue-500 to-cyan-500 rounded-lg shadow-soft-sm group-hover:scale-110 transition-transform">
                                <i class="fas fa-boxes text-white"></i>
                            </div>
                            <div>
                                <p class="mb-0 text-xs font-semibold text-blue-700">Gestionar</p>
                                <p class="mb-0 text-sm font-bold text-blue-900">Productos</p>
                            </div>
                        </a>

                        <a href="{{ route('clientes.index') }}" class="flex items-center p-3 bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-200 rounded-lg hover:from-purple-100 hover:to-pink-100 transition-all group">
                            <div class="flex items-center justify-center w-10 h-10 mr-3 bg-gradient-to-tl from-purple-500 to-pink-500 rounded-lg shadow-soft-sm group-hover:scale-110 transition-transform">
                                <i class="fas fa-users text-white"></i>
                            </div>
                            <div>
                                <p class="mb-0 text-xs font-semibold text-purple-700">Gestionar</p>
                                <p class="mb-0 text-sm font-bold text-purple-900">Clientes</p>
                            </div>
                        </a>

                        <a href="{{ route('categorias.index') }}" class="flex items-center p-3 bg-gradient-to-r from-green-50 to-lime-50 border border-green-200 rounded-lg hover:from-green-100 hover:to-lime-100 transition-all group">
                            <div class="flex items-center justify-center w-10 h-10 mr-3 bg-gradient-to-tl from-green-500 to-lime-500 rounded-lg shadow-soft-sm group-hover:scale-110 transition-transform">
                                <i class="fas fa-tags text-white"></i>
                            </div>
                            <div>
                                <p class="mb-0 text-xs font-semibold text-green-700">Gestionar</p>
                                <p class="mb-0 text-sm font-bold text-green-900">Categorías</p>
                            </div>
                        </a>

                        <a href="{{ route('users.index') }}" class="flex items-center p-3 bg-gradient-to-r from-orange-50 to-yellow-50 border border-orange-200 rounded-lg hover:from-orange-100 hover:to-yellow-100 transition-all group">
                            <div class="flex items-center justify-center w-10 h-10 mr-3 bg-gradient-to-tl from-orange-500 to-yellow-500 rounded-lg shadow-soft-sm group-hover:scale-110 transition-transform">
                                <i class="fas fa-user-tie text-white"></i>
                            </div>
                            <div>
                                <p class="mb-0 text-xs font-semibold text-orange-700">Gestionar</p>
                                <p class="mb-0 text-sm font-bold text-orange-900">Usuarios</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

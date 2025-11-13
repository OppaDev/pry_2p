@extends('layouts.app')
@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="container-fluid py-4">
            <h1 class="mb-0 text-2xl font-semibold text-slate-700">REPORTES</h1>
            <p class="text-slate-500">Genera y exporta reportes del sistema en formato Excel/PDF</p>
        </div>

        <div class="flex flex-wrap -mx-3">
            <!-- Reporte de Ventas -->
            @can('verReportesVentas')
            <div class="w-full md:w-1/2 lg:w-1/3 px-3 mb-6">
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border hover:scale-102 transition-transform duration-200">
                    <div class="p-6">
                        <div class="flex items-center justify-center w-12 h-12 mb-4 text-center bg-gradient-to-tl from-purple-700 to-pink-500 rounded-xl shadow-soft-2xl">
                            <i class="fas fa-shopping-cart text-white text-xl"></i>
                        </div>
                        <h5 class="mb-2 text-lg font-semibold text-slate-700">Reporte de Ventas</h5>
                        <p class="mb-4 text-sm text-slate-500">Detalle completo de ventas por período, vendedor o cliente con totales e impuestos.</p>
                        <a href="{{ route('reportes.ventas') }}" 
                           class="inline-block px-6 py-2 text-sm font-bold text-center text-white uppercase bg-gradient-to-tl from-purple-700 to-pink-500 rounded-lg hover:shadow-soft-lg transition-all">
                            <i class="fas fa-file-alt mr-2"></i>Generar
                        </a>
                    </div>
                </div>
            </div>
            @endcan

            <!-- Reporte de Inventario -->
            @can('verReportesInventario')
            <div class="w-full md:w-1/2 lg:w-1/3 px-3 mb-6">
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border hover:scale-102 transition-transform duration-200">
                    <div class="p-6">
                        <div class="flex items-center justify-center w-12 h-12 mb-4 text-center bg-gradient-to-tl from-blue-600 to-cyan-400 rounded-xl shadow-soft-2xl">
                            <i class="fas fa-boxes text-white text-xl"></i>
                        </div>
                        <h5 class="mb-2 text-lg font-semibold text-slate-700">Reporte de Inventario</h5>
                        <p class="mb-4 text-sm text-slate-500">Estado actual del inventario, stock disponible, valor total y productos con bajo stock.</p>
                        <a href="{{ route('reportes.inventario') }}" 
                           class="inline-block px-6 py-2 text-sm font-bold text-center text-white uppercase bg-gradient-to-tl from-blue-600 to-cyan-400 rounded-lg hover:shadow-soft-lg transition-all">
                            <i class="fas fa-file-alt mr-2"></i>Generar
                        </a>
                    </div>
                </div>
            </div>
            @endcan

            <!-- Productos Más Vendidos -->
            @can('verReportesInventario')
            <div class="w-full md:w-1/2 lg:w-1/3 px-3 mb-6">
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border hover:scale-102 transition-transform duration-200">
                    <div class="p-6">
                        <div class="flex items-center justify-center w-12 h-12 mb-4 text-center bg-gradient-to-tl from-green-600 to-lime-400 rounded-xl shadow-soft-2xl">
                            <i class="fas fa-star text-white text-xl"></i>
                        </div>
                        <h5 class="mb-2 text-lg font-semibold text-slate-700">Productos Más Vendidos</h5>
                        <p class="mb-4 text-sm text-slate-500">Ranking de productos con mayor rotación, cantidad vendida e ingresos generados.</p>
                        <a href="{{ route('reportes.productos-mas-vendidos') }}" 
                           class="inline-block px-6 py-2 text-sm font-bold text-center text-white uppercase bg-gradient-to-tl from-green-600 to-lime-400 rounded-lg hover:shadow-soft-lg transition-all">
                            <i class="fas fa-file-alt mr-2"></i>Generar
                        </a>
                    </div>
                </div>
            </div>
            @endcan

            <!-- Movimientos de Inventario -->
            @can('verReportesInventario')
            <div class="w-full md:w-1/2 lg:w-1/3 px-3 mb-6">
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border hover:scale-102 transition-transform duration-200">
                    <div class="p-6">
                        <div class="flex items-center justify-center w-12 h-12 mb-4 text-center bg-gradient-to-tl from-orange-500 to-yellow-400 rounded-xl shadow-soft-2xl">
                            <i class="fas fa-exchange-alt text-white text-xl"></i>
                        </div>
                        <h5 class="mb-2 text-lg font-semibold text-slate-700">Movimientos de Inventario</h5>
                        <p class="mb-4 text-sm text-slate-500">Historial completo de entradas, salidas y ajustes de inventario con responsables.</p>
                        <a href="{{ route('reportes.movimientos-inventario') }}" 
                           class="inline-block px-6 py-2 text-sm font-bold text-center text-white uppercase bg-gradient-to-tl from-orange-500 to-yellow-400 rounded-lg hover:shadow-soft-lg transition-all">
                            <i class="fas fa-file-alt mr-2"></i>Generar
                        </a>
                    </div>
                </div>
            </div>
            @endcan

            <!-- Reporte de Clientes -->
            @can('verReportesVentas')
            <div class="w-full md:w-1/2 lg:w-1/3 px-3 mb-6">
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border hover:scale-102 transition-transform duration-200">
                    <div class="p-6">
                        <div class="flex items-center justify-center w-12 h-12 mb-4 text-center bg-gradient-to-tl from-indigo-600 to-purple-500 rounded-xl shadow-soft-2xl">
                            <i class="fas fa-users text-white text-xl"></i>
                        </div>
                        <h5 class="mb-2 text-lg font-semibold text-slate-700">Reporte de Clientes</h5>
                        <p class="mb-4 text-sm text-slate-500">Análisis de clientes, total de compras, promedio de gasto y clientes frecuentes.</p>
                        <a href="{{ route('reportes.clientes') }}" 
                           class="inline-block px-6 py-2 text-sm font-bold text-center text-white uppercase bg-gradient-to-tl from-indigo-600 to-purple-500 rounded-lg hover:shadow-soft-lg transition-all">
                            <i class="fas fa-file-alt mr-2"></i>Generar
                        </a>
                    </div>
                </div>
            </div>
            @endcan

            <!-- Ventas por Vendedor -->
            @can('verReportesVentas')
            <div class="w-full md:w-1/2 lg:w-1/3 px-3 mb-6">
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border hover:scale-102 transition-transform duration-200">
                    <div class="p-6">
                        <div class="flex items-center justify-center w-12 h-12 mb-4 text-center bg-gradient-to-tl from-red-600 to-rose-400 rounded-xl shadow-soft-2xl">
                            <i class="fas fa-user-tie text-white text-xl"></i>
                        </div>
                        <h5 class="mb-2 text-lg font-semibold text-slate-700">Ventas por Vendedor</h5>
                        <p class="mb-4 text-sm text-slate-500">Desempeño de vendedores, total de ventas, ingresos generados y promedios.</p>
                        <a href="{{ route('reportes.ventas-por-vendedor') }}" 
                           class="inline-block px-6 py-2 text-sm font-bold text-center text-white uppercase bg-gradient-to-tl from-red-600 to-rose-400 rounded-lg hover:shadow-soft-lg transition-all">
                            <i class="fas fa-file-alt mr-2"></i>Generar
                        </a>
                    </div>
                </div>
            </div>
            @endcan

            <!-- Productos con Bajo Stock -->
            @can('verReportesInventario')
            <div class="w-full md:w-1/2 lg:w-1/3 px-3 mb-6">
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border hover:scale-102 transition-transform duration-200">
                    <div class="p-6">
                        <div class="flex items-center justify-center w-12 h-12 mb-4 text-center bg-gradient-to-tl from-red-700 to-rose-500 rounded-xl shadow-soft-2xl">
                            <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                        </div>
                        <h5 class="mb-2 text-lg font-semibold text-slate-700">Productos con Bajo Stock</h5>
                        <p class="mb-4 text-sm text-slate-500">Listado de productos por debajo del stock mínimo que requieren reposición.</p>
                        <a href="{{ route('reportes.bajo-stock') }}" 
                           class="inline-block px-6 py-2 text-sm font-bold text-center text-white uppercase bg-gradient-to-tl from-red-700 to-rose-500 rounded-lg hover:shadow-soft-lg transition-all">
                            <i class="fas fa-file-alt mr-2"></i>Generar
                        </a>
                    </div>
                </div>
            </div>
            @endcan
        </div>

        <!-- Información Adicional -->
        <div class="flex flex-wrap -mx-3 mt-6">
            <div class="w-full px-3">
                <div class="relative flex flex-col min-w-0 break-words bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-200 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6">
                        <div class="flex items-start">
                            <div class="flex items-center justify-center w-12 h-12 mr-4 bg-blue-500 rounded-xl shadow-soft-md">
                                <i class="fas fa-info-circle text-white text-xl"></i>
                            </div>
                            <div>
                                <h6 class="mb-2 text-lg font-semibold text-blue-700">Formatos Disponibles</h6>
                                <p class="text-sm text-blue-600 mb-3">
                                    Todos los reportes pueden exportarse en los siguientes formatos:
                                </p>
                                <div class="flex flex-wrap gap-2">
                                    <span class="px-3 py-1 text-xs font-semibold bg-white text-blue-700 rounded-lg border border-blue-300">
                                        <i class="fas fa-file-excel mr-1"></i>Excel (.csv)
                                    </span>
                                    <span class="px-3 py-1 text-xs font-semibold bg-white text-blue-700 rounded-lg border border-blue-300">
                                        <i class="fas fa-file-pdf mr-1"></i>PDF (Vista imprimible)
                                    </span>
                                    <span class="px-3 py-1 text-xs font-semibold bg-white text-blue-700 rounded-lg border border-blue-300">
                                        <i class="fas fa-eye mr-1"></i>Vista Web
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

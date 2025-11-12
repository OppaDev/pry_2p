@extends('layouts.app')

@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <div class="container-fluid py-4">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="mb-1 text-2xl font-semibold text-slate-700">PRODUCTOS MÁS VENDIDOS</h1>
                <p class="text-sm text-slate-500">Ranking de productos por cantidad vendida</p>
            </div>
            <a href="{{ route('reportes.index') }}" class="px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Volver
            </a>
        </div>

        <!-- Formulario de Filtros -->
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6">
                <h6 class="mb-4 text-lg font-semibold text-slate-700">
                    <i class="fas fa-filter mr-2 text-blue-500"></i>Filtros
                </h6>
                <form method="GET" action="{{ route('reportes.productos-mas-vendidos') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Fecha Inicio -->
                    <div>
                        <label for="fecha_inicio" class="block text-sm font-medium text-slate-700 mb-2">
                            <i class="fas fa-calendar-alt mr-1 text-blue-500"></i>Fecha Inicio
                        </label>
                        <input type="date" id="fecha_inicio" name="fecha_inicio" 
                            value="{{ request('fecha_inicio') }}"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Fecha Fin -->
                    <div>
                        <label for="fecha_fin" class="block text-sm font-medium text-slate-700 mb-2">
                            <i class="fas fa-calendar-check mr-1 text-blue-500"></i>Fecha Fin
                        </label>
                        <input type="date" id="fecha_fin" name="fecha_fin" 
                            value="{{ request('fecha_fin') }}"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Categoría -->
                    <div>
                        <label for="categoria_id" class="block text-sm font-medium text-slate-700 mb-2">
                            <i class="fas fa-tags mr-1 text-purple-500"></i>Categoría
                        </label>
                        <select id="categoria_id" name="categoria_id"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">Todas las categorías</option>
                            @foreach($categorias ?? [] as $categoria)
                                <option value="{{ $categoria->id }}" {{ request('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Límite (Top N) -->
                    <div>
                        <label for="limite" class="block text-sm font-medium text-slate-700 mb-2">
                            <i class="fas fa-list-ol mr-1 text-green-500"></i>Mostrar Top
                        </label>
                        <select id="limite" name="limite"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="10" {{ request('limite', 10) == 10 ? 'selected' : '' }}>Top 10</option>
                            <option value="20" {{ request('limite') == 20 ? 'selected' : '' }}>Top 20</option>
                            <option value="50" {{ request('limite') == 50 ? 'selected' : '' }}>Top 50</option>
                            <option value="100" {{ request('limite') == 100 ? 'selected' : '' }}>Top 100</option>
                        </select>
                    </div>

                    <!-- Botones -->
                    <div class="flex items-end space-x-2 md:col-span-4">
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all shadow-sm hover:shadow-md">
                            <i class="fas fa-search mr-2"></i>Buscar
                        </button>
                        <a href="{{ route('reportes.productos-mas-vendidos') }}" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition-colors">
                            <i class="fas fa-times mr-2"></i>Limpiar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        @if(isset($datos))
        <!-- Botones de Exportación -->
        <div class="flex justify-end space-x-3 mb-4">
            <form method="GET" action="{{ route('reportes.productos-mas-vendidos') }}" class="inline">
                @foreach(request()->except('formato') as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                <input type="hidden" name="formato" value="excel">
                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all shadow-sm hover:shadow-md">
                    <i class="fas fa-file-excel mr-2"></i>Exportar Excel
                </button>
            </form>
            <form method="GET" action="{{ route('reportes.productos-mas-vendidos') }}" class="inline">
                @foreach(request()->except('formato') as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                <input type="hidden" name="formato" value="pdf">
                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all shadow-sm hover:shadow-md">
                    <i class="fas fa-file-pdf mr-2"></i>Exportar PDF
                </button>
            </form>
        </div>

        <!-- Tabla de Productos Más Vendidos -->
        <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                <h6 class="mb-4 text-lg font-semibold text-slate-700">
                    <i class="fas fa-trophy mr-2 text-yellow-500"></i>Ranking de Productos
                </h6>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-0 overflow-x-auto">
                    <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                        <thead class="align-bottom">
                            <tr>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Ranking</th>
                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Código</th>
                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Producto</th>
                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Categoría</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Total Vendido</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Total Ingresos</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Número Ventas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($datos['productos'] as $index => $producto)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-3 align-middle border-b text-center">
                                    @if($index == 0)
                                        <span class="inline-flex items-center justify-center w-8 h-8 bg-gradient-to-tl from-yellow-400 to-yellow-500 text-white font-bold rounded-full shadow-soft-lg">
                                            <i class="fas fa-trophy"></i>
                                        </span>
                                    @elseif($index == 1)
                                        <span class="inline-flex items-center justify-center w-8 h-8 bg-gradient-to-tl from-slate-300 to-slate-400 text-white font-bold rounded-full shadow-soft-lg">
                                            <i class="fas fa-medal"></i>
                                        </span>
                                    @elseif($index == 2)
                                        <span class="inline-flex items-center justify-center w-8 h-8 bg-gradient-to-tl from-orange-400 to-orange-500 text-white font-bold rounded-full shadow-soft-lg">
                                            <i class="fas fa-award"></i>
                                        </span>
                                    @else
                                        <span class="text-lg font-bold text-slate-600">{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 align-middle border-b whitespace-nowrap text-sm">
                                    <span class="font-semibold text-slate-700">{{ $producto->codigo }}</span>
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-sm">
                                    <div>
                                        <p class="mb-0 font-semibold text-slate-700">{{ $producto->nombre }}</p>
                                        <p class="mb-0 text-xs text-slate-500">{{ $producto->marca }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-sm">
                                    <span class="px-2 py-1 text-xs font-semibold bg-purple-100 text-purple-700 rounded-full">
                                        {{ $producto->categoria }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-center text-sm">
                                    <span class="font-bold text-blue-600 text-lg">{{ number_format($producto->total_vendido) }}</span>
                                    <span class="text-xs text-slate-500 block">unidades</span>
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-center text-sm">
                                    <span class="font-bold text-green-600 text-lg">${{ number_format($producto->total_ingresos, 2) }}</span>
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-center text-sm">
                                    <span class="px-3 py-1 text-sm font-semibold bg-slate-100 text-slate-700 rounded-lg">
                                        {{ number_format($producto->numero_ventas) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-slate-500">
                                    <i class="fas fa-chart-bar text-4xl mb-3 text-slate-300"></i>
                                    <p class="mb-0">No se encontraron productos vendidos con los filtros seleccionados</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <div class="container-fluid py-4">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="mb-1 text-2xl font-semibold text-slate-700">REPORTE DE INVENTARIO</h1>
                <p class="text-sm text-slate-500">Estado actual del stock de productos</p>
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
                <form method="GET" action="{{ route('reportes.inventario') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
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

                    <!-- Estado -->
                    <div>
                        <label for="estado" class="block text-sm font-medium text-slate-700 mb-2">
                            <i class="fas fa-toggle-on mr-1 text-green-500"></i>Estado
                        </label>
                        <select id="estado" name="estado"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Todos</option>
                            <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>

                    <!-- Botones -->
                    <div class="flex items-end space-x-2">
                        <label class="flex items-center px-4 py-2 bg-red-50 border border-red-200 rounded-lg cursor-pointer hover:bg-red-100 transition-colors">
                            <input type="checkbox" name="bajo_stock" value="1" {{ request('bajo_stock') ? 'checked' : '' }}
                                class="mr-2 w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500">
                            <i class="fas fa-exclamation-triangle mr-1 text-red-600"></i>
                            <span class="text-sm font-medium text-red-700">Solo Bajo Stock</span>
                        </label>
                        <button type="submit" class="flex-1 px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all shadow-sm hover:shadow-md">
                            <i class="fas fa-search mr-2"></i>Buscar
                        </button>
                        <a href="{{ route('reportes.inventario') }}" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition-colors">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        @if(isset($datos))
        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Total Productos -->
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-4">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-12 h-12 text-center bg-gradient-to-tl from-blue-500 to-cyan-500 rounded-xl shadow-soft-2xl">
                            <i class="fas fa-boxes text-lg text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="mb-0 text-sm font-semibold leading-normal text-slate-500">Total Productos</p>
                            <h5 class="mb-0 font-bold text-slate-700">{{ number_format($datos['estadisticas']['total_productos'] ?? 0) }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Productos Bajo Stock -->
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-4">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-12 h-12 text-center bg-gradient-to-tl from-red-500 to-rose-500 rounded-xl shadow-soft-2xl">
                            <i class="fas fa-exclamation-triangle text-lg text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="mb-0 text-sm font-semibold leading-normal text-slate-500">Bajo Stock</p>
                            <h5 class="mb-0 font-bold text-red-600">{{ number_format($datos['estadisticas']['productos_bajo_stock'] ?? 0) }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Valor Total Inventario -->
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-4">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-12 h-12 text-center bg-gradient-to-tl from-green-500 to-lime-500 rounded-xl shadow-soft-2xl">
                            <i class="fas fa-dollar-sign text-lg text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="mb-0 text-sm font-semibold leading-normal text-slate-500">Valor Total</p>
                            <h5 class="mb-0 font-bold text-slate-700">${{ number_format($datos['estadisticas']['valor_total_inventario'] ?? 0, 2) }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock Total -->
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-4">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-12 h-12 text-center bg-gradient-to-tl from-purple-500 to-pink-500 rounded-xl shadow-soft-2xl">
                            <i class="fas fa-cubes text-lg text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="mb-0 text-sm font-semibold leading-normal text-slate-500">Stock Total</p>
                            <h5 class="mb-0 font-bold text-slate-700">{{ number_format($datos['estadisticas']['stock_total'] ?? 0) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de Exportación -->
        <div class="flex justify-end space-x-3 mb-4">
            <form method="GET" action="{{ route('reportes.inventario') }}" class="inline">
                @foreach(request()->except('formato') as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                <input type="hidden" name="formato" value="excel">
                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all shadow-sm hover:shadow-md">
                    <i class="fas fa-file-excel mr-2"></i>Exportar Excel
                </button>
            </form>
            <form method="GET" action="{{ route('reportes.inventario') }}" class="inline">
                @foreach(request()->except('formato') as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                <input type="hidden" name="formato" value="pdf">
                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all shadow-sm hover:shadow-md">
                    <i class="fas fa-file-pdf mr-2"></i>Exportar PDF
                </button>
            </form>
        </div>

        <!-- Tabla de Inventario -->
        <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                <h6 class="mb-4 text-lg font-semibold text-slate-700">
                    <i class="fas fa-list mr-2 text-slate-600"></i>Detalle de Inventario
                </h6>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-0 overflow-x-auto">
                    <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                        <thead class="align-bottom">
                            <tr>
                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Código</th>
                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Producto</th>
                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Categoría</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Presentación</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Stock Actual</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Stock Mínimo</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Precio</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Valor Total</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($datos['productos'] as $producto)
                            <tr class="hover:bg-slate-50 transition-colors {{ $producto->stock_actual <= $producto->stock_minimo ? 'bg-red-50' : '' }}">
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
                                    <span class="text-slate-600">{{ $producto->categoria->nombre ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-center text-sm">
                                    <span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-700 rounded-full">
                                        {{ ucfirst($producto->presentacion) }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-center text-sm">
                                    <span class="font-bold {{ $producto->stock_actual <= $producto->stock_minimo ? 'text-red-600' : 'text-slate-700' }}">
                                        {{ number_format($producto->stock_actual) }}
                                    </span>
                                    @if($producto->stock_actual <= $producto->stock_minimo)
                                        <i class="fas fa-exclamation-triangle text-red-500 ml-1"></i>
                                    @endif
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-center text-sm">
                                    <span class="text-slate-600">{{ number_format($producto->stock_minimo) }}</span>
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-center text-sm">
                                    <span class="font-semibold text-slate-700">${{ number_format($producto->precio, 2) }}</span>
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-center text-sm">
                                    <span class="font-bold text-green-600">${{ number_format($producto->stock_actual * $producto->precio, 2) }}</span>
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-center text-sm">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $producto->estado == 'activo' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ ucfirst($producto->estado) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="px-6 py-8 text-center text-slate-500">
                                    <i class="fas fa-inbox text-4xl mb-3 text-slate-300"></i>
                                    <p class="mb-0">No se encontraron productos con los filtros seleccionados</p>
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

@extends('layouts.app')

@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <div class="container-fluid py-4">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="mb-1 text-2xl font-semibold text-slate-700">MOVIMIENTOS DE INVENTARIO</h1>
                <p class="text-sm text-slate-500">Historial completo de entrada/salida/ajustes</p>
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
                <form method="GET" action="{{ route('reportes.movimientos-inventario') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
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

                    <!-- Tipo de Movimiento -->
                    <div>
                        <label for="tipo" class="block text-sm font-medium text-slate-700 mb-2">
                            <i class="fas fa-exchange-alt mr-1 text-orange-500"></i>Tipo de Movimiento
                        </label>
                        <select id="tipo" name="tipo"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            <option value="">Todos</option>
                            <option value="ingreso" {{ request('tipo') == 'ingreso' ? 'selected' : '' }}>Ingreso</option>
                            <option value="salida" {{ request('tipo') == 'salida' ? 'selected' : '' }}>Salida</option>
                            <option value="ajuste" {{ request('tipo') == 'ajuste' ? 'selected' : '' }}>Ajuste</option>
                        </select>
                    </div>

                    <!-- Producto -->
                    <div>
                        <label for="producto_id" class="block text-sm font-medium text-slate-700 mb-2">
                            <i class="fas fa-box mr-1 text-purple-500"></i>Producto
                        </label>
                        <select id="producto_id" name="producto_id"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">Todos los productos</option>
                            @foreach($productos ?? [] as $producto)
                                <option value="{{ $producto->id }}" {{ request('producto_id') == $producto->id ? 'selected' : '' }}>
                                    {{ $producto->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Responsable -->
                    <div>
                        <label for="responsable_id" class="block text-sm font-medium text-slate-700 mb-2">
                            <i class="fas fa-user mr-1 text-green-500"></i>Responsable
                        </label>
                        <select id="responsable_id" name="responsable_id"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Todos</option>
                            @foreach($responsables ?? [] as $responsable)
                                <option value="{{ $responsable->id }}" {{ request('responsable_id') == $responsable->id ? 'selected' : '' }}>
                                    {{ $responsable->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Botones -->
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all shadow-sm hover:shadow-md">
                            <i class="fas fa-search mr-2"></i>Buscar
                        </button>
                        <a href="{{ route('reportes.movimientos-inventario') }}" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition-colors">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        @if(isset($datos))
        <!-- Estadísticas por Tipo -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <!-- Ingresos -->
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-4">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-12 h-12 text-center bg-gradient-to-tl from-green-500 to-lime-500 rounded-xl shadow-soft-2xl">
                            <i class="fas fa-arrow-down text-lg text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="mb-0 text-sm font-semibold leading-normal text-slate-500">Ingresos</p>
                            <h5 class="mb-0 font-bold text-green-600">{{ number_format($datos['estadisticas']['ingresos']['cantidad'] ?? 0) }}</h5>
                            <p class="mb-0 text-xs text-slate-400">{{ number_format($datos['estadisticas']['ingresos']['total_unidades'] ?? 0) }} unidades</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Salidas -->
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-4">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-12 h-12 text-center bg-gradient-to-tl from-red-500 to-rose-500 rounded-xl shadow-soft-2xl">
                            <i class="fas fa-arrow-up text-lg text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="mb-0 text-sm font-semibold leading-normal text-slate-500">Salidas</p>
                            <h5 class="mb-0 font-bold text-red-600">{{ number_format($datos['estadisticas']['salidas']['cantidad'] ?? 0) }}</h5>
                            <p class="mb-0 text-xs text-slate-400">{{ number_format($datos['estadisticas']['salidas']['total_unidades'] ?? 0) }} unidades</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ajustes -->
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-4">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-12 h-12 text-center bg-gradient-to-tl from-blue-500 to-cyan-500 rounded-xl shadow-soft-2xl">
                            <i class="fas fa-adjust text-lg text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="mb-0 text-sm font-semibold leading-normal text-slate-500">Ajustes</p>
                            <h5 class="mb-0 font-bold text-blue-600">{{ number_format($datos['estadisticas']['ajustes']['cantidad'] ?? 0) }}</h5>
                            <p class="mb-0 text-xs text-slate-400">{{ number_format(abs($datos['estadisticas']['ajustes']['total_unidades'] ?? 0)) }} unidades</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de Exportación -->
        <div class="flex justify-end space-x-3 mb-4">
            <form method="GET" action="{{ route('reportes.movimientos-inventario') }}" class="inline">
                @foreach(request()->except('formato') as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                <input type="hidden" name="formato" value="excel">
                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all shadow-sm hover:shadow-md">
                    <i class="fas fa-file-excel mr-2"></i>Exportar Excel
                </button>
            </form>
            <form method="GET" action="{{ route('reportes.movimientos-inventario') }}" class="inline">
                @foreach(request()->except('formato') as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                <input type="hidden" name="formato" value="pdf">
                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all shadow-sm hover:shadow-md">
                    <i class="fas fa-file-pdf mr-2"></i>Exportar PDF
                </button>
            </form>
        </div>

        <!-- Tabla de Movimientos -->
        <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                <h6 class="mb-4 text-lg font-semibold text-slate-700">
                    <i class="fas fa-list mr-2 text-slate-600"></i>Detalle de Movimientos
                </h6>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-0 overflow-x-auto">
                    <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                        <thead class="align-bottom">
                            <tr>
                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Fecha</th>
                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Producto</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Tipo</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Cantidad</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Stock Anterior</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Stock Nuevo</th>
                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Responsable</th>
                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Descripción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($datos['movimientos'] as $movimiento)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-3 align-middle border-b whitespace-nowrap text-sm">
                                    <span class="text-slate-600">{{ \Carbon\Carbon::parse($movimiento->created_at)->format('d/m/Y H:i') }}</span>
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-sm">
                                    <div>
                                        <p class="mb-0 font-semibold text-slate-700">{{ $movimiento->producto->nombre ?? 'N/A' }}</p>
                                        <p class="mb-0 text-xs text-slate-500">{{ $movimiento->producto->codigo ?? '' }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-center text-sm">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        @if($movimiento->tipo == 'ingreso') bg-green-100 text-green-700
                                        @elseif($movimiento->tipo == 'salida') bg-red-100 text-red-700
                                        @else bg-blue-100 text-blue-700
                                        @endif">
                                        {{ ucfirst($movimiento->tipo) }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-center text-sm">
                                    <span class="font-bold {{ $movimiento->cantidad > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $movimiento->cantidad > 0 ? '+' : '' }}{{ number_format($movimiento->cantidad) }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-center text-sm">
                                    <span class="text-slate-600">{{ number_format($movimiento->stock_anterior) }}</span>
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-center text-sm">
                                    <span class="font-semibold text-slate-700">{{ number_format($movimiento->stock_nuevo) }}</span>
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-sm">
                                    <span class="text-slate-600">{{ $movimiento->responsable->name ?? 'Sistema' }}</span>
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-sm">
                                    <span class="text-slate-500 text-xs">{{ $movimiento->descripcion ?? '-' }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-slate-500">
                                    <i class="fas fa-inbox text-4xl mb-3 text-slate-300"></i>
                                    <p class="mb-0">No se encontraron movimientos con los filtros seleccionados</p>
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

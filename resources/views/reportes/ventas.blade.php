@extends('layouts.app')

@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <div class="container-fluid py-4">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="mb-1 text-2xl font-semibold text-slate-700">REPORTE DE VENTAS</h1>
                <p class="text-sm text-slate-500">Análisis completo de ventas con filtros personalizados</p>
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
                <form method="GET" action="{{ route('reportes.ventas') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
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

                    <!-- Vendedor -->
                    <div>
                        <label for="vendedor_id" class="block text-sm font-medium text-slate-700 mb-2">
                            <i class="fas fa-user-tie mr-1 text-purple-500"></i>Vendedor
                        </label>
                        <select id="vendedor_id" name="vendedor_id"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">Todos los vendedores</option>
                            @foreach($vendedores ?? [] as $vendedor)
                                <option value="{{ $vendedor->id }}" {{ request('vendedor_id') == $vendedor->id ? 'selected' : '' }}>
                                    {{ $vendedor->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Cliente -->
                    <div>
                        <label for="cliente_id" class="block text-sm font-medium text-slate-700 mb-2">
                            <i class="fas fa-user mr-1 text-green-500"></i>Cliente
                        </label>
                        <select id="cliente_id" name="cliente_id"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Todos los clientes</option>
                            @foreach($clientes ?? [] as $cliente)
                                <option value="{{ $cliente->id }}" {{ request('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->nombre_completo }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Método de Pago -->
                    <div>
                        <label for="metodo_pago" class="block text-sm font-medium text-slate-700 mb-2">
                            <i class="fas fa-credit-card mr-1 text-indigo-500"></i>Método de Pago
                        </label>
                        <select id="metodo_pago" name="metodo_pago"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">Todos</option>
                            <option value="efectivo" {{ request('metodo_pago') == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                            <option value="tarjeta" {{ request('metodo_pago') == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                            <option value="transferencia" {{ request('metodo_pago') == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                        </select>
                    </div>

                    <!-- Botones -->
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all shadow-sm hover:shadow-md">
                            <i class="fas fa-search mr-2"></i>Buscar
                        </button>
                        <a href="{{ route('reportes.ventas') }}" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition-colors">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        @if(isset($datos))
        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Total Ventas -->
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-4">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-12 h-12 text-center bg-gradient-to-tl from-purple-500 to-pink-500 rounded-xl shadow-soft-2xl">
                            <i class="fas fa-shopping-cart text-lg text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="mb-0 text-sm font-semibold leading-normal text-slate-500">Total Ventas</p>
                            <h5 class="mb-0 font-bold text-slate-700">{{ number_format($datos['estadisticas']['total_ventas'] ?? 0) }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Ingresos -->
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-4">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-12 h-12 text-center bg-gradient-to-tl from-green-500 to-lime-500 rounded-xl shadow-soft-2xl">
                            <i class="fas fa-dollar-sign text-lg text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="mb-0 text-sm font-semibold leading-normal text-slate-500">Total Ingresos</p>
                            <h5 class="mb-0 font-bold text-slate-700">${{ number_format($datos['estadisticas']['total_ingresos'] ?? 0, 2) }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Promedio Venta -->
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-4">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-12 h-12 text-center bg-gradient-to-tl from-blue-500 to-cyan-500 rounded-xl shadow-soft-2xl">
                            <i class="fas fa-chart-line text-lg text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="mb-0 text-sm font-semibold leading-normal text-slate-500">Promedio Venta</p>
                            <h5 class="mb-0 font-bold text-slate-700">${{ number_format($datos['estadisticas']['promedio_venta'] ?? 0, 2) }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- IVA Recaudado -->
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-4">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-12 h-12 text-center bg-gradient-to-tl from-orange-500 to-yellow-500 rounded-xl shadow-soft-2xl">
                            <i class="fas fa-percentage text-lg text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="mb-0 text-sm font-semibold leading-normal text-slate-500">Total Impuestos</p>
                            <h5 class="mb-0 font-bold text-slate-700">${{ number_format($datos['estadisticas']['total_impuestos'] ?? 0, 2) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de Exportación -->
        <div class="flex justify-end space-x-3 mb-4">
            <form method="GET" action="{{ route('reportes.ventas') }}" class="inline">
                @foreach(request()->except('formato') as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                <input type="hidden" name="formato" value="excel">
                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all shadow-sm hover:shadow-md">
                    <i class="fas fa-file-excel mr-2"></i>Exportar Excel
                </button>
            </form>
            <form method="GET" action="{{ route('reportes.ventas') }}" class="inline">
                @foreach(request()->except('formato') as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                <input type="hidden" name="formato" value="pdf">
                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all shadow-sm hover:shadow-md">
                    <i class="fas fa-file-pdf mr-2"></i>Exportar PDF
                </button>
            </form>
        </div>

        <!-- Tabla de Ventas -->
        <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                <h6 class="mb-4 text-lg font-semibold text-slate-700">
                    <i class="fas fa-list mr-2 text-slate-600"></i>Detalle de Ventas
                </h6>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-0 overflow-x-auto">
                    <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                        <thead class="align-bottom">
                            <tr>
                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Número</th>
                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Fecha</th>
                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Cliente</th>
                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Vendedor</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Subtotal</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Impuestos</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Total</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Método Pago</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($datos['ventas'] as $venta)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-3 align-middle border-b whitespace-nowrap text-sm">
                                    <span class="font-semibold text-slate-700">{{ $venta->numero_secuencial }}</span>
                                </td>
                                <td class="px-6 py-3 align-middle border-b whitespace-nowrap text-sm">
                                    <span class="text-slate-600">{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y H:i') }}</span>
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-sm">
                                    <div>
                                        <p class="mb-0 font-semibold text-slate-700">{{ $venta->cliente->nombre_completo ?? 'N/A' }}</p>
                                        <p class="mb-0 text-xs text-slate-500">{{ $venta->cliente->identificacion ?? '' }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-sm">
                                    <span class="text-slate-600">{{ $venta->vendedor->name ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-center text-sm">
                                    <span class="font-semibold text-slate-700">${{ number_format($venta->subtotal, 2) }}</span>
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-center text-sm">
                                    <span class="text-slate-600">${{ number_format($venta->impuestos, 2) }}</span>
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-center text-sm">
                                    <span class="font-bold text-green-600">${{ number_format($venta->total, 2) }}</span>
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-center text-sm">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        @if($venta->metodo_pago == 'efectivo') bg-green-100 text-green-700
                                        @elseif($venta->metodo_pago == 'tarjeta') bg-blue-100 text-blue-700
                                        @else bg-purple-100 text-purple-700
                                        @endif">
                                        {{ ucfirst($venta->metodo_pago) }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-center text-sm">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        @if($venta->estado == 'completada') bg-green-100 text-green-700
                                        @elseif($venta->estado == 'pendiente') bg-yellow-100 text-yellow-700
                                        @else bg-red-100 text-red-700
                                        @endif">
                                        {{ ucfirst($venta->estado) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="px-6 py-8 text-center text-slate-500">
                                    <i class="fas fa-inbox text-4xl mb-3 text-slate-300"></i>
                                    <p class="mb-0">No se encontraron ventas con los filtros seleccionados</p>
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

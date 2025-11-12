@extends('layouts.app')

@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <div class="container-fluid py-4">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="mb-1 text-2xl font-semibold text-slate-700">REPORTE DE CLIENTES</h1>
                <p class="text-sm text-slate-500">Análisis de clientes y comportamiento de compra</p>
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
                <form method="GET" action="{{ route('reportes.clientes') }}" class="flex items-end space-x-4">
                    <!-- Estado -->
                    <div class="flex-1">
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
                    <div class="flex space-x-2">
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all shadow-sm hover:shadow-md">
                            <i class="fas fa-search mr-2"></i>Buscar
                        </button>
                        <a href="{{ route('reportes.clientes') }}" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition-colors">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        @if(isset($datos))
        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Total Clientes -->
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-4">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-12 h-12 text-center bg-gradient-to-tl from-purple-500 to-pink-500 rounded-xl shadow-soft-2xl">
                            <i class="fas fa-users text-lg text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="mb-0 text-sm font-semibold leading-normal text-slate-500">Total Clientes</p>
                            <h5 class="mb-0 font-bold text-slate-700">{{ number_format($datos['estadisticas']['total_clientes'] ?? 0) }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Clientes con Compras -->
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-4">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-12 h-12 text-center bg-gradient-to-tl from-green-500 to-lime-500 rounded-xl shadow-soft-2xl">
                            <i class="fas fa-shopping-bag text-lg text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="mb-0 text-sm font-semibold leading-normal text-slate-500">Con Compras</p>
                            <h5 class="mb-0 font-bold text-green-600">{{ number_format($datos['estadisticas']['clientes_con_compras'] ?? 0) }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Clientes sin Compras -->
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-4">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-12 h-12 text-center bg-gradient-to-tl from-orange-500 to-yellow-500 rounded-xl shadow-soft-2xl">
                            <i class="fas fa-user-times text-lg text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="mb-0 text-sm font-semibold leading-normal text-slate-500">Sin Compras</p>
                            <h5 class="mb-0 font-bold text-orange-600">{{ number_format($datos['estadisticas']['clientes_sin_compras'] ?? 0) }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Ventas Generadas -->
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-4">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-12 h-12 text-center bg-gradient-to-tl from-blue-500 to-cyan-500 rounded-xl shadow-soft-2xl">
                            <i class="fas fa-dollar-sign text-lg text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="mb-0 text-sm font-semibold leading-normal text-slate-500">Total Ventas</p>
                            <h5 class="mb-0 font-bold text-slate-700">${{ number_format($datos['estadisticas']['total_ventas_generadas'] ?? 0, 2) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de Exportación -->
        <div class="flex justify-end space-x-3 mb-4">
            <form method="GET" action="{{ route('reportes.clientes') }}" class="inline">
                @foreach(request()->except('formato') as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                <input type="hidden" name="formato" value="excel">
                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all shadow-sm hover:shadow-md">
                    <i class="fas fa-file-excel mr-2"></i>Exportar Excel
                </button>
            </form>
            <form method="GET" action="{{ route('reportes.clientes') }}" class="inline">
                @foreach(request()->except('formato') as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                <input type="hidden" name="formato" value="pdf">
                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all shadow-sm hover:shadow-md">
                    <i class="fas fa-file-pdf mr-2"></i>Exportar PDF
                </button>
            </form>
        </div>

        <!-- Tabla de Clientes -->
        <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                <h6 class="mb-4 text-lg font-semibold text-slate-700">
                    <i class="fas fa-list mr-2 text-slate-600"></i>Detalle de Clientes
                </h6>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-0 overflow-x-auto">
                    <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                        <thead class="align-bottom">
                            <tr>
                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Identificación</th>
                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Cliente</th>
                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Contacto</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Total Compras</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Total Gastado</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Promedio Compra</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($datos['clientes'] as $cliente)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-3 align-middle border-b whitespace-nowrap text-sm">
                                    <span class="font-semibold text-slate-700">{{ $cliente->identificacion }}</span>
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-sm">
                                    <div>
                                        <p class="mb-0 font-semibold text-slate-700">{{ $cliente->nombre_completo }}</p>
                                        <p class="mb-0 text-xs text-slate-500">Registrado: {{ \Carbon\Carbon::parse($cliente->created_at)->format('d/m/Y') }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-sm">
                                    <div>
                                        <p class="mb-0 text-slate-600 text-xs">
                                            <i class="fas fa-envelope mr-1 text-blue-500"></i>{{ $cliente->email }}
                                        </p>
                                        <p class="mb-0 text-slate-600 text-xs">
                                            <i class="fas fa-phone mr-1 text-green-500"></i>{{ $cliente->telefono ?? 'N/A' }}
                                        </p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-center text-sm">
                                    <span class="font-bold text-blue-600">{{ number_format($cliente->total_compras ?? 0) }}</span>
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-center text-sm">
                                    <span class="font-bold text-green-600">${{ number_format($cliente->total_gastado ?? 0, 2) }}</span>
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-center text-sm">
                                    <span class="font-semibold text-slate-700">${{ number_format($cliente->promedio_compra ?? 0, 2) }}</span>
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-center text-sm">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $cliente->estado == 'activo' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ ucfirst($cliente->estado) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-slate-500">
                                    <i class="fas fa-inbox text-4xl mb-3 text-slate-300"></i>
                                    <p class="mb-0">No se encontraron clientes con los filtros seleccionados</p>
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

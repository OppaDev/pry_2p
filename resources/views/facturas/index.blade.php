@extends('layouts.app')

@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="mb-1 text-2xl font-semibold text-slate-700">
                    <i class="fas fa-file-invoice mr-2 text-blue-500"></i>FACTURACIÓN ELECTRÓNICA
                </h1>
                <p class="text-sm text-slate-500">Sistema de facturación SRI Ecuador</p>
                @if(env('SRI_MODO_PRUEBA', true))
                    <span class="inline-flex items-center px-3 py-1 mt-2 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-full">
                        <i class="fas fa-exclamation-triangle mr-1"></i>MODO PRUEBA - No válido para SRI
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 mt-2 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                        <i class="fas fa-check-circle mr-1"></i>MODO PRODUCCIÓN
                    </span>
                @endif
            </div>
            <a href="{{ route('dashboard') }}" class="px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Dashboard
            </a>
        </div>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Total Facturas -->
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="flex-auto p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-soft-lg">
                                <i class="fas fa-file-invoice text-white text-lg"></i>
                            </div>
                        </div>
                        <div class="flex-1 ml-4">
                            <p class="mb-0 text-sm font-semibold text-slate-600">Total Facturas</p>
                            <h5 class="mb-0 text-xl font-bold text-slate-700">{{ number_format($estadisticas['total_facturas']) }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Autorizadas -->
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="flex-auto p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-soft-lg">
                                <i class="fas fa-check-circle text-white text-lg"></i>
                            </div>
                        </div>
                        <div class="flex-1 ml-4">
                            <p class="mb-0 text-sm font-semibold text-slate-600">Autorizadas</p>
                            <h5 class="mb-0 text-xl font-bold text-green-600">{{ number_format($estadisticas['facturas_autorizadas']) }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pendientes -->
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="flex-auto p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center shadow-soft-lg">
                                <i class="fas fa-clock text-white text-lg"></i>
                            </div>
                        </div>
                        <div class="flex-1 ml-4">
                            <p class="mb-0 text-sm font-semibold text-slate-600">Pendientes</p>
                            <h5 class="mb-0 text-xl font-bold text-yellow-600">{{ number_format($estadisticas['facturas_pendientes']) }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Facturado Mes -->
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="flex-auto p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-soft-lg">
                                <i class="fas fa-dollar-sign text-white text-lg"></i>
                            </div>
                        </div>
                        <div class="flex-1 ml-4">
                            <p class="mb-0 text-sm font-semibold text-slate-600">Facturado Mes</p>
                            <h5 class="mb-0 text-xl font-bold text-purple-600">${{ number_format($estadisticas['total_facturado_mes'], 2) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6">
                <h6 class="mb-4 text-lg font-semibold text-slate-700">
                    <i class="fas fa-filter mr-2 text-blue-500"></i>Filtros
                </h6>
                <form method="GET" action="{{ route('facturas.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Número Secuencial -->
                    <div>
                        <label for="numero_secuencial" class="block text-sm font-medium text-slate-700 mb-2">
                            <i class="fas fa-hashtag mr-1"></i>Número Secuencial
                        </label>
                        <input type="text" id="numero_secuencial" name="numero_secuencial" 
                            value="{{ request('numero_secuencial') }}"
                            placeholder="001-001-000000001"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Clave de Acceso -->
                    <div>
                        <label for="clave_acceso" class="block text-sm font-medium text-slate-700 mb-2">
                            <i class="fas fa-key mr-1"></i>Clave de Acceso
                        </label>
                        <input type="text" id="clave_acceso" name="clave_acceso" 
                            value="{{ request('clave_acceso') }}"
                            placeholder="Ingrese clave..."
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Estado -->
                    <div>
                        <label for="estado" class="block text-sm font-medium text-slate-700 mb-2">
                            <i class="fas fa-tag mr-1"></i>Estado
                        </label>
                        <select id="estado" name="estado"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Todos</option>
                            <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="autorizada" {{ request('estado') == 'autorizada' ? 'selected' : '' }}>Autorizada</option>
                            <option value="rechazada" {{ request('estado') == 'rechazada' ? 'selected' : '' }}>Rechazada</option>
                            <option value="anulada" {{ request('estado') == 'anulada' ? 'selected' : '' }}>Anulada</option>
                        </select>
                    </div>

                    <!-- Fecha Desde -->
                    <div>
                        <label for="fecha_desde" class="block text-sm font-medium text-slate-700 mb-2">
                            <i class="fas fa-calendar-alt mr-1"></i>Fecha Desde
                        </label>
                        <input type="date" id="fecha_desde" name="fecha_desde" 
                            value="{{ request('fecha_desde') }}"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Fecha Hasta -->
                    <div>
                        <label for="fecha_hasta" class="block text-sm font-medium text-slate-700 mb-2">
                            <i class="fas fa-calendar-check mr-1"></i>Fecha Hasta
                        </label>
                        <input type="date" id="fecha_hasta" name="fecha_hasta" 
                            value="{{ request('fecha_hasta') }}"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Botones -->
                    <div class="flex items-end space-x-2 lg:col-span-3">
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all shadow-sm hover:shadow-md">
                            <i class="fas fa-search mr-2"></i>Buscar
                        </button>
                        <a href="{{ route('facturas.index') }}" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition-colors">
                            <i class="fas fa-times mr-2"></i>Limpiar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla de Facturas -->
        <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6">
                <h6 class="mb-4 text-lg font-semibold text-slate-700">
                    <i class="fas fa-list mr-2 text-blue-500"></i>Listado de Facturas
                    <span class="text-sm font-normal text-slate-500">({{ $facturas->total() }} registros)</span>
                </h6>

                @if($facturas->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-slate-700">
                            <thead class="text-xs text-slate-700 uppercase bg-slate-50 border-b">
                                <tr>
                                    <th scope="col" class="px-4 py-3">Número</th>
                                    <th scope="col" class="px-4 py-3">Fecha Emisión</th>
                                    <th scope="col" class="px-4 py-3">Cliente</th>
                                    <th scope="col" class="px-4 py-3">Total</th>
                                    <th scope="col" class="px-4 py-3">Estado</th>
                                    <th scope="col" class="px-4 py-3 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($facturas as $factura)
                                    <tr class="bg-white border-b hover:bg-slate-50 transition-colors">
                                        <td class="px-4 py-3 font-medium">
                                            <div class="flex flex-col">
                                                <span class="text-slate-900">{{ $factura->numero_secuencial }}</span>
                                                <span class="text-xs text-slate-500">{{ substr($factura->clave_acceso_sri, 0, 20) }}...</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex flex-col">
                                                <span>{{ $factura->fecha_emision->format('d/m/Y') }}</span>
                                                <span class="text-xs text-slate-500">{{ $factura->fecha_emision->format('H:i') }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($factura->venta && $factura->venta->cliente)
                                                <div class="flex flex-col">
                                                    <span class="font-medium">{{ $factura->venta->cliente->nombre_completo }}</span>
                                                    <span class="text-xs text-slate-500">{{ $factura->venta->cliente->identificacion }}</span>
                                                </div>
                                            @else
                                                <span class="text-slate-400">Sin cliente</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex flex-col">
                                                <span class="font-bold text-green-600">${{ number_format($factura->total, 2) }}</span>
                                                <span class="text-xs text-slate-500">IVA: ${{ number_format($factura->impuestos, 2) }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($factura->estado_autorizacion === 'autorizada')
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                                                    <i class="fas fa-check-circle mr-1"></i>Autorizada
                                                </span>
                                            @elseif($factura->estado_autorizacion === 'pendiente')
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-full">
                                                    <i class="fas fa-clock mr-1"></i>Pendiente
                                                </span>
                                            @elseif($factura->estado_autorizacion === 'rechazada')
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full">
                                                    <i class="fas fa-times-circle mr-1"></i>Rechazada
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-slate-800 bg-slate-100 rounded-full">
                                                    <i class="fas fa-ban mr-1"></i>Anulada
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center justify-center space-x-2">
                                                <a href="{{ route('facturas.show', $factura) }}" 
                                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                                                    title="Ver detalle">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('facturas.descargar-xml', $factura) }}" 
                                                    class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors" 
                                                    title="Descargar XML">
                                                    <i class="fas fa-file-code"></i>
                                                </a>
                                                <a href="{{ route('facturas.descargar-ride', $factura) }}" 
                                                    class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition-colors" 
                                                    title="Descargar RIDE">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="mt-6">
                        {{ $facturas->links() }}
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-12">
                        <i class="fas fa-inbox text-6xl text-slate-300 mb-4"></i>
                        <p class="text-slate-500 text-lg">No hay facturas registradas</p>
                        <p class="text-slate-400 text-sm">Las facturas generadas aparecerán aquí</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

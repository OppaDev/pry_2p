@extends('layouts.app')

@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="mb-1 text-2xl font-semibold text-slate-700">
                    <i class="fas fa-receipt mr-2 text-blue-500"></i>VENTAS
                </h1>
                <p class="text-sm text-slate-500">Gestión de ventas</p>
            </div>
            <a href="{{ route('ventas.create') }}" class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-green-500 to-green-600 rounded-lg hover:from-green-600 hover:to-green-700 transition-all shadow-md hover:shadow-lg">
                <i class="fas fa-cash-register mr-2"></i>Nueva Venta
            </a>
        </div>

        <!-- KPIs -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="flex-auto p-4">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-green-500 to-green-600 shadow-soft-2xl">
                            <i class="fas fa-shopping-cart text-lg text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="mb-0 text-xs font-semibold text-slate-500 uppercase">Ventas Hoy</p>
                            <h5 class="mb-0 font-bold text-slate-700">{{ $ventasHoy }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="flex-auto p-4">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-blue-500 to-blue-600 shadow-soft-2xl">
                            <i class="fas fa-dollar-sign text-lg text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="mb-0 text-xs font-semibold text-slate-500 uppercase">Ingresos Hoy</p>
                            <h5 class="mb-0 font-bold text-slate-700">${{ number_format($ingresosHoy, 2) }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="flex-auto p-4">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-purple-500 to-purple-600 shadow-soft-2xl">
                            <i class="fas fa-calendar-alt text-lg text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="mb-0 text-xs font-semibold text-slate-500 uppercase">Ventas Mes</p>
                            <h5 class="mb-0 font-bold text-slate-700">{{ $ventasMes }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="flex-auto p-4">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-orange-500 to-orange-600 shadow-soft-2xl">
                            <i class="fas fa-chart-line text-lg text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="mb-0 text-xs font-semibold text-slate-500 uppercase">Ingresos Mes</p>
                            <h5 class="mb-0 font-bold text-slate-700">${{ number_format($ingresosMes, 2) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6">
                <form method="GET" action="{{ route('ventas.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-2">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}"
                                class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-2">Fecha Fin</label>
                            <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}"
                                class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-2">Estado</label>
                            <select name="estado" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Todos</option>
                                <option value="completada" {{ request('estado') == 'completada' ? 'selected' : '' }}>Completada</option>
                                <option value="anulada" {{ request('estado') == 'anulada' ? 'selected' : '' }}>Anulada</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-2">Vendedor</label>
                            <select name="vendedor_id" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Todos</option>
                                @foreach($vendedores as $vendedor)
                                    <option value="{{ $vendedor->id }}" {{ request('vendedor_id') == $vendedor->id ? 'selected' : '' }}>
                                        {{ $vendedor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-2">Número</label>
                            <input type="text" name="numero_secuencial" value="{{ request('numero_secuencial') }}" placeholder="000001"
                                class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit" class="flex-1 px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all">
                                <i class="fas fa-search mr-2"></i>Filtrar
                            </button>
                            <a href="{{ route('ventas.index') }}" class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 transition-colors">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla -->
        <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6">
                <div class="overflow-x-auto">
                    @if($ventas->count() > 0)
                        <table class="w-full text-sm text-left text-slate-700">
                            <thead class="text-xs text-slate-700 uppercase bg-slate-50">
                                <tr>
                                    <th class="px-4 py-3">Número</th>
                                    <th class="px-4 py-3">Fecha</th>
                                    <th class="px-4 py-3">Cliente</th>
                                    <th class="px-4 py-3">Vendedor</th>
                                    <th class="px-4 py-3">Método Pago</th>
                                    <th class="px-4 py-3 text-right">Total</th>
                                    <th class="px-4 py-3 text-center">Estado</th>
                                    <th class="px-4 py-3 text-center">Factura</th>
                                    <th class="px-4 py-3 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ventas as $venta)
                                <tr class="border-b hover:bg-slate-50">
                                    <td class="px-4 py-3 font-medium">{{ $venta->numero_secuencial }}</td>
                                    <td class="px-4 py-3">{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-col">
                                            <span class="font-medium">{{ $venta->cliente->nombre_completo }}</span>
                                            <span class="text-xs text-slate-500">{{ $venta->cliente->identificacion }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">{{ $venta->vendedor->name }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                                            {{ $venta->metodo_pago == 'efectivo' ? 'bg-green-100 text-green-700' : '' }}
                                            {{ $venta->metodo_pago == 'tarjeta' ? 'bg-blue-100 text-blue-700' : '' }}
                                            {{ $venta->metodo_pago == 'transferencia' ? 'bg-purple-100 text-purple-700' : '' }}">
                                            {{ ucfirst($venta->metodo_pago) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right font-semibold">${{ number_format($venta->total, 2) }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                                            {{ $venta->estado == 'completada' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ ucfirst($venta->estado) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($venta->factura)
                                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded-full">
                                                <i class="fas fa-file-invoice"></i> Sí
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-medium bg-slate-100 text-slate-500 rounded-full">
                                                No
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('ventas.show', $venta->id) }}" 
                                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                                                title="Ver detalle">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-4">
                            {{ $ventas->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-shopping-cart text-6xl text-slate-300 mb-4"></i>
                            <p class="text-slate-500 text-lg">No hay ventas registradas</p>
                            <a href="{{ route('ventas.create') }}" class="inline-block mt-4 px-6 py-3 text-white bg-gradient-to-r from-green-500 to-green-600 rounded-lg hover:from-green-600 hover:to-green-700 transition-all">
                                <i class="fas fa-plus mr-2"></i>Crear Primera Venta
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

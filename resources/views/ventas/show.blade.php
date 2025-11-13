@extends('layouts.app')

@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="mb-1 text-2xl font-semibold text-slate-700">
                    <i class="fas fa-receipt mr-2 text-blue-500"></i>VENTA #{{ $venta->numero_secuencial }}
                </h1>
                <p class="text-sm text-slate-500">Detalle de venta</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('ventas.index') }}" class="px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Volver
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna Izquierda: Información -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Estado y Fecha -->
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <span class="text-xs font-semibold text-slate-500 uppercase">Estado</span>
                                <div class="mt-1">
                                    <span class="px-3 py-1 text-sm font-medium rounded-full 
                                        {{ $venta->estado == 'completada' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        <i class="fas {{ $venta->estado == 'completada' ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                        {{ ucfirst($venta->estado) }}
                                    </span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-semibold text-slate-500 uppercase">Fecha</span>
                                <p class="mt-1 text-sm font-medium text-slate-700">{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-200">
                            <div>
                                <span class="text-xs font-semibold text-slate-500 uppercase">Método de Pago</span>
                                <p class="mt-1 text-sm font-medium text-slate-700">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                                        {{ $venta->metodo_pago == 'efectivo' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $venta->metodo_pago == 'tarjeta' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $venta->metodo_pago == 'transferencia' ? 'bg-purple-100 text-purple-700' : '' }}">
                                        {{ ucfirst($venta->metodo_pago) }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-slate-500 uppercase">Vendedor</span>
                                <p class="mt-1 text-sm font-medium text-slate-700">{{ $venta->vendedor->name }}</p>
                            </div>
                        </div>

                        @if($venta->observaciones)
                        <div class="mt-4 pt-4 border-t border-slate-200">
                            <span class="text-xs font-semibold text-slate-500 uppercase">Observaciones</span>
                            <p class="mt-1 text-sm text-slate-700">{{ $venta->observaciones }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Información Cliente -->
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6">
                        <h6 class="mb-4 text-lg font-semibold text-slate-700">
                            <i class="fas fa-user mr-2 text-purple-500"></i>Cliente
                        </h6>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-xs font-semibold text-slate-500 uppercase">Nombre Completo</span>
                                <p class="mt-1 text-sm font-medium text-slate-700">{{ $venta->cliente->nombre_completo }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-slate-500 uppercase">Identificación</span>
                                <p class="mt-1 text-sm font-medium text-slate-700">{{ $venta->cliente->identificacion }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-slate-500 uppercase">Email</span>
                                <p class="mt-1 text-sm text-slate-700">{{ $venta->cliente->email }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-slate-500 uppercase">Teléfono</span>
                                <p class="mt-1 text-sm text-slate-700">{{ $venta->cliente->telefono }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Productos -->
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6">
                        <h6 class="mb-4 text-lg font-semibold text-slate-700">
                            <i class="fas fa-box mr-2 text-blue-500"></i>Productos
                        </h6>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-slate-700">
                                <thead class="text-xs text-slate-700 uppercase bg-slate-50">
                                    <tr>
                                        <th class="px-4 py-3">Código</th>
                                        <th class="px-4 py-3">Producto</th>
                                        <th class="px-4 py-3 text-center">Cantidad</th>
                                        <th class="px-4 py-3 text-right">Precio Unit.</th>
                                        <th class="px-4 py-3 text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($venta->detalles as $detalle)
                                    <tr class="border-b">
                                        <td class="px-4 py-3 font-medium">{{ $detalle->producto->codigo }}</td>
                                        <td class="px-4 py-3">{{ $detalle->producto->nombre }}</td>
                                        <td class="px-4 py-3 text-center">{{ $detalle->cantidad }}</td>
                                        <td class="px-4 py-3 text-right">${{ number_format($detalle->precio_unitario, 2) }}</td>
                                        <td class="px-4 py-3 text-right font-semibold">${{ number_format($detalle->subtotal, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Factura Info (si existe) -->
                @if($venta->factura)
                <div class="relative flex flex-col min-w-0 break-words bg-gradient-to-br from-blue-500 to-blue-600 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 text-white">
                        <h6 class="mb-4 text-lg font-semibold">
                            <i class="fas fa-file-invoice mr-2"></i>Factura Electrónica
                        </h6>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-xs font-semibold uppercase opacity-80">Número</span>
                                <p class="mt-1 text-sm font-medium">{{ $venta->factura->numero_factura }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-semibold uppercase opacity-80">Estado</span>
                                <p class="mt-1">
                                    <span class="px-2 py-1 text-xs font-medium bg-white bg-opacity-20 rounded-full">
                                        {{ ucfirst($venta->factura->estado) }}
                                    </span>
                                </p>
                            </div>
                            <div class="col-span-2">
                                <span class="text-xs font-semibold uppercase opacity-80">Clave de Acceso</span>
                                <p class="mt-1 text-xs font-mono break-all">{{ $venta->factura->clave_acceso }}</p>
                            </div>
                            <div class="col-span-2 pt-4 border-t border-white border-opacity-20">
                                <a href="{{ route('facturas.show', $venta->factura->id) }}" 
                                    class="inline-block px-4 py-2 text-sm font-medium text-blue-600 bg-white rounded-lg hover:bg-opacity-90 transition-colors">
                                    <i class="fas fa-eye mr-2"></i>Ver Factura Completa
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Columna Derecha: Resumen y Acciones -->
            <div class="space-y-6">
                <!-- Resumen Totales -->
                <div class="relative flex flex-col min-w-0 break-words bg-gradient-to-br from-green-500 to-green-600 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 text-white">
                        <h6 class="mb-4 text-lg font-semibold">
                            <i class="fas fa-calculator mr-2"></i>Resumen
                        </h6>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center pb-2 border-b border-green-400">
                                <span>Subtotal:</span>
                                <span class="font-semibold">${{ number_format($venta->subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center pb-2 border-b border-green-400">
                                <span>IVA (15%):</span>
                                <span class="font-semibold">${{ number_format($venta->impuestos, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center text-xl">
                                <span class="font-bold">TOTAL:</span>
                                <span class="font-bold">${{ number_format($venta->total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acciones -->
                @if($venta->estado == 'completada')
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6">
                        <h6 class="mb-4 text-sm font-semibold text-slate-700 uppercase">Acciones</h6>
                        <div class="space-y-3">
                            @if(!$venta->factura)
                                <form action="{{ route('ventas.generar-factura', $venta->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                        class="w-full px-4 py-3 text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all shadow-md hover:shadow-lg">
                                        <i class="fas fa-file-invoice mr-2"></i>Generar Factura
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('facturas.show', $venta->factura->id) }}" 
                                    class="block w-full px-4 py-3 text-center text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all shadow-md hover:shadow-lg">
                                    <i class="fas fa-eye mr-2"></i>Ver Factura
                                </a>
                            @endif

                            <button type="button" onclick="document.getElementById('modal-anular').classList.remove('hidden')"
                                class="w-full px-4 py-3 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                                <i class="fas fa-times-circle mr-2"></i>Anular Venta
                            </button>
                        </div>
                    </div>
                </div>
                @elseif($venta->estado == 'anulada')
                <div class="relative flex flex-col min-w-0 break-words bg-red-50 border border-red-200 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6">
                        <h6 class="mb-2 text-sm font-semibold text-red-700 uppercase">
                            <i class="fas fa-exclamation-triangle mr-2"></i>Venta Anulada
                        </h6>
                        <p class="text-sm text-red-600">Esta venta ha sido anulada y no se puede modificar.</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Anular Venta -->
<div id="modal-anular" class="hidden fixed inset-0 bg-slate-900 bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-slate-700">
                    <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>Anular Venta
                </h3>
                <button onclick="document.getElementById('modal-anular').classList.add('hidden')" 
                    class="text-slate-400 hover:text-slate-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form action="{{ route('ventas.anular', $venta->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <p class="text-sm text-slate-600 mb-4">¿Está seguro que desea anular esta venta? Esta acción no se puede deshacer.</p>
                    
                    <label class="block text-sm font-medium text-slate-700 mb-2">Motivo de Anulación *</label>
                    <textarea name="motivo" rows="3" required
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                        placeholder="Ingrese el motivo de la anulación (mínimo 10 caracteres)..."></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('modal-anular').classList.add('hidden')"
                        class="flex-1 px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-times-circle mr-2"></i>Anular Venta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

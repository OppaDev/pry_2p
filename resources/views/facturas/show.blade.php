@extends('layouts.app')

@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="mb-1 text-2xl font-semibold text-slate-700">
                    <i class="fas fa-file-invoice mr-2 text-blue-500"></i>Factura {{ $factura->numero_secuencial }}
                </h1>
                <p class="text-sm text-slate-500">Detalle completo de la factura electrónica</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('facturas.index') }}" class="px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Volver
                </a>
                <a href="{{ route('facturas.descargar-xml', $factura) }}" class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-green-500 to-green-600 rounded-lg hover:from-green-600 hover:to-green-700 transition-all">
                    <i class="fas fa-file-code mr-2"></i>XML
                </a>
                <a href="{{ route('facturas.descargar-ride', $factura) }}" class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg hover:from-purple-600 hover:to-purple-700 transition-all">
                    <i class="fas fa-file-pdf mr-2"></i>RIDE
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Información Principal -->
            <div class="lg:col-span-2">
                <!-- Estado y Datos Principales -->
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h6 class="text-lg font-semibold text-slate-700">
                                <i class="fas fa-info-circle mr-2 text-blue-500"></i>Información de la Factura
                            </h6>
                            @if($factura->estado_autorizacion === 'autorizada')
                                <span class="inline-flex items-center px-3 py-1 text-sm font-medium text-green-800 bg-green-100 rounded-full">
                                    <i class="fas fa-check-circle mr-1"></i>Autorizada
                                </span>
                            @elseif($factura->estado_autorizacion === 'pendiente')
                                <span class="inline-flex items-center px-3 py-1 text-sm font-medium text-yellow-800 bg-yellow-100 rounded-full">
                                    <i class="fas fa-clock mr-1"></i>Pendiente
                                </span>
                            @elseif($factura->estado_autorizacion === 'rechazada')
                                <span class="inline-flex items-center px-3 py-1 text-sm font-medium text-red-800 bg-red-100 rounded-full">
                                    <i class="fas fa-times-circle mr-1"></i>Rechazada
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 text-sm font-medium text-slate-800 bg-slate-100 rounded-full">
                                    <i class="fas fa-ban mr-1"></i>Anulada
                                </span>
                            @endif
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-slate-500 mb-1">Número Secuencial</p>
                                <p class="text-base font-semibold text-slate-700">{{ $factura->numero_secuencial }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500 mb-1">Fecha Emisión</p>
                                <p class="text-base font-semibold text-slate-700">{{ $factura->fecha_emision->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-sm text-slate-500 mb-1">Clave de Acceso</p>
                                <p class="text-xs font-mono text-slate-700 bg-slate-50 p-2 rounded break-all">{{ $factura->clave_acceso_sri }}</p>
                            </div>
                            @if($factura->numero_autorizacion)
                                <div class="col-span-2">
                                    <p class="text-sm text-slate-500 mb-1">Número Autorización</p>
                                    <p class="text-xs font-mono text-green-700 bg-green-50 p-2 rounded break-all">{{ $factura->numero_autorizacion }}</p>
                                </div>
                            @endif
                            @if($factura->fecha_autorizacion)
                                <div>
                                    <p class="text-sm text-slate-500 mb-1">Fecha Autorización</p>
                                    <p class="text-base font-semibold text-green-600">{{ $factura->fecha_autorizacion->format('d/m/Y H:i') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Cliente -->
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6">
                        <h6 class="mb-4 text-lg font-semibold text-slate-700">
                            <i class="fas fa-user mr-2 text-purple-500"></i>Cliente
                        </h6>
                        @if($factura->venta && $factura->venta->cliente)
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-slate-500 mb-1">Nombre Completo</p>
                                    <p class="text-base font-semibold text-slate-700">{{ $factura->venta->cliente->nombre_completo }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-500 mb-1">Identificación</p>
                                    <p class="text-base font-semibold text-slate-700">{{ $factura->venta->cliente->identificacion }}</p>
                                </div>
                                @if($factura->venta->cliente->correo)
                                    <div>
                                        <p class="text-sm text-slate-500 mb-1">Email</p>
                                        <p class="text-base text-slate-700">{{ $factura->venta->cliente->correo }}</p>
                                    </div>
                                @endif
                                @if($factura->venta->cliente->telefono)
                                    <div>
                                        <p class="text-sm text-slate-500 mb-1">Teléfono</p>
                                        <p class="text-base text-slate-700">{{ $factura->venta->cliente->telefono }}</p>
                                    </div>
                                @endif
                                @if($factura->venta->cliente->direccion)
                                    <div class="col-span-2">
                                        <p class="text-sm text-slate-500 mb-1">Dirección</p>
                                        <p class="text-base text-slate-700">{{ $factura->venta->cliente->direccion }}</p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Detalle de Productos -->
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6">
                        <h6 class="mb-4 text-lg font-semibold text-slate-700">
                            <i class="fas fa-box mr-2 text-green-500"></i>Detalle de Productos
                        </h6>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-slate-700">
                                <thead class="text-xs text-slate-700 uppercase bg-slate-50">
                                    <tr>
                                        <th class="px-4 py-3">Producto</th>
                                        <th class="px-4 py-3 text-center">Cantidad</th>
                                        <th class="px-4 py-3 text-right">P. Unitario</th>
                                        <th class="px-4 py-3 text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($factura->venta->detalles as $detalle)
                                        <tr class="border-b hover:bg-slate-50">
                                            <td class="px-4 py-3">
                                                <div class="flex flex-col">
                                                    <span class="font-medium">{{ $detalle->producto->nombre }}</span>
                                                    <span class="text-xs text-slate-500">{{ $detalle->producto->codigo }}</span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-center font-medium">{{ $detalle->cantidad }}</td>
                                            <td class="px-4 py-3 text-right">${{ number_format($detalle->precio_unitario, 2) }}</td>
                                            <td class="px-4 py-3 text-right font-semibold">${{ number_format($detalle->precio_unitario * $detalle->cantidad, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumen y Acciones -->
            <div class="lg:col-span-1">
                <!-- Totales -->
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-gradient-to-br from-blue-500 to-blue-600 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 text-white">
                        <h6 class="mb-4 text-lg font-semibold">
                            <i class="fas fa-calculator mr-2"></i>Resumen
                        </h6>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center pb-2 border-b border-blue-400">
                                <span>Subtotal:</span>
                                <span class="font-semibold">${{ number_format($factura->subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center pb-2 border-b border-blue-400">
                                <span>IVA (15%):</span>
                                <span class="font-semibold">${{ number_format($factura->impuestos, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center text-xl">
                                <span class="font-bold">TOTAL:</span>
                                <span class="font-bold">${{ number_format($factura->total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6">
                        <h6 class="mb-4 text-lg font-semibold text-slate-700">
                            <i class="fas fa-cog mr-2 text-slate-500"></i>Acciones
                        </h6>
                        <div class="space-y-2">
                            <a href="{{ route('facturas.descargar-xml', $factura) }}" 
                                class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-green-500 to-green-600 rounded-lg hover:from-green-600 hover:to-green-700 transition-all">
                                <i class="fas fa-file-code mr-2"></i>Descargar XML
                            </a>
                            <a href="{{ route('facturas.descargar-ride', $factura) }}" 
                                class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg hover:from-purple-600 hover:to-purple-700 transition-all">
                                <i class="fas fa-file-pdf mr-2"></i>Descargar RIDE
                            </a>
                            
                            @if($factura->estado_autorizacion === 'pendiente')
                                <form action="{{ route('facturas.enviar-sri', $factura) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                        class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all">
                                        <i class="fas fa-paper-plane mr-2"></i>Enviar al SRI
                                    </button>
                                </form>
                            @endif

                            @if(!$factura->estaAnulada())
                                <form action="{{ route('facturas.anular', $factura) }}" method="POST" 
                                    onsubmit="return confirm('¿Está seguro de anular esta factura?')">
                                    @csrf
                                    <button type="submit" 
                                        class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-red-500 to-red-600 rounded-lg hover:from-red-600 hover:to-red-700 transition-all">
                                        <i class="fas fa-ban mr-2"></i>Anular Factura
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Info Modo -->
                <div class="relative flex flex-col min-w-0 break-words bg-yellow-50 border border-yellow-200 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-4">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-yellow-600 text-xl mr-3 mt-1"></i>
                            <div>
                                <p class="text-sm font-semibold text-yellow-800 mb-1">Modo de Operación</p>
                                <p class="text-xs text-yellow-700">
                                    @if(env('SRI_MODO_PRUEBA', true))
                                        Esta factura fue generada en <strong>MODO PRUEBA</strong>. No es válida para efectos tributarios ante el SRI.
                                    @else
                                        Esta factura fue generada en <strong>MODO PRODUCCIÓN</strong> y es válida ante el SRI.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

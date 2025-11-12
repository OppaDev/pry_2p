@extends('layouts.app')

@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <div class="flex flex-wrap -mx-3">
        <!-- Información del Cliente -->
        <div class="w-full max-w-full px-3 lg:w-2/3 lg:flex-none">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <div class="flex items-center justify-between">
                        <h6 class="font-bold text-xl">Información del Cliente</h6>
                        <div class="flex gap-2">
                            @can('clientes.editar')
                            <a href="{{ route('clientes.edit', $cliente) }}" class="inline-block px-4 py-2 font-bold text-center text-white uppercase align-middle transition-all bg-gradient-to-tl from-purple-700 to-pink-500 rounded-lg cursor-pointer leading-pro text-sm ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 hover:shadow-soft-xs active:opacity-85">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            @endcan
                            <a href="{{ route('clientes.index') }}" class="inline-block px-4 py-2 font-bold text-center text-slate-700 uppercase align-middle transition-all bg-white border border-slate-300 rounded-lg cursor-pointer leading-pro text-sm ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 hover:shadow-soft-xs active:opacity-85">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="flex-auto p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-slate-400 mb-1">Identificación</p>
                            <p class="text-lg font-semibold text-slate-700">{{ $cliente->tipo_identificacion }}: {{ $cliente->identificacion }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-slate-400 mb-1">Nombre Completo</p>
                            <p class="text-lg font-semibold text-slate-700">{{ $cliente->nombre_completo }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-slate-400 mb-1">Fecha de Nacimiento</p>
                            <p class="text-lg font-semibold text-slate-700">{{ \Carbon\Carbon::parse($cliente->fecha_nacimiento)->format('d/m/Y') }}</p>
                            <p class="text-sm {{ $cliente->es_mayor_edad ? 'text-green-600' : 'text-red-600' }} font-semibold">
                                {{ $cliente->edad }} años {{ $cliente->es_mayor_edad ? '✓ Mayor de edad' : '✗ Menor de edad' }}
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-slate-400 mb-1">Teléfono</p>
                            <p class="text-lg font-semibold text-slate-700">{{ $cliente->telefono }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-slate-400 mb-1">Correo Electrónico</p>
                            <p class="text-lg font-semibold text-slate-700">{{ $cliente->correo ?? 'No registrado' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-slate-400 mb-1">Estado</p>
                            <span class="bg-gradient-to-tl {{ $cliente->estado == 'activo' ? 'from-green-600 to-lime-400' : 'from-slate-600 to-slate-300' }} px-3.6 text-sm rounded-1.8 py-2.2 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">
                                {{ ucfirst($cliente->estado) }}
                            </span>
                        </div>
                        
                        <div class="md:col-span-2">
                            <p class="text-sm text-slate-400 mb-1">Dirección</p>
                            <p class="text-lg font-semibold text-slate-700">{{ $cliente->direccion }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Últimas Compras -->
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <h6 class="font-bold text-xl">Historial de Compras</h6>
                </div>
                
                <div class="flex-auto p-6">
                    @if($cliente->ventas->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                                <thead class="align-bottom">
                                    <tr>
                                        <th class="px-4 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 text-sm">Fecha</th>
                                        <th class="px-4 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 text-sm">Total</th>
                                        <th class="px-4 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 text-sm">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cliente->ventas as $venta)
                                    <tr>
                                        <td class="p-2 align-middle bg-transparent border-b">{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y H:i') }}</td>
                                        <td class="p-2 align-middle bg-transparent border-b font-semibold">${{ number_format($venta->total, 2) }}</td>
                                        <td class="p-2 align-middle bg-transparent border-b">
                                            <span class="px-2 py-1 text-xs font-semibold {{ $venta->estado == 'completada' ? 'text-green-600 bg-green-100' : 'text-red-600 bg-red-100' }} rounded">
                                                {{ ucfirst($venta->estado) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-slate-400">No hay compras registradas</p>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Estadísticas -->
        <div class="w-full max-w-full px-3 lg:w-1/3 lg:flex-none">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <h6 class="font-bold text-xl">Estadísticas</h6>
                </div>
                
                <div class="flex-auto p-6">
                    <div class="mb-6">
                        <div class="flex items-center mb-2">
                            <div class="flex items-center justify-center w-12 h-12 mr-4 text-white bg-gradient-to-tl from-blue-600 to-cyan-400 rounded-xl">
                                <i class="fas fa-shopping-cart text-lg"></i>
                            </div>
                            <div>
                                <p class="text-sm text-slate-400">Total de Compras</p>
                                <p class="text-2xl font-bold text-slate-700">{{ $estadisticas['total_compras'] }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <div class="flex items-center mb-2">
                            <div class="flex items-center justify-center w-12 h-12 mr-4 text-white bg-gradient-to-tl from-green-600 to-lime-400 rounded-xl">
                                <i class="fas fa-dollar-sign text-lg"></i>
                            </div>
                            <div>
                                <p class="text-sm text-slate-400">Monto Total</p>
                                <p class="text-2xl font-bold text-slate-700">${{ number_format($estadisticas['monto_total'], 2) }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <div class="flex items-center mb-2">
                            <div class="flex items-center justify-center w-12 h-12 mr-4 text-white bg-gradient-to-tl from-purple-700 to-pink-500 rounded-xl">
                                <i class="fas fa-calendar text-lg"></i>
                            </div>
                            <div>
                                <p class="text-sm text-slate-400">Última Compra</p>
                                <p class="text-lg font-bold text-slate-700">
                                    {{ $estadisticas['ultima_compra'] ? \Carbon\Carbon::parse($estadisticas['ultima_compra'])->format('d/m/Y') : 'Sin compras' }}
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

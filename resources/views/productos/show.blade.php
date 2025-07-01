@extends('layouts.app')
@section('content')
    <div class="w-full px-6 py-6 mx-auto text-lg">
        <div class="container-fluid py-4">
            <div class="flex items-center justify-between">
                <h1 class="mb-0 text-2xl font-semibold text-slate-700">DETALLE DEL PRODUCTO</h1>
                <div class="flex space-x-2">
                    <a href="{{ route('productos.audit-history', $producto->id) }}" 
                       class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg hover:from-purple-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-200 transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class="fas fa-history mr-2"></i>
                        Ver Historial Completo
                    </a>
                    <a href="{{ route('productos.index') }}" 
                       class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-slate-500 to-slate-600 rounded-lg hover:from-slate-600 hover:to-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-200 transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver
                    </a>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap -mx-3">
            <!-- Información del Producto -->
            <div class="w-full lg:w-1/3 px-3 mb-6">
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6">
                        <div class="text-center">
                            <div class="w-24 h-24 mx-auto bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-box text-3xl text-white"></i>
                            </div>
                            <h5 class="text-xl font-semibold text-slate-700">{{ $producto->nombre }}</h5>
                            <p class="text-slate-500 mb-4">Código: {{ $producto->codigo }}</p>
                            
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-blue-600">{{ $producto->cantidad }}</p>
                                    <p class="text-xs text-slate-500">Cantidad</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-green-600">${{ number_format($producto->precio, 2) }}</p>
                                    <p class="text-xs text-slate-500">Precio</p>
                                </div>
                            </div>
                            
                            <div class="text-sm text-slate-500 space-y-2">
                                <p><i class="fas fa-calendar-plus mr-2"></i>Creado: {{ $producto->created_at->format('d/m/Y H:i') }}</p>
                                <p><i class="fas fa-calendar-edit mr-2"></i>Actualizado: {{ $producto->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historial de Auditoría -->
            <div class="w-full lg:w-2/3 px-3">
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <div class="flex justify-between items-center">
                            <h6 class="mb-0 text-xl font-semibold text-slate-700">
                                <i class="fas fa-history mr-2 text-slate-700"></i>
                                HISTORIAL DE CAMBIOS
                            </h6>
                            <span class="px-3 py-1 text-sm font-semibold bg-slate-100 text-slate-600 rounded-full">
                                {{ $audits->total() }} registros
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-0 overflow-x-auto">
                            @if($audits->count() > 0)
                                <div class="space-y-4 p-6">
                                    @foreach($audits->take(5) as $audit)
                                        <div class="flex items-start space-x-4 p-4 bg-slate-50 rounded-lg">
                                            <div class="flex-shrink-0">
                                                @switch($audit->event)
                                                    @case('created')
                                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                            <i class="fas fa-plus text-green-600"></i>
                                                        </div>
                                                        @break
                                                    @case('updated')
                                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                            <i class="fas fa-edit text-blue-600"></i>
                                                        </div>
                                                        @break
                                                    @case('deleted')
                                                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                                            <i class="fas fa-trash text-red-600"></i>
                                                        </div>
                                                        @break
                                                    @case('restored')
                                                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                                            <i class="fas fa-undo text-yellow-600"></i>
                                                        </div>
                                                        @break
                                                @endswitch
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between">
                                                    <h4 class="text-sm font-semibold text-slate-700 capitalize">
                                                        {{ $audit->event == 'created' ? 'Creado' : 
                                                           ($audit->event == 'updated' ? 'Actualizado' : 
                                                           ($audit->event == 'deleted' ? 'Eliminado' : 'Restaurado')) }}
                                                    </h4>
                                                    <span class="text-xs text-slate-500">
                                                        {{ $audit->created_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                                <p class="text-sm text-slate-600 mb-2">
                                                    Por: {{ $audit->user ? $audit->user->name : 'Sistema' }}
                                                </p>
                                                @if($audit->new_values)
                                                    <div class="text-xs text-slate-500">
                                                        <strong>Cambios:</strong>
                                                        @foreach($audit->new_values as $key => $value)
                                                            <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded mr-1 mb-1">
                                                                {{ ucfirst($key) }}: {{ is_array($value) ? 'Array' : Str::limit($value, 30) }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="flex flex-col items-center py-8">
                                    <i class="fas fa-history text-4xl text-slate-300 mb-4"></i>
                                    <p class="text-slate-500 text-lg">No hay historial de cambios disponible</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

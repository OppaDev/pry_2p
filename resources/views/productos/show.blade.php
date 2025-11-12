@extends('layouts.app')
@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="container-fluid py-4">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="mb-0 text-2xl font-semibold text-slate-700">DETALLE DEL PRODUCTO</h1>
                    <p class="text-sm text-slate-400 mt-1">Información completa e historial del producto</p>
                </div>
                <div class="flex space-x-2">
                    @can('productos.editar')
                        <button onclick="openModal('ajustar-stock-modal')"
                            class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-cyan-400 rounded-lg hover:from-blue-700 hover:to-cyan-500 focus:outline-none focus:ring-2 focus:ring-blue-200 transition-all duration-200 shadow-sm hover:shadow-md">
                            <i class="fas fa-boxes mr-2"></i>
                            Ajustar Stock
                        </button>
                        <a href="{{ route('productos.edit', $producto->id) }}" 
                           class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-purple-700 to-pink-500 rounded-lg hover:from-purple-800 hover:to-pink-600 focus:outline-none focus:ring-2 focus:ring-purple-200 transition-all duration-200 shadow-sm hover:shadow-md">
                            <i class="fas fa-edit mr-2"></i>
                            Editar
                        </a>
                    @endcan
                    <a href="{{ route('productos.audit-history', $producto->id) }}" 
                       class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg hover:from-indigo-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-200 transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class="fas fa-history mr-2"></i>
                        Historial
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
            <!-- Columna Izquierda: Información Principal -->
            <div class="w-full lg:w-1/2 px-3 mb-6">
                <!-- Card: Información del Producto -->
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border mb-6">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <h6 class="mb-4 text-lg font-semibold text-slate-700">
                            <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                            Información del Producto
                        </h6>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase">Nombre</label>
                                <p class="text-sm font-semibold text-slate-700">{{ $producto->nombre }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase">Código</label>
                                <p class="text-sm font-semibold text-slate-700">{{ $producto->codigo }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase">Marca</label>
                                <p class="text-sm font-semibold text-slate-700">{{ $producto->marca }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase">Categoría</label>
                                @if($producto->categoria)
                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded-lg bg-gradient-to-r from-purple-100 to-pink-100 text-purple-700">
                                        {{ $producto->categoria->nombre }}
                                    </span>
                                @else
                                    <p class="text-sm text-slate-400">Sin categoría</p>
                                @endif
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase">Presentación</label>
                                <p class="text-sm font-semibold text-slate-700 capitalize">{{ $producto->presentacion }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase">Capacidad</label>
                                <p class="text-sm font-semibold text-slate-700">{{ $producto->capacidad }} ({{ $producto->volumen_ml }}ml)</p>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase">Precio Unitario</label>
                                <p class="text-lg font-bold text-green-600">${{ number_format($producto->precio, 2) }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase">Estado</label>
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-lg {{ $producto->estado == 'activo' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600' }}">
                                    {{ ucfirst($producto->estado) }}
                                </span>
                            </div>
                        </div>
                        
                        @if($producto->descripcion)
                            <div class="mt-4">
                                <label class="text-xs font-bold text-slate-400 uppercase">Descripción</label>
                                <p class="text-sm text-slate-600">{{ $producto->descripcion }}</p>
                            </div>
                        @endif
                        
                        <div class="grid grid-cols-2 gap-4 mt-4 pt-4 border-t border-gray-200">
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase">Creado</label>
                                <p class="text-xs text-slate-600">{{ $producto->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase">Actualizado</label>
                                <p class="text-xs text-slate-600">{{ $producto->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Card: Estado de Stock -->
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <h6 class="mb-4 text-lg font-semibold text-slate-700">
                            <i class="fas fa-warehouse mr-2 text-blue-500"></i>
                            Estado de Inventario
                        </h6>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-4 rounded-xl {{ $producto->estaEnBajoStock() ? 'bg-red-50' : 'bg-blue-50' }}">
                                <p class="text-xs font-bold text-slate-400 uppercase mb-2">Stock Actual</p>
                                <p class="text-3xl font-bold {{ $producto->estaEnBajoStock() ? 'text-red-600' : 'text-blue-600' }}">
                                    {{ number_format($producto->stock_actual) }}
                                </p>
                                @if($producto->estaEnBajoStock())
                                    <span class="inline-flex items-center px-2 py-1 mt-2 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Bajo Stock
                                    </span>
                                @endif
                            </div>
                            <div class="text-center p-4 rounded-xl bg-gray-50">
                                <p class="text-xs font-bold text-slate-400 uppercase mb-2">Stock Mínimo</p>
                                <p class="text-3xl font-bold text-slate-600">{{ number_format($producto->stock_minimo) }}</p>
                            </div>
                        </div>
                        
                        @if($producto->estaEnBajoStock())
                            <div class="mt-4 p-3 rounded-lg bg-red-50 border border-red-200">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-red-800">¡Alerta de Stock!</p>
                                        <p class="text-xs text-red-600">
                                            El stock actual está {{ $producto->stock_actual < $producto->stock_minimo ? 'por debajo' : 'en' }} el nivel mínimo.
                                            Se necesitan {{ max(0, $producto->stock_minimo - $producto->stock_actual) }} unidades para alcanzar el mínimo.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="mt-4 p-3 rounded-lg bg-green-50 border border-green-200">
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <p class="text-sm text-green-700">Stock en nivel óptimo</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Historial de Auditoría -->
            <div class="w-full lg:w-1/2 px-3">
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <div class="flex justify-between items-center">
                            <h6 class="mb-0 text-lg font-semibold text-slate-700">
                                <i class="fas fa-history mr-2 text-indigo-500"></i>
                                Historial de Cambios Recientes
                            </h6>
                            <span class="px-3 py-1 text-xs font-semibold bg-slate-100 text-slate-600 rounded-full">
                                {{ $audits->total() }} total
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-0 overflow-x-auto">
                            @if($audits->count() > 0)
                                <div class="space-y-3 p-6">
                                    @foreach($audits->take(8) as $audit)
                                        <div class="flex items-start space-x-3 p-3 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                                            <div class="flex-shrink-0">
                                                @switch($audit->event)
                                                    @case('created')
                                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                            <i class="fas fa-plus text-green-600 text-sm"></i>
                                                        </div>
                                                        @break
                                                    @case('updated')
                                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                            <i class="fas fa-edit text-blue-600 text-sm"></i>
                                                        </div>
                                                        @break
                                                    @case('deleted')
                                                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                                            <i class="fas fa-trash text-red-600 text-sm"></i>
                                                        </div>
                                                        @break
                                                    @case('restored')
                                                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                                            <i class="fas fa-undo text-yellow-600 text-sm"></i>
                                                        </div>
                                                        @break
                                                @endswitch
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between mb-1">
                                                    <h4 class="text-sm font-semibold text-slate-700 capitalize">
                                                        {{ $audit->event == 'created' ? 'Creado' : 
                                                           ($audit->event == 'updated' ? 'Actualizado' : 
                                                           ($audit->event == 'deleted' ? 'Eliminado' : 'Restaurado')) }}
                                                    </h4>
                                                    <span class="text-xs text-slate-400">
                                                        {{ $audit->created_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                                <p class="text-xs text-slate-500 mb-1">
                                                    Por: {{ $audit->user ? $audit->user->name : 'Sistema' }}
                                                </p>
                                                @if($audit->new_values && count($audit->new_values) > 0)
                                                    <div class="text-xs text-slate-500">
                                                        <span class="font-semibold">Cambios:</span>
                                                        <div class="flex flex-wrap gap-1 mt-1">
                                                            @foreach($audit->new_values as $key => $value)
                                                                <span class="inline-block bg-blue-100 text-blue-800 px-2 py-0.5 rounded text-xs">
                                                                    {{ ucfirst($key) }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                @if($audits->total() > 8)
                                    <div class="px-6 pb-6">
                                        <a href="{{ route('productos.audit-history', $producto->id) }}"
                                            class="block w-full text-center px-4 py-2 text-sm font-semibold text-purple-700 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                                            Ver todos los {{ $audits->total() }} registros
                                            <i class="fas fa-arrow-right ml-2"></i>
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="flex flex-col items-center py-8">
                                    <i class="fas fa-history text-4xl text-slate-300 mb-4"></i>
                                    <p class="text-slate-500 text-sm">No hay historial de cambios disponible</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal: Ajustar Stock -->
    @can('productos.editar')
        <div id="ajustar-stock-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Overlay -->
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true" onclick="closeModal('ajustar-stock-modal')"></div>

                <!-- Center modal -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form action="{{ route('productos.ajustar-stock', $producto->id) }}" method="POST">
                        @csrf
                        <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-blue-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                                    <i class="fas fa-boxes text-blue-600"></i>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                                    <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">
                                        Ajustar Stock
                                    </h3>
                                    <p class="text-sm text-gray-500 mt-1">{{ $producto->nombre }}</p>
                                    <p class="text-xs text-gray-400">Stock actual: {{ number_format($producto->stock_actual) }}</p>
                                    
                                    <div class="mt-4 space-y-4">
                                        <!-- Tipo de Movimiento -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Tipo de Movimiento <span class="text-red-500">*</span>
                                            </label>
                                            <div class="grid grid-cols-3 gap-2">
                                                <label class="relative flex items-center justify-center p-3 border rounded-lg cursor-pointer hover:bg-green-50 hover:border-green-300 transition-colors">
                                                    <input type="radio" name="tipo_movimiento" value="entrada" required class="sr-only peer">
                                                    <div class="text-center peer-checked:text-green-700">
                                                        <i class="fas fa-arrow-down text-lg peer-checked:text-green-600"></i>
                                                        <p class="text-xs font-medium mt-1">Entrada</p>
                                                    </div>
                                                    <div class="absolute inset-0 border-2 border-green-600 rounded-lg opacity-0 peer-checked:opacity-100"></div>
                                                </label>
                                                
                                                <label class="relative flex items-center justify-center p-3 border rounded-lg cursor-pointer hover:bg-red-50 hover:border-red-300 transition-colors">
                                                    <input type="radio" name="tipo_movimiento" value="salida" required class="sr-only peer">
                                                    <div class="text-center peer-checked:text-red-700">
                                                        <i class="fas fa-arrow-up text-lg peer-checked:text-red-600"></i>
                                                        <p class="text-xs font-medium mt-1">Salida</p>
                                                    </div>
                                                    <div class="absolute inset-0 border-2 border-red-600 rounded-lg opacity-0 peer-checked:opacity-100"></div>
                                                </label>
                                                
                                                <label class="relative flex items-center justify-center p-3 border rounded-lg cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition-colors">
                                                    <input type="radio" name="tipo_movimiento" value="ajuste" required class="sr-only peer">
                                                    <div class="text-center peer-checked:text-blue-700">
                                                        <i class="fas fa-sync text-lg peer-checked:text-blue-600"></i>
                                                        <p class="text-xs font-medium mt-1">Ajuste</p>
                                                    </div>
                                                    <div class="absolute inset-0 border-2 border-blue-600 rounded-lg opacity-0 peer-checked:opacity-100"></div>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Cantidad -->
                                        <div>
                                            <label for="cantidad" class="block text-sm font-medium text-gray-700 mb-1">
                                                Cantidad <span class="text-red-500">*</span>
                                            </label>
                                            <input type="number" id="cantidad" name="cantidad" min="1" required
                                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Ingrese la cantidad">
                                        </div>

                                        <!-- Descripción -->
                                        <div>
                                            <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">
                                                Descripción / Motivo <span class="text-red-500">*</span>
                                            </label>
                                            <textarea id="descripcion" name="descripcion" rows="3" required
                                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Ej: Reabastecimiento de proveedor, Venta directa, Corrección de inventario..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                            <button type="submit"
                                class="inline-flex justify-center w-full px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-cyan-400 border border-transparent rounded-lg shadow-sm hover:from-blue-700 hover:to-cyan-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:w-auto">
                                <i class="fas fa-check mr-2"></i>
                                Confirmar Ajuste
                            </button>
                            <button type="button" onclick="closeModal('ajustar-stock-modal')"
                                class="inline-flex justify-center w-full px-4 py-2 mt-3 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:w-auto">
                                <i class="fas fa-times mr-2"></i>
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
@endsection
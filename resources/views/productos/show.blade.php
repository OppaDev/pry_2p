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
        <div id="ajustar-stock-modal" class="fixed inset-0 z-[9999] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <!-- Overlay -->
            <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity backdrop-blur-sm" aria-hidden="true" onclick="closeModal('ajustar-stock-modal')"></div>
            
            <!-- Container centrado -->
            <div class="fixed inset-0 z-[10000] overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 sm:p-6">
                    <!-- Modal centrado con tamaño ajustado -->
                    <div class="relative w-full max-w-md sm:max-w-xl transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all">
                    <form action="{{ route('productos.ajustar-stock', $producto->id) }}" method="POST">
                        @csrf
                        <!-- Header del modal -->
                        <div class="bg-gradient-to-r from-blue-600 to-cyan-500 px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-lg">
                                        <i class="fas fa-boxes text-white text-xl"></i>
                                    </div>
                                </div>
                                <div class="ml-4 flex-1">
                                    <h3 class="text-xl font-bold text-white" id="modal-title">
                                        Ajustar Stock
                                    </h3>
                                    <p class="text-sm text-blue-100 mt-1">{{ $producto->nombre }}</p>
                                    <p class="text-xs text-blue-200 font-semibold">Stock actual: {{ number_format($producto->stock_actual) }} unidades</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Body del modal -->
                        <div class="px-6 py-5 bg-white">
                            <div class="space-y-5">
                                <!-- Tipo de Movimiento -->
                                <div>
                                    <label class="block text-base font-semibold text-gray-800 mb-3">
                                        <i class="fas fa-exchange-alt mr-2 text-blue-600"></i>Tipo de Movimiento <span class="text-red-500">*</span>
                                    </label>
                                    <div class="grid grid-cols-3 gap-3">
                                        <label class="relative flex flex-col items-center justify-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-green-400 hover:bg-green-50 transition-all duration-200">
                                            <input type="radio" name="tipo_movimiento" value="entrada" required class="sr-only peer">
                                            <i class="fas fa-arrow-down text-2xl text-gray-400 peer-checked:text-green-600 mb-2"></i>
                                            <p class="text-sm font-medium text-gray-600 peer-checked:text-green-700">Entrada</p>
                                            <div class="absolute inset-0 border-2 border-green-600 rounded-xl opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none"></div>
                                        </label>
                                        
                                        <label class="relative flex flex-col items-center justify-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-red-400 hover:bg-red-50 transition-all duration-200">
                                            <input type="radio" name="tipo_movimiento" value="salida" required class="sr-only peer">
                                            <i class="fas fa-arrow-up text-2xl text-gray-400 peer-checked:text-red-600 mb-2"></i>
                                            <p class="text-sm font-medium text-gray-600 peer-checked:text-red-700">Salida</p>
                                            <div class="absolute inset-0 border-2 border-red-600 rounded-xl opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none"></div>
                                        </label>
                                        
                                        <label class="relative flex flex-col items-center justify-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition-all duration-200">
                                            <input type="radio" name="tipo_movimiento" value="ajuste" required class="sr-only peer">
                                            <i class="fas fa-sync text-2xl text-gray-400 peer-checked:text-blue-600 mb-2"></i>
                                            <p class="text-sm font-medium text-gray-600 peer-checked:text-blue-700">Ajuste</p>
                                            <div class="absolute inset-0 border-2 border-blue-600 rounded-xl opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none"></div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Cantidad -->
                                <div>
                                    <label for="cantidad" class="block text-base font-semibold text-gray-800 mb-2">
                                        <i class="fas fa-hashtag mr-2 text-blue-600"></i>Cantidad <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" id="cantidad" name="cantidad" min="1" required
                                        class="w-full px-4 py-3 text-base border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                        placeholder="Ej: 100">
                                </div>

                                <!-- Descripción -->
                                <div>
                                    <label for="descripcion" class="block text-base font-semibold text-gray-800 mb-2">
                                        <i class="fas fa-comment-alt mr-2 text-blue-600"></i>Descripción / Motivo <span class="text-red-500">*</span>
                                    </label>
                                    <textarea id="descripcion" name="descripcion" rows="4" required
                                        class="w-full px-4 py-3 text-base border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none"
                                        placeholder="Ej: Reabastecimiento de proveedor, Venta directa, Corrección de inventario..."></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Footer del modal -->
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                            <button type="button" onclick="closeModal('ajustar-stock-modal')"
                                class="inline-flex items-center justify-center px-6 py-3 text-base font-semibold text-gray-700 bg-white border-2 border-gray-300 rounded-lg shadow-sm hover:bg-gray-100 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all">
                                <i class="fas fa-times mr-2"></i>
                                Cancelar
                            </button>
                            <button type="submit"
                                class="inline-flex items-center justify-center px-6 py-3 text-base font-bold text-white bg-gradient-to-r from-blue-600 to-cyan-500 border border-transparent rounded-lg shadow-lg hover:from-blue-700 hover:to-cyan-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:scale-105 transition-all">
                                <i class="fas fa-check-circle mr-2"></i>
                                Confirmar Ajuste
                            </button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    @endcan

<script>
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
}

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
}

// Cerrar modal con ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modals = document.querySelectorAll('.fixed.inset-0:not(.hidden)');
        modals.forEach(modal => {
            if (modal.id) closeModal(modal.id);
        });
    }
});
</script>
@endsection
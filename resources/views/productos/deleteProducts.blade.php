@extends('layouts.app')
@section('content')
    <div class="w-full px-6 py-6 mx-auto text-lg">
        <div class="container-fluid py-4">
            <div class="flex items-center justify-between">
                <h1 class="mb-0 text-2xl font-semibold text-slate-700">PRODUCTOS ELIMINADOS</h1>
                <a href="{{ route('productos.index') }}" 
                    class="inline-block px-6 py-2 mb-0 font-bold text-center uppercase align-middle transition-all bg-transparent border border-solid rounded-lg shadow-none cursor-pointer leading-pro text-base ease-soft-in tracking-tight-soft bg-gradient-to-tl from-blue-600 to-blue-400 bg-150 bg-x-25 border-blue-600 text-white hover:scale-102 hover:shadow-soft-xs active:opacity-85">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver a Productos
                </a>
            </div>
        </div>
        <div class="flex flex-wrap -mx-3">            
            <div class="flex-none w-full max-w-full px-3">
                <div
                    class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <div class="flex justify-between items-center">
                            <h6 class="mb-0 text-xl font-semibold text-slate-700">
                                <i class="fas fa-trash-restore mr-2 text-slate-700"></i>
                                PRODUCTOS ELIMINADOS
                            </h6>
                            <div class="flex items-center space-x-3">
                                <!-- Formulario de búsqueda -->
                                <form method="GET" action="{{ route('productos.deleted') }}" class="flex items-center space-x-2">
                                    <!-- Mantener el parámetro per_page -->
                                    <input type="hidden" name="per_page" value="{{ $perPage }}">

                                    <div
                                        class="flex items-center space-x-2 bg-gradient-to-r from-red-50 to-red-100 px-4 py-2 rounded-xl border border-red-200/60 shadow-sm">
                                        <label for="search" class="text-lg font-medium text-red-600 flex items-center">
                                            <i class="fas fa-search mr-2 text-red-500"></i>
                                            <span>Buscar:</span>
                                        </label>
                                        <input type="text" id="search" name="search" value="{{ $search }}"
                                            placeholder="Nombre o código..."
                                            class="px-3 py-1.5 text-lg bg-white/80 backdrop-blur-sm border border-red-200/60 rounded-lg shadow-soft-xs hover:shadow-soft-sm focus:shadow-soft-md focus:outline-none focus:ring-2 focus:ring-red-200/50 focus:border-red-300 transition-all duration-300 ease-soft-in-out text-slate-700 min-w-[200px]">
                                        <button type="submit"
                                            class="px-3 py-1.5 text-lg bg-red-500 hover:bg-red-600 text-white rounded-lg shadow-soft-xs hover:shadow-soft-sm focus:shadow-soft-md focus:outline-none focus:ring-2 focus:ring-red-200/50 transition-all duration-300 ease-soft-in-out">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        @if ($search)
                                            <a href="{{ route('productos.deleted', ['per_page' => $perPage]) }}"
                                                class="px-3 py-1.5 text-lg bg-gray-500 hover:bg-gray-600 text-white rounded-lg shadow-soft-xs hover:shadow-soft-sm focus:shadow-soft-md focus:outline-none focus:ring-2 focus:ring-gray-200/50 transition-all duration-300 ease-soft-in-out"
                                                title="Limpiar búsqueda">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        @endif
                                    </div>
                                </form>

                                <!-- Selector de per_page -->
                                <div
                                    class="flex items-center space-x-2 bg-gradient-to-r from-slate-50 to-slate-100 px-4 py-2 rounded-xl border border-slate-200/60 shadow-sm">
                                    <label for="per_page" class="text-lg font-medium text-slate-600 flex items-center">
                                        <i class="fas fa-eye mr-2 text-slate-500"></i>
                                        <span>Mostrar:</span>
                                    </label>
                                    <select id="per_page" name="per_page" onchange="changePerPage(this.value)"
                                        class="px-3 py-1.5 text-lg bg-white/80 backdrop-blur-sm border border-slate-200/60 rounded-lg shadow-soft-xs hover:shadow-soft-sm focus:shadow-soft-md focus:outline-none focus:ring-2 focus:ring-slate-200/50 focus:border-slate-300 transition-all duration-300 ease-soft-in-out text-slate-700 cursor-pointer">
                                        <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                        <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
                                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                    </select>
                                    <span class="text-lg font-medium text-slate-600">por página</span>
                                </div>
                            </div>
                        </div>
                        @if ($errors->has('per_page'))
                            <div class="relative w-full p-4 text-white bg-red-500 rounded-lg mt-4">
                                {{ $errors->first('per_page') }}
                            </div>
                        @endif
                        @if ($errors->has('search'))
                            <div class="relative w-full p-4 text-white bg-red-500 rounded-lg mt-4">
                                {{ $errors->first('search') }}
                            </div>
                        @endif
                        @if ($search)
                            <div
                                class="relative w-full p-4 text-red-700 bg-red-100 border border-red-300 rounded-lg mt-4">
                                <div class="flex items-center">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <span>Mostrando resultados para: <strong>"{{ $search }}"</strong></span>
                                    <span class="ml-2 text-sm text-red-600">({{ $productos->total() }}
                                        {{ $productos->total() == 1 ? 'resultado' : 'resultados' }})</span>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-0 overflow-x-auto">
                            <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                                <thead class="align-bottom">
                                    <tr>
                                        <th
                                            class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-base border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            NOMBRE</th>
                                        <th
                                            class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-base border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            CÓDIGO</th>
                                        <th
                                            class="px-6 py-3 pl-2 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-base border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            CANTIDAD</th>
                                        <th
                                            class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-base border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            PRECIO</th>
                                        <th
                                            class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-base border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            ELIMINADO</th>
                                        <th
                                            class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-gray-200 border-solid shadow-none tracking-none whitespace-nowrap text-slate-400 opacity-70 text-center">
                                            ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($productos as $producto)
                                        <tr class="table-row-hover transition-all duration-200 bg-red-50/20">
                                            <td
                                                class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <div class="flex px-2 py-1">
                                                    <div class="flex flex-col justify-center">
                                                        <h6 class="mb-0 text-lg leading-normal text-slate-600">{{ $producto->nombre }}</h6>
                                                        <span class="text-xs text-red-500 font-medium">
                                                            <i class="fas fa-trash mr-1"></i>
                                                            Eliminado
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td
                                                class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <p class="mb-0 text-lg font-semibold leading-tight text-slate-600">{{ $producto->codigo }}</p>
                                            </td>
                                            <td
                                                class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <span
                                                    class="text-lg font-semibold leading-tight text-slate-400">{{ number_format($producto->cantidad) }}</span>
                                            </td>
                                            <td
                                                class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <span
                                                    class="text-lg font-semibold leading-tight text-slate-500">${{ number_format($producto->precio, 2) }}</span>
                                            </td>
                                            <td
                                                class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <span
                                                    class="text-sm font-semibold leading-tight text-red-600">{{ $producto->deleted_at->format('d/m/Y H:i') }}</span>
                                            </td>
                                            <td
                                                class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <div class="flex items-center justify-center space-x-2 action-buttons">
                                                    <button type="button" onclick="openModal('restore-producto-{{ $producto->id }}-modal')"
                                                        class="px-3 py-1.5 text-sm font-semibold text-white bg-gradient-to-r from-green-500 to-green-600 rounded-lg hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-green-200 transition-all duration-200 shadow-sm hover:shadow-md btn-soft-transition">
                                                        <i class="fas fa-undo mr-1"></i>
                                                        Restaurar
                                                    </button>
                                                    <button type="button" onclick="openModal('force-delete-producto-{{ $producto->id }}-modal')"
                                                        class="px-3 py-1.5 text-sm font-semibold text-white bg-gradient-to-r from-red-600 to-red-700 rounded-lg hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-red-200 transition-all duration-200 shadow-sm hover:shadow-md btn-soft-transition">
                                                        <i class="fas fa-trash-alt mr-1"></i>
                                                        Eliminar Definitivamente
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6"
                                                class="p-4 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <div class="flex flex-col items-center py-8">
                                                    <i class="fas fa-trash-restore text-4xl text-slate-300 mb-4"></i>
                                                    <p class="text-xl font-medium text-slate-500">No hay productos eliminados</p>
                                                    <p class="text-lg text-slate-400">
                                                        @if($search)
                                                            No se encontraron productos eliminados que coincidan con tu búsqueda.
                                                        @else
                                                            No tienes productos en la papelera de reciclaje.
                                                        @endif
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <!-- Paginación -->
                            <div class="flex items-center mt-6 px-6">
                                <div class="w-full max-w-4xl">
                                    {{ $productos->appends(request()->input())->links('pagination::tailwind') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modales de Confirmación de Restauración y Eliminación Permanente -->
    @foreach($productos as $producto)
        <!-- Modal de Restauración -->
        @include('components.restore-modal', [
            'modalId' => 'restore-producto-' . $producto->id . '-modal',
            'title' => 'Confirmar Restauración de Producto',
            'message' => '¿Estás seguro de que deseas restaurar este producto? El producto volverá a estar disponible en la lista principal.',
            'itemName' => $producto->nombre,
            'itemDetails' => 'Código: ' . $producto->codigo . ' | Precio: $' . number_format($producto->precio, 2),
            'restoreRoute' => route('productos.restore', $producto->id),
            'confirmText' => 'Restaurar Producto'
        ])
        
        <!-- Modal de Eliminación Permanente -->
        @include('components.force-delete-modal', [
            'modalId' => 'force-delete-producto-' . $producto->id . '-modal',
            'title' => 'Confirmar Eliminación Permanente de Producto',
            'message' => 'Esta acción eliminará permanentemente el producto y NO se podrá recuperar jamás.',
            'itemName' => $producto->nombre,
            'itemDetails' => 'Código: ' . $producto->codigo . ' | Cantidad: ' . $producto->cantidad,
            'itemCode' => $producto->codigo,
            'itemType' => 'producto',
            'deleteRoute' => route('productos.forceDelete', $producto->id),
            'confirmText' => 'Eliminar Permanentemente'
        ])
    @endforeach
    
    <script>
        function changePerPage(value) {
            const url = new URL(window.location);
            url.searchParams.set('per_page', value);
            url.searchParams.delete('page');
            const currentSearch = document.getElementById('search').value;
            if (currentSearch) {
                url.searchParams.set('search', currentSearch);
            }
            window.location.href = url.toString();
        }
    </script>
@endsection

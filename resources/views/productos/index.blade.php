@extends('layouts.app')
@section('content')
    <div class="w-full px-6 py-6 mx-auto text-lg">
        <div class="container-fluid py-4">
            <h1 class="mb-0 text-2xl font-semibold text-slate-700">LISTA DE PRODUCTOS</h1>
        </div>
        <div class="flex flex-wrap -mx-3">
            <!-- Mostrar mensajes de éxito -->
            @if(session('success'))
                <div class="w-full max-w-full px-3 mb-4">
                    <div class="relative w-full p-4 text-white bg-green-500 rounded-lg shadow-soft-xl">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-3"></i>
                            <div>
                                <strong>Éxito:</strong> {{ session('success') }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Mostrar mensajes de error -->
            @if(session('error'))
                <div class="w-full max-w-full px-3 mb-4">
                    <div class="relative w-full p-4 text-white bg-red-500 rounded-lg shadow-soft-xl">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-3"></i>
                            <div>
                                <strong>Error:</strong> {{ session('error') }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="flex-none w-full max-w-full px-3">
                <div
                    class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <div class="flex justify-between items-center">
                            <h6 class="mb-0 text-xl font-semibold text-slate-700">
                                <i class="fas fa-box mr-2 text-slate-700"></i>
                                PRODUCTOS
                            </h6>
                            <div class="flex items-center space-x-3">
                                <!-- Formulario de búsqueda -->
                                <form method="GET" action="{{ route('productos.index') }}" class="flex items-center space-x-2">
                                    <!-- Mantener el parámetro per_page -->
                                    <input type="hidden" name="per_page" value="{{ $perPage }}">

                                    <div
                                        class="flex items-center space-x-2 bg-gradient-to-r from-blue-50 to-blue-100 px-4 py-2 rounded-xl border border-blue-200/60 shadow-sm">
                                        <label for="search" class="text-lg font-medium text-blue-600 flex items-center">
                                            <i class="fas fa-search mr-2 text-blue-500"></i>
                                            <span>Buscar:</span>
                                        </label>
                                        <input type="text" id="search" name="search" value="{{ $search }}"
                                            placeholder="Nombre o código..."
                                            class="px-3 py-1.5 text-lg bg-white/80 backdrop-blur-sm border border-blue-200/60 rounded-lg shadow-soft-xs hover:shadow-soft-sm focus:shadow-soft-md focus:outline-none focus:ring-2 focus:ring-blue-200/50 focus:border-blue-300 transition-all duration-300 ease-soft-in-out text-slate-700 min-w-[200px]">
                                        <button type="submit"
                                            class="px-3 py-1.5 text-lg bg-blue-500 hover:bg-blue-600 text-white rounded-lg shadow-soft-xs hover:shadow-soft-sm focus:shadow-soft-md focus:outline-none focus:ring-2 focus:ring-blue-200/50 transition-all duration-300 ease-soft-in-out">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        @if ($search)
                                            <a href="{{ route('productos.index', ['per_page' => $perPage]) }}"
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
                                class="relative w-full p-4 text-blue-700 bg-blue-100 border border-blue-300 rounded-lg mt-4">
                                <div class="flex items-center">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <span>Mostrando resultados para: <strong>"{{ $search }}"</strong></span>
                                    <span class="ml-2 text-sm text-blue-600">({{ $productos->total() }}
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
                                            CREADO</th>
                                        <th
                                            class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-base border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            ACTUALIZADO</th>
                                        <th
                                            class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-gray-200 border-solid shadow-none tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($productos as $producto)
                                        <tr class="table-row-hover transition-all duration-200">
                                            <td
                                                class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <div class="flex px-2 py-1">
                                                    <div class="flex flex-col justify-center">
                                                        <h6 class="mb-0 text-lg leading-normal">{{ $producto->nombre }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td
                                                class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <p class="mb-0 text-lg font-semibold leading-tight">{{ $producto->codigo }}</p>
                                            </td>
                                            <td
                                                class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <span
                                                    class="text-lg font-semibold leading-tight text-slate-400">{{ number_format($producto->cantidad) }}</span>
                                            </td>
                                            <td
                                                class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <span
                                                    class="text-lg font-semibold leading-tight text-green-600">${{ number_format($producto->precio, 2) }}</span>
                                            </td>
                                            <td
                                                class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <span
                                                    class="text-lg font-semibold leading-tight text-slate-400">{{ $producto->created_at->format('d/m/y') }}</span>
                                            </td>
                                            <td
                                                class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <span
                                                    class="text-lg font-semibold leading-tight text-slate-400">{{ $producto->updated_at->format('d/m/y') }}</span>
                                            </td>
                                            <td
                                                class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <div class="flex items-center space-x-2 action-buttons">
                                                    <a href="{{ route('productos.edit', $producto->id) }}"
                                                        class="px-3 py-1.5 text-sm font-semibold text-white bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200 transition-all duration-200 shadow-sm hover:shadow-md btn-soft-transition">
                                                        <i class="fas fa-edit mr-1"></i>
                                                        Editar
                                                    </a>
                                                    <button type="button" onclick="openModal('delete-producto-{{ $producto->id }}-modal')"
                                                        class="px-3 py-1.5 text-sm font-semibold text-white bg-gradient-to-r from-red-500 to-red-600 rounded-lg hover:from-red-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-red-200 transition-all duration-200 shadow-sm hover:shadow-md btn-soft-transition">
                                                        <i class="fas fa-trash mr-1"></i>
                                                        Eliminar
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7"
                                                class="p-4 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <div class="flex flex-col items-center py-8">
                                                    <i class="fas fa-box-open text-4xl text-slate-300 mb-4"></i>
                                                    <p class="text-xl font-medium text-slate-500">No hay productos disponibles</p>
                                                    <p class="text-lg text-slate-400">
                                                        @if($search)
                                                            No se encontraron productos que coincidan con tu búsqueda.
                                                        @else
                                                            Aún no has agregado ningún producto.
                                                        @endif
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="flex justify-between items-center mt-6 p-6 pt-0 bg-gray-50/50 rounded-b-2xl border-t border-gray-100">
                                <div class="flex-1">
                                    {{ $productos->links() }}
                                </div>
                                <div class="ml-4">
                                    <a href="{{ route('productos.create') }}" 
                                        class="inline-block px-8 py-2 mb-0 font-bold text-center uppercase align-middle transition-all bg-transparent border border-solid rounded-lg shadow-none cursor-pointer leading-pro text-lg ease-soft-in tracking-tight-soft bg-gradient-to-tl from-purple-700 to-pink-500 bg-150 bg-x-25 border-purple-700 text-white hover:scale-102 hover:shadow-soft-xs active:opacity-85">
                                        <i class="fas fa-plus mr-2"></i>
                                        Nuevo Producto
                                    </a>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modales de Confirmación de Eliminación -->
    @foreach($productos as $producto)
        @include('components.delete-modal', [
            'modalId' => 'delete-producto-' . $producto->id . '-modal',
            'title' => 'Confirmar Eliminación de Producto',
            'message' => '¿Estás seguro de que deseas eliminar este producto? Esta acción eliminará permanentemente toda la información del producto.',
            'itemName' => $producto->nombre,
            'itemDetails' => 'Código: ' . $producto->codigo . ' | Precio: $' . number_format($producto->precio, 2),
            'deleteRoute' => route('productos.destroy', $producto->id),
            'confirmText' => 'Eliminar Producto'
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
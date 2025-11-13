@extends('layouts.app')
@section('content')
    <div class="w-full px-6 py-6 mx-auto text-lg">
        <div class="container-fluid py-4">
            <h1 class="mb-0 text-2xl font-semibold text-slate-700">LISTA DE PRODUCTOS</h1>
        </div>
        <div class="flex flex-wrap -mx-3">
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
                                <!-- Formulario de búsqueda y filtros -->
                                <form method="GET" action="{{ route('productos.index') }}" class="flex items-center flex-wrap gap-2">
                                    <!-- Mantener el parámetro per_page -->
                                    <input type="hidden" name="per_page" value="{{ $perPage }}">

                                    <!-- Búsqueda -->
                                    <div class="flex items-center space-x-2 bg-gradient-to-r from-blue-50 to-blue-100 px-3 py-1.5 rounded-xl border border-blue-200/60 shadow-sm">
                                        <label for="search" class="text-sm font-medium text-blue-600 flex items-center">
                                            <i class="fas fa-search mr-1 text-blue-500"></i>
                                            <span>Buscar:</span>
                                        </label>
                                        <input type="text" id="search" name="search" value="{{ request('search') }}"
                                            placeholder="Nombre, código o marca..."
                                            class="px-2 py-1 text-sm bg-white/80 border border-blue-200/60 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200/50 text-slate-700 w-[180px]">
                                        <button type="submit" class="px-2 py-1 text-sm bg-blue-500 hover:bg-blue-600 text-white rounded-lg">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>

                                    <!-- Filtro Categoría -->
                                    <div class="flex items-center space-x-2 bg-gradient-to-r from-purple-50 to-pink-100 px-3 py-1.5 rounded-xl border border-purple-200/60 shadow-sm">
                                        <label for="categoria_id" class="text-sm font-medium text-purple-600 flex items-center">
                                            <i class="fas fa-tags mr-1 text-purple-500"></i>
                                            <span>Categoría:</span>
                                        </label>
                                        <select id="categoria_id" name="categoria_id" onchange="this.form.submit()"
                                            class="px-2 py-1 text-sm bg-white/80 border border-purple-200/60 rounded-lg focus:outline-none text-slate-700 cursor-pointer">
                                            <option value="">Todas</option>
                                            @foreach($categorias as $categoria)
                                                <option value="{{ $categoria->id }}" {{ request('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                                    {{ $categoria->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Filtro Estado -->
                                    <div class="flex items-center space-x-2 bg-gradient-to-r from-green-50 to-lime-100 px-3 py-1.5 rounded-xl border border-green-200/60 shadow-sm">
                                        <label for="estado" class="text-sm font-medium text-green-600 flex items-center">
                                            <i class="fas fa-toggle-on mr-1 text-green-500"></i>
                                            <span>Estado:</span>
                                        </label>
                                        <select id="estado" name="estado" onchange="this.form.submit()"
                                            class="px-2 py-1 text-sm bg-white/80 border border-green-200/60 rounded-lg focus:outline-none text-slate-700 cursor-pointer">
                                            <option value="">Todos</option>
                                            <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                                            <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                        </select>
                                    </div>

                                    <!-- Checkbox Bajo Stock -->
                                    <div class="flex items-center space-x-2 bg-gradient-to-r from-red-50 to-rose-100 px-3 py-1.5 rounded-xl border border-red-200/60 shadow-sm">
                                        <label for="bajo_stock" class="text-sm font-medium text-red-600 flex items-center cursor-pointer">
                                            <input type="checkbox" id="bajo_stock" name="bajo_stock" value="1" {{ request('bajo_stock') ? 'checked' : '' }}
                                                onchange="this.form.submit()"
                                                class="mr-2 w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500">
                                            <i class="fas fa-exclamation-triangle mr-1 text-red-500"></i>
                                            <span>Bajo Stock</span>
                                        </label>
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
                                            class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-sm border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            PRODUCTO</th>
                                        <th
                                            class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-sm border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            CATEGORÍA</th>
                                        <th
                                            class="px-6 py-3 pl-2 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-sm border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            PRESENTACIÓN</th>
                                        <th
                                            class="px-6 py-3 pl-2 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-sm border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            STOCK</th>
                                        <th
                                            class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-sm border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            PRECIO</th>
                                        <th
                                            class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-sm border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            ESTADO</th>
                                        <th
                                            class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-gray-200 border-solid shadow-none tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($productos as $producto)
                                        <tr class="table-row-hover transition-all duration-200 {{ $producto->estaEnBajoStock() ? 'bg-red-50/30' : '' }}">
                                            <td class="p-2 align-middle bg-transparent border-b shadow-transparent">
                                                <div class="flex px-2 py-1">
                                                    <div class="flex flex-col justify-center">
                                                        <h6 class="mb-0 text-sm font-semibold leading-normal text-slate-700">{{ $producto->nombre }}</h6>
                                                        <p class="mb-0 text-xs text-slate-400">{{ $producto->codigo }} • {{ $producto->marca }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                @if($producto->categoria)
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-lg bg-gradient-to-r from-purple-100 to-pink-100 text-purple-700">
                                                        {{ $producto->categoria->nombre }}
                                                    </span>
                                                @else
                                                    <span class="text-xs text-slate-400">Sin categoría</span>
                                                @endif
                                            </td>
                                            <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <p class="mb-0 text-sm font-medium text-slate-600">{{ $producto->presentacion }}</p>
                                                <p class="mb-0 text-xs text-slate-400">{{ $producto->capacidad }} ({{ $producto->volumen_ml }}ml)</p>
                                            </td>
                                            <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <div class="flex flex-col items-center">
                                                    <span class="text-sm font-bold {{ $producto->estaEnBajoStock() ? 'text-red-600' : 'text-slate-700' }}">
                                                        {{ number_format($producto->stock_actual) }}
                                                    </span>
                                                    <span class="text-xs text-slate-400">mín: {{ number_format($producto->stock_minimo) }}</span>
                                                    @if($producto->estaEnBajoStock())
                                                        <span class="inline-flex items-center px-2 py-0.5 mt-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                                            <i class="fas fa-exclamation-triangle mr-1 text-xs"></i>
                                                            Bajo Stock
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <span class="text-sm font-bold leading-tight text-green-600">${{ number_format($producto->precio, 2) }}</span>
                                            </td>
                                            <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-lg {{ $producto->estado == 'activo' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600' }}">
                                                    {{ ucfirst($producto->estado) }}
                                                </span>
                                            </td>
                                            <td
                                                class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <div class="flex items-center space-x-2 action-buttons">
                                                    <a href="{{ route('productos.show', $producto->id) }}"
                                                        class="px-3 py-1.5 text-sm font-semibold text-white bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg hover:from-indigo-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-200 transition-all duration-200 shadow-sm hover:shadow-md btn-soft-transition">
                                                        <i class="fas fa-eye mr-1"></i>
                                                        Ver
                                                    </a>
                                                    <a href="{{ route('productos.audit-history', $producto->id) }}"
                                                        class="px-3 py-1.5 text-sm font-semibold text-white bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg hover:from-purple-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-200 transition-all duration-200 shadow-sm hover:shadow-md btn-soft-transition">
                                                        <i class="fas fa-history mr-1"></i>
                                                        Historial
                                                    </a>
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
                                                    <p class="text-sm text-slate-400">
                                                        @if(request('search') || request('categoria_id') || request('estado') || request('bajo_stock'))
                                                            No se encontraron productos con los filtros aplicados.
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
                                <div class="ml-4 flex space-x-3">
                                    <!-- Botón para crear un nuevo producto (solo Jefe de Bodega y Administrador) -->
                                    @can('productos.crear')
                                        <a href="{{ route('productos.create') }}" 
                                            class="inline-block px-8 py-2 mb-0 font-bold text-center uppercase align-middle transition-all bg-transparent border border-solid rounded-lg shadow-none cursor-pointer leading-pro text-base ease-soft-in tracking-tight-soft bg-gradient-to-tl from-purple-700 to-pink-500 bg-150 bg-x-25 border-purple-700 text-white hover:scale-102 hover:shadow-soft-xs active:opacity-85">
                                            <i class="fas fa-plus mr-2"></i>
                                            Nuevo Producto
                                        </a>
                                    @endcan
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modales de Confirmación de Eliminación - Solo cargar cuando hay productos -->
    @if($productos->count() > 0)
        @foreach($productos as $producto)
            @include('components.delete-modal', [
                'modalId' => 'delete-producto-' . $producto->id . '-modal',
                'title' => 'Confirmar Eliminación de Producto',
                'message' => '¿Estás seguro de que deseas eliminar este producto? Esta acción lo enviará a la papelera y podrá ser restaurado posteriormente.',
                'itemName' => $producto->nombre,
                'itemDetails' => 'Código: ' . $producto->codigo . ' | Precio: $' . number_format($producto->precio, 2),
                'deleteRoute' => route('productos.destroy', $producto->id),
                'confirmText' => 'Mover a Papelera'
            ])
        @endforeach
    @endif
    
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
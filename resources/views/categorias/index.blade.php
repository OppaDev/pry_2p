@extends('layouts.app')

@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <div class="flex items-center justify-between">
                        <h6 class="font-bold text-xl">Categorías de Productos</h6>
                        @can('productos.crear')
                        <a href="{{ route('categorias.create') }}" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all bg-gradient-to-tl from-purple-700 to-pink-500 rounded-lg cursor-pointer leading-pro text-sm ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 hover:shadow-soft-xs active:opacity-85">
                            <i class="fas fa-plus mr-2"></i>Nueva Categoría
                        </a>
                        @endcan
                    </div>
                </div>
                
                <!-- Filtros -->
                <div class="p-6 border-b border-gray-200">
                    <form method="GET" action="{{ route('categorias.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <input type="text" name="buscar" value="{{ request('buscar') }}" 
                                   class="focus:shadow-soft-primary-outline text-base leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" 
                                   placeholder="Buscar categoría...">
                        </div>
                        
                        <div>
                            <select name="estado" class="focus:shadow-soft-primary-outline text-base leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow">
                                <option value="">Activas</option>
                                <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                        
                        <div class="flex gap-2">
                            <button type="submit" class="inline-block px-6 py-2 font-bold text-center text-white uppercase align-middle transition-all bg-gradient-to-tl from-purple-700 to-pink-500 rounded-lg cursor-pointer leading-pro text-sm ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 hover:shadow-soft-xs active:opacity-85">
                                <i class="fas fa-search mr-2"></i>Buscar
                            </button>
                            <a href="{{ route('categorias.index') }}" class="inline-block px-6 py-2 font-bold text-center text-slate-700 uppercase align-middle transition-all bg-white border border-slate-300 rounded-lg cursor-pointer leading-pro text-sm ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 hover:shadow-soft-xs active:opacity-85">
                                <i class="fas fa-times mr-2"></i>Limpiar
                            </a>
                        </div>
                    </form>
                </div>
                
                <div class="flex-auto px-0 pt-0 pb-2">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @forelse($categorias as $categoria)
                            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                                <div class="flex-auto p-4">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex items-center">
                                            <div class="flex items-center justify-center w-12 h-12 mr-3 text-white bg-gradient-to-tl from-purple-700 to-pink-500 rounded-xl">
                                                <i class="fas fa-tags text-lg"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 text-lg font-bold text-slate-700">{{ $categoria->nombre }}</h6>
                                                <p class="mb-0 text-sm text-slate-400">{{ $categoria->productos_count }} productos</p>
                                            </div>
                                        </div>
                                        <span class="bg-gradient-to-tl {{ $categoria->estado == 'activo' ? 'from-green-600 to-lime-400' : 'from-slate-600 to-slate-300' }} px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">
                                            {{ $categoria->estado }}
                                        </span>
                                    </div>
                                    
                                    @if($categoria->descripcion)
                                    <p class="mb-4 text-sm text-slate-500">{{ Str::limit($categoria->descripcion, 100) }}</p>
                                    @endif
                                    
                                    <div class="flex gap-2 justify-end">
                                        <a href="{{ route('categorias.show', $categoria) }}" class="inline-block px-4 py-2 text-sm font-bold text-center text-white uppercase align-middle transition-all bg-gradient-to-tl from-blue-600 to-cyan-400 rounded-lg cursor-pointer leading-pro ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 hover:shadow-soft-xs active:opacity-85">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @can('productos.editar')
                                        <a href="{{ route('categorias.edit', $categoria) }}" class="inline-block px-4 py-2 text-sm font-bold text-center text-white uppercase align-middle transition-all bg-gradient-to-tl from-purple-700 to-pink-500 rounded-lg cursor-pointer leading-pro ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 hover:shadow-soft-xs active:opacity-85">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-span-full text-center py-8">
                                <p class="text-slate-400">No se encontraron categorías.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                    
                    <!-- Paginación -->
                    @if($categorias->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $categorias->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

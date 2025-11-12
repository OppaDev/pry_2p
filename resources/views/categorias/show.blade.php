@extends('layouts.app')

@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <div class="flex items-center justify-between">
                        <div>
                            <h6 class="font-bold text-xl">{{ $categoria->nombre }}</h6>
                            <p class="text-slate-400">{{ $categoria->descripcion }}</p>
                        </div>
                        <div class="flex gap-2">
                            @can('productos.editar')
                            <a href="{{ route('categorias.edit', $categoria) }}" class="inline-block px-4 py-2 font-bold text-center text-white uppercase align-middle transition-all bg-gradient-to-tl from-purple-700 to-pink-500 rounded-lg cursor-pointer leading-pro text-sm ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 hover:shadow-soft-xs active:opacity-85">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            @endcan
                            <a href="{{ route('categorias.index') }}" class="inline-block px-4 py-2 font-bold text-center text-slate-700 uppercase align-middle transition-all bg-white border border-slate-300 rounded-lg cursor-pointer leading-pro text-sm ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 hover:shadow-soft-xs active:opacity-85">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Estadísticas -->
                <div class="p-6 border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="flex items-center p-4 bg-gradient-to-tl from-blue-600 to-cyan-400 rounded-xl">
                            <div class="flex items-center justify-center w-12 h-12 mr-4 text-white bg-white/20 rounded-xl">
                                <i class="fas fa-box text-lg"></i>
                            </div>
                            <div class="text-white">
                                <p class="text-sm opacity-75">Total Productos</p>
                                <p class="text-2xl font-bold">{{ $estadisticas['total_productos'] }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center p-4 bg-gradient-to-tl from-green-600 to-lime-400 rounded-xl">
                            <div class="flex items-center justify-center w-12 h-12 mr-4 text-white bg-white/20 rounded-xl">
                                <i class="fas fa-check-circle text-lg"></i>
                            </div>
                            <div class="text-white">
                                <p class="text-sm opacity-75">Productos Activos</p>
                                <p class="text-2xl font-bold">{{ $estadisticas['productos_activos'] }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center p-4 bg-gradient-to-tl from-red-600 to-rose-400 rounded-xl">
                            <div class="flex items-center justify-center w-12 h-12 mr-4 text-white bg-white/20 rounded-xl">
                                <i class="fas fa-exclamation-triangle text-lg"></i>
                            </div>
                            <div class="text-white">
                                <p class="text-sm opacity-75">Bajo Stock</p>
                                <p class="text-2xl font-bold">{{ $estadisticas['productos_bajo_stock'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Productos -->
                <div class="flex-auto p-6">
                    <h6 class="font-bold text-lg mb-4">Productos en esta Categoría</h6>
                    
                    @if($productos->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($productos as $producto)
                        <div class="relative flex flex-col min-w-0 break-words bg-white border border-gray-200 shadow-sm rounded-xl">
                            <div class="p-4">
                                <div class="flex items-start justify-between mb-2">
                                    <h6 class="mb-0 text-base font-semibold text-slate-700">{{ $producto->nombre }}</h6>
                                    <span class="bg-gradient-to-tl {{ $producto->estado == 'activo' ? 'from-green-600 to-lime-400' : 'from-slate-600 to-slate-300' }} px-2 text-xs rounded py-1 inline-block whitespace-nowrap text-center font-bold uppercase text-white">
                                        {{ $producto->estado }}
                                    </span>
                                </div>
                                
                                <p class="mb-1 text-sm text-slate-600">{{ $producto->marca }}</p>
                                <p class="mb-2 text-sm text-slate-500">{{ $producto->presentacion }}</p>
                                
                                <div class="flex items-center justify-between">
                                    <p class="text-lg font-bold text-purple-600">${{ number_format($producto->precio, 2) }}</p>
                                    <p class="text-sm {{ $producto->estaEnBajoStock() ? 'text-red-600 font-semibold' : 'text-slate-600' }}">
                                        Stock: {{ $producto->stock_actual }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    @if($productos->hasPages())
                    <div class="mt-6">
                        {{ $productos->links() }}
                    </div>
                    @endif
                    @else
                    <p class="text-center text-slate-400 py-8">No hay productos en esta categoría</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

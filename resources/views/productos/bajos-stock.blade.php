@extends('layouts.app')
@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="container-fluid py-4">
            <h1 class="mb-0 text-2xl font-semibold text-slate-700">PRODUCTOS CON BAJO STOCK</h1>
            <p class="text-sm text-slate-400 mt-1">Productos que han alcanzado o están por debajo del stock mínimo configurado</p>
        </div>
        
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-12 h-12 mr-4 text-center bg-center rounded-lg shadow-soft-2xl bg-gradient-to-tl from-red-700 to-rose-500">
                                    <i class="fas fa-exclamation-triangle text-white text-lg"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 text-xl font-semibold text-slate-700">
                                        ALERTAS DE INVENTARIO
                                    </h6>
                                    <p class="text-sm text-slate-400">
                                        {{ $productos->total() }} {{ $productos->total() == 1 ? 'producto' : 'productos' }} requieren atención
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <!-- Filtro Categoría -->
                                <form method="GET" action="{{ route('productos.bajos-stock') }}" class="flex items-center space-x-2">
                                    <div class="flex items-center space-x-2 bg-gradient-to-r from-purple-50 to-pink-100 px-3 py-2 rounded-xl border border-purple-200/60 shadow-sm">
                                        <label for="categoria_id" class="text-sm font-medium text-purple-600 flex items-center">
                                            <i class="fas fa-tags mr-2 text-purple-500"></i>
                                            <span>Categoría:</span>
                                        </label>
                                        <select id="categoria_id" name="categoria_id" onchange="this.form.submit()"
                                            class="px-3 py-1.5 text-sm bg-white/80 backdrop-blur-sm border border-purple-200/60 rounded-lg focus:outline-none text-slate-700 cursor-pointer">
                                            <option value="">Todas</option>
                                            @foreach($categorias as $categoria)
                                                <option value="{{ $categoria->id }}" {{ request('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                                    {{ $categoria->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </form>
                                
                                <a href="{{ route('productos.index') }}"
                                    class="inline-block px-6 py-2 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-slate-600 to-slate-300 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Volver a Productos
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-0 overflow-x-auto">
                            <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                                <thead class="align-bottom">
                                    <tr>
                                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-sm border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            PRODUCTO
                                        </th>
                                        <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-sm border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            CATEGORÍA
                                        </th>
                                        <th class="px-6 py-3 pl-2 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-sm border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            STOCK ACTUAL
                                        </th>
                                        <th class="px-6 py-3 pl-2 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-sm border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            STOCK MÍNIMO
                                        </th>
                                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-sm border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            DIFERENCIA
                                        </th>
                                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-sm border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            PRECIO
                                        </th>
                                        <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-gray-200 border-solid shadow-none tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($productos as $producto)
                                        <tr class="transition-all duration-200 bg-red-50/50 hover:bg-red-100/30">
                                            <td class="p-2 align-middle bg-transparent border-b shadow-transparent">
                                                <div class="flex px-2 py-1">
                                                    <div class="flex flex-col justify-center">
                                                        <h6 class="mb-0 text-sm font-semibold leading-normal text-slate-700">
                                                            {{ $producto->nombre }}
                                                        </h6>
                                                        <p class="mb-0 text-xs text-slate-400">
                                                            {{ $producto->codigo }} • {{ $producto->marca }}
                                                        </p>
                                                        <p class="mb-0 text-xs text-slate-400">
                                                            {{ $producto->presentacion }} - {{ $producto->capacidad }}
                                                        </p>
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
                                                <div class="flex flex-col items-center">
                                                    <span class="text-lg font-bold text-red-600">
                                                        {{ number_format($producto->stock_actual) }}
                                                    </span>
                                                    <span class="inline-flex items-center px-2 py-0.5 mt-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                                        <i class="fas fa-exclamation-triangle mr-1 text-xs"></i>
                                                        Crítico
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <span class="text-sm font-semibold text-slate-600">
                                                    {{ number_format($producto->stock_minimo) }}
                                                </span>
                                            </td>
                                            <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                @php
                                                    $diferencia = $producto->stock_actual - $producto->stock_minimo;
                                                @endphp
                                                <span class="px-2 py-1 text-xs font-bold rounded-lg {{ $diferencia < 0 ? 'bg-red-200 text-red-800' : 'bg-orange-200 text-orange-800' }}">
                                                    {{ $diferencia }}
                                                </span>
                                            </td>
                                            <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <span class="text-sm font-bold leading-tight text-green-600">
                                                    ${{ number_format($producto->precio, 2) }}
                                                </span>
                                            </td>
                                            <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <div class="flex items-center justify-end space-x-2 pr-4">
                                                    @can('productos.editar')
                                                        <a href="{{ route('productos.show', $producto->id) }}"
                                                            class="px-3 py-1.5 text-xs font-semibold text-white bg-gradient-to-r from-blue-600 to-cyan-400 rounded-lg hover:from-blue-700 hover:to-cyan-500 focus:outline-none focus:ring-2 focus:ring-blue-200 transition-all duration-200 shadow-sm hover:shadow-md">
                                                            <i class="fas fa-box mr-1"></i>
                                                            Ajustar Stock
                                                        </a>
                                                        <a href="{{ route('productos.edit', $producto->id) }}"
                                                            class="px-3 py-1.5 text-xs font-semibold text-white bg-gradient-to-r from-purple-700 to-pink-500 rounded-lg hover:from-purple-800 hover:to-pink-600 focus:outline-none focus:ring-2 focus:ring-purple-200 transition-all duration-200 shadow-sm hover:shadow-md">
                                                            <i class="fas fa-edit mr-1"></i>
                                                            Editar
                                                        </a>
                                                    @else
                                                        <a href="{{ route('productos.show', $producto->id) }}"
                                                            class="px-3 py-1.5 text-xs font-semibold text-white bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg hover:from-indigo-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-200 transition-all duration-200 shadow-sm hover:shadow-md">
                                                            <i class="fas fa-eye mr-1"></i>
                                                            Ver
                                                        </a>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="p-4 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <div class="flex flex-col items-center py-8">
                                                    <div class="flex items-center justify-center w-16 h-16 mb-4 rounded-full bg-green-100">
                                                        <i class="fas fa-check-circle text-3xl text-green-600"></i>
                                                    </div>
                                                    <p class="text-xl font-medium text-slate-700">¡Excelente!</p>
                                                    <p class="text-sm text-slate-400 mt-2">
                                                        No hay productos con bajo stock en este momento.
                                                    </p>
                                                    <p class="text-xs text-slate-400 mt-1">
                                                        Todos los productos tienen stock suficiente según sus niveles mínimos configurados.
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            
                            @if($productos->hasPages())
                                <div class="flex justify-center items-center mt-6 p-6 pt-0">
                                    {{ $productos->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Card de estadísticas resumidas -->
        @if($productos->count() > 0)
            <div class="flex flex-wrap -mx-3 mt-6">
                <div class="w-full px-3">
                    <div class="relative flex flex-col min-w-0 break-words bg-gradient-to-r from-red-500 to-rose-600 border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-16 h-16 mr-4 text-center bg-white/20 rounded-xl">
                                    <i class="fas fa-chart-line text-white text-2xl"></i>
                                </div>
                                <div class="flex-1 text-white">
                                    <h6 class="mb-2 text-lg font-semibold">Resumen de Stock Crítico</h6>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <p class="text-sm text-white/80">Total productos afectados</p>
                                            <p class="text-2xl font-bold">{{ $productos->total() }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-white/80">Stock total faltante</p>
                                            <p class="text-2xl font-bold">
                                                {{ number_format($productos->sum(function($p) { 
                                                    return max(0, $p->stock_minimo - $p->stock_actual); 
                                                })) }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-white/80">Valor estimado reposición</p>
                                            <p class="text-2xl font-bold">
                                                ${{ number_format($productos->sum(function($p) { 
                                                    return max(0, $p->stock_minimo - $p->stock_actual) * $p->precio; 
                                                }), 2) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@extends('layouts.app')
@section('content')
    <div class="w-full px-6 py-6 mx-auto text-lg">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3">
                <div
                    class="relative flex flex-col min-w-0 mt-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <div class="flex items-center">
                            <div
                                class="flex items-center justify-center w-12 h-12 mr-4 text-center bg-center rounded-lg shadow-soft-2xl bg-gradient-to-tl from-purple-700 to-pink-500">
                                <i class="fas fa-edit text-white text-lg"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 text-lg font-semibold">Editar Producto</h6>
                                <p class="mb-0 text-sm leading-normal text-slate-400">Modifique la información del producto
                                    <span class="font-semibold text-slate-600">"{{ $producto->nombre }}"</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex-auto p-6">
                        <form action="{{ route('productos.update', $producto->id) }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <!-- Primera fila: Nombre y Código -->
                            <div class="flex flex-wrap -mx-3">
                                <div class="w-full max-w-full px-3 md:w-6/12 md:flex-none">
                                    <div class="mb-4">
                                        <label for="nombre" class="inline-block mb-2 ml-1 font-bold text-sm text-slate-700">
                                            Nombre del Producto <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $producto->nombre) }}" required
                                            class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid @error('nombre') border-red-500 @else border-gray-300 @enderror bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none"
                                            placeholder="Ej: Whisky Johnnie Walker Red Label">
                                        @error('nombre')
                                            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="w-full max-w-full px-3 md:w-6/12 md:flex-none">
                                    <div class="mb-4">
                                        <label for="codigo" class="inline-block mb-2 ml-1 font-bold text-sm text-slate-700">
                                            Código del Producto <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="codigo" name="codigo" value="{{ old('codigo', $producto->codigo) }}" required
                                            class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid @error('codigo') border-red-500 @else border-gray-300 @enderror bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none"
                                            placeholder="Ej: WHI-JW-RL-750">
                                        @error('codigo')
                                            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Segunda fila: Marca y Categoría -->
                            <div class="flex flex-wrap -mx-3">
                                <div class="w-full max-w-full px-3 md:w-6/12 md:flex-none">
                                    <div class="mb-4">
                                        <label for="marca" class="inline-block mb-2 ml-1 font-bold text-sm text-slate-700">
                                            Marca <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="marca" name="marca" value="{{ old('marca', $producto->marca) }}" required
                                            class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid @error('marca') border-red-500 @else border-gray-300 @enderror bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none"
                                            placeholder="Ej: Johnnie Walker">
                                        @error('marca')
                                            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="w-full max-w-full px-3 md:w-6/12 md:flex-none">
                                    <div class="mb-4">
                                        <label for="categoria_id" class="inline-block mb-2 ml-1 font-bold text-sm text-slate-700">
                                            Categoría <span class="text-red-500">*</span>
                                        </label>
                                        <select id="categoria_id" name="categoria_id" required
                                            class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid @error('categoria_id') border-red-500 @else border-gray-300 @enderror bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none">
                                            <option value="">Seleccione una categoría</option>
                                            @foreach(\App\Models\Categoria::orderBy('nombre')->get() as $categoria)
                                                <option value="{{ $categoria->id }}" {{ old('categoria_id', $producto->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                                    {{ $categoria->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('categoria_id')
                                            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Tercera fila: Presentación, Capacidad y Volumen -->
                            <div class="flex flex-wrap -mx-3">
                                <div class="w-full max-w-full px-3 md:w-4/12 md:flex-none">
                                    <div class="mb-4">
                                        <label for="presentacion" class="inline-block mb-2 ml-1 font-bold text-sm text-slate-700">
                                            Presentación <span class="text-red-500">*</span>
                                        </label>
                                        <select id="presentacion" name="presentacion" required
                                            class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid @error('presentacion') border-red-500 @else border-gray-300 @enderror bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none">
                                            <option value="">Seleccione</option>
                                            <option value="botella" {{ old('presentacion', $producto->presentacion) == 'botella' ? 'selected' : '' }}>Botella</option>
                                            <option value="lata" {{ old('presentacion', $producto->presentacion) == 'lata' ? 'selected' : '' }}>Lata</option>
                                            <option value="caja" {{ old('presentacion', $producto->presentacion) == 'caja' ? 'selected' : '' }}>Caja</option>
                                            <option value="barril" {{ old('presentacion', $producto->presentacion) == 'barril' ? 'selected' : '' }}>Barril</option>
                                        </select>
                                        @error('presentacion')
                                            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="w-full max-w-full px-3 md:w-4/12 md:flex-none">
                                    <div class="mb-4">
                                        <label for="capacidad" class="inline-block mb-2 ml-1 font-bold text-sm text-slate-700">
                                            Capacidad <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="capacidad" name="capacidad" value="{{ old('capacidad', $producto->capacidad) }}" required
                                            class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid @error('capacidad') border-red-500 @else border-gray-300 @enderror bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none"
                                            placeholder="Ej: 750ml, 1L, 330ml">
                                        @error('capacidad')
                                            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="w-full max-w-full px-3 md:w-4/12 md:flex-none">
                                    <div class="mb-4">
                                        <label for="volumen_ml" class="inline-block mb-2 ml-1 font-bold text-sm text-slate-700">
                                            Volumen (ml) <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" id="volumen_ml" name="volumen_ml" value="{{ old('volumen_ml', $producto->volumen_ml) }}" min="1" required
                                            class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid @error('volumen_ml') border-red-500 @else border-gray-300 @enderror bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none"
                                            placeholder="750">
                                        <p class="mt-1 text-xs text-slate-400">Volumen en mililitros (numérico)</p>
                                        @error('volumen_ml')
                                            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Cuarta fila: Stock Actual, Stock Mínimo y Precio -->
                            <div class="flex flex-wrap -mx-3">
                                <div class="w-full max-w-full px-3 md:w-4/12 md:flex-none">
                                    <div class="mb-4">
                                        <label for="stock_actual" class="inline-block mb-2 ml-1 font-bold text-sm text-slate-700">
                                            Stock Actual <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" id="stock_actual" name="stock_actual" value="{{ old('stock_actual', $producto->stock_actual) }}" min="0" required disabled
                                            class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-gray-100 bg-clip-padding px-3 py-2 font-normal text-gray-600 transition-all focus:border-fuchsia-300 focus:outline-none cursor-not-allowed"
                                            placeholder="0">
                                        <p class="mt-1 text-xs text-slate-400">Use el botón "Ajustar Stock" en la vista de detalle</p>
                                    </div>
                                </div>

                                <div class="w-full max-w-full px-3 md:w-4/12 md:flex-none">
                                    <div class="mb-4">
                                        <label for="stock_minimo" class="inline-block mb-2 ml-1 font-bold text-sm text-slate-700">
                                            Stock Mínimo <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" id="stock_minimo" name="stock_minimo" value="{{ old('stock_minimo', $producto->stock_minimo) }}" min="0" required
                                            class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid @error('stock_minimo') border-red-500 @else border-gray-300 @enderror bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none"
                                            placeholder="5">
                                        <p class="mt-1 text-xs text-slate-400">Alerta cuando el stock llegue a este nivel</p>
                                        @error('stock_minimo')
                                            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="w-full max-w-full px-3 md:w-4/12 md:flex-none">
                                    <div class="mb-4">
                                        <label for="precio" class="inline-block mb-2 ml-1 font-bold text-sm text-slate-700">
                                            Precio Unitario <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">$</span>
                                            <input type="number" id="precio" name="precio" value="{{ old('precio', $producto->precio) }}" step="0.01" min="0.01" required
                                                class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid @error('precio') border-red-500 @else border-gray-300 @enderror bg-white bg-clip-padding pl-8 pr-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none"
                                                placeholder="0.00">
                                        </div>
                                        @error('precio')
                                            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Quinta fila: Estado y Descripción -->
                            <div class="flex flex-wrap -mx-3">
                                <div class="w-full max-w-full px-3 md:w-3/12 md:flex-none">
                                    <div class="mb-4">
                                        <label for="estado" class="inline-block mb-2 ml-1 font-bold text-sm text-slate-700">
                                            Estado
                                        </label>
                                        <select id="estado" name="estado"
                                            class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none">
                                            <option value="activo" {{ old('estado', $producto->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                                            <option value="inactivo" {{ old('estado', $producto->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="w-full max-w-full px-3 md:w-9/12 md:flex-none">
                                    <div class="mb-4">
                                        <label for="descripcion" class="inline-block mb-2 ml-1 font-bold text-sm text-slate-700">
                                            Descripción (Opcional)
                                        </label>
                                        <textarea id="descripcion" name="descripcion" rows="3"
                                            class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid @error('descripcion') border-red-500 @else border-gray-300 @enderror bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none"
                                            placeholder="Información adicional del producto...">{{ old('descripcion', $producto->descripcion) }}</textarea>
                                        @error('descripcion')
                                            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="flex flex-wrap -mx-3">
                                <div class="w-full max-w-full px-3">
                                    <div class="flex items-center justify-between pt-4">
                                        <a href="{{ route('productos.index') }}"
                                            class="inline-block px-6 py-2.5 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-slate-600 to-slate-300 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                            <i class="fas fa-arrow-left mr-2"></i>
                                            Volver
                                        </a>
                                        <button type="submit"
                                            class="inline-block px-6 py-2.5 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-purple-700 to-pink-500 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                            <i class="fas fa-save mr-2"></i>
                                            Actualizar Producto
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

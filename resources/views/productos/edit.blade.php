@extends('layouts.app')
@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <!-- Header -->
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3">
                <div class="relative flex items-center p-0 mt-6 overflow-hidden bg-center bg-cover min-h-75 rounded-2xl"
                    style="background-image: url('{{ asset('assets/img/curved-images/curved0.jpg') }}'); background-position-y: 50%">
                    <span
                        class="absolute inset-y-0 w-full h-full bg-center bg-cover bg-gradient-to-tl from-purple-700 to-pink-500 opacity-60"></span>
                </div>
            </div>
        </div>

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
                        <form action="{{ route('producto.update', $producto->id) }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <!-- Primera fila -->
                            <div class="flex flex-wrap -mx-3">
                                <div class="w-full max-w-full px-3 md:w-6/12 md:flex-none">
                                    <div class="mb-4">
                                        <label for="nombre"
                                            class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700">
                                            Nombre del Producto
                                        </label>
                                        <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $producto->nombre) }}"
                                            class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                                            placeholder="Ingrese el nombre del producto">
                                        @error('nombre')
                                            <p class="mb-0 text-xs leading-tight text-red-500 mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="w-full max-w-full px-3 md:w-6/12 md:flex-none">
                                    <div class="mb-4">
                                        <label for="codigo"
                                            class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700">
                                            Código del Producto <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="codigo" name="codigo" value="{{ old('codigo', $producto->codigo) }}"
                                            required
                                            class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                                            placeholder="Ej: PRD-001">
                                        @error('codigo')
                                            <p class="mb-0 text-xs leading-tight text-red-500 mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Segunda fila -->
                            <div class="flex flex-wrap -mx-3">
                                <div class="w-full max-w-full px-3 md:w-6/12 md:flex-none">
                                    <div class="mb-4">
                                        <label for="cantidad"
                                            class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700">
                                            Cantidad<span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" id="cantidad" name="cantidad" value="{{ old('cantidad', $producto->cantidad) }}"
                                            min="0" required
                                            class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                                            placeholder="0">
                                        @error('cantidad')
                                            <p class="mb-0 text-xs leading-tight text-red-500 mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="w-full max-w-full px-3 md:w-6/12 md:flex-none">
                                    <div class="mb-4">
                                        <label for="precio"
                                            class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700">
                                            Precio Unitario <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <span
                                                class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">$</span>
                                            <input type="number" id="precio" name="precio" value="{{ old('precio', $producto->precio) }}"
                                                step="0.01" min="0" required
                                                class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding pl-8 pr-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                                                placeholder="0.00">
                                        </div>
                                        @error('precio')
                                            <p class="mb-0 text-xs leading-tight text-red-500 mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>                            

                            <!-- Botones -->
                            <div class="flex flex-wrap -mx-3">
                                <div class="w-full max-w-full px-3">
                                    <div class="flex items-center justify-between pt-4">
                                        <div class="flex space-x-3">
                                            <a href="{{ route('productos.index') }}"
                                                class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-slate-600 to-slate-300 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                                <i class="fas fa-arrow-left mr-2"></i>
                                                Volver
                                            </a>
                                            
                                        </div>                                        <div class="flex space-x-3">
                                            <button type="button" onclick="resetForm()"
                                                class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-slate-600 to-slate-300 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                                <i class="fas fa-undo mr-2"></i>
                                                Restablecer
                                            </button>
                                            <button type="submit"
                                                class="inline-block px-8 py-2 mb-0 font-bold text-center uppercase align-middle transition-all bg-transparent border border-solid rounded-lg shadow-none cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft bg-gradient-to-tl from-purple-700 to-pink-500 bg-150 bg-x-25 border-purple-700 text-white hover:scale-102 hover:shadow-soft-xs active:opacity-85">
                                                <i class="fas fa-save mr-2"></i>
                                                Actualizar Producto
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function resetForm() {
            // Resetear a los valores originales
            document.getElementById('nombre').value = '{{ $producto->nombre }}';
            document.getElementById('codigo').value = '{{ $producto->codigo }}';
            document.getElementById('cantidad').value = '{{ $producto->cantidad }}';
            document.getElementById('precio').value = '{{ $producto->precio }}';
        }
    </script>
@endsection

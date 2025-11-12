@extends('layouts.app')

@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3 lg:w-1/2 mx-auto">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <h6 class="font-bold text-xl">Nueva Categoría</h6>
                    <p class="leading-normal text-base text-slate-400">Complete la información de la categoría</p>
                </div>
                
                <div class="flex-auto p-6">
                    <form action="{{ route('categorias.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Nombre <span class="text-red-500">*</span></label>
                            <input type="text" name="nombre" value="{{ old('nombre') }}" required 
                                   class="focus:shadow-soft-primary-outline text-base leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow @error('nombre') border-red-500 @enderror" 
                                   placeholder="Ej: Vinos Tintos">
                            @error('nombre')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Descripción</label>
                            <textarea name="descripcion" rows="3" 
                                      class="focus:shadow-soft-primary-outline text-base leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow @error('descripcion') border-red-500 @enderror" 
                                      placeholder="Descripción opcional">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Estado <span class="text-red-500">*</span></label>
                            <select name="estado" required 
                                    class="focus:shadow-soft-primary-outline text-base leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow @error('estado') border-red-500 @enderror">
                                <option value="activo" {{ old('estado', 'activo') == 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="inactivo" {{ old('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                            @error('estado')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="flex gap-2 mt-6">
                            <button type="submit" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all bg-gradient-to-tl from-purple-700 to-pink-500 rounded-lg cursor-pointer leading-pro text-sm ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 hover:shadow-soft-xs active:opacity-85">
                                <i class="fas fa-save mr-2"></i>Guardar Categoría
                            </button>
                            <a href="{{ route('categorias.index') }}" class="inline-block px-6 py-3 font-bold text-center text-slate-700 uppercase align-middle transition-all bg-white border border-slate-300 rounded-lg cursor-pointer leading-pro text-sm ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 hover:shadow-soft-xs active:opacity-85">
                                <i class="fas fa-times mr-2"></i>Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

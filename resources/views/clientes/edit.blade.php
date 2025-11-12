@extends('layouts.app')

@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <h6 class="font-bold text-xl">Editar Cliente</h6>
                    <p class="leading-normal text-base text-slate-400">Actualice la información del cliente</p>
                </div>
                
                <div class="flex-auto p-6">
                    <form action="{{ route('clientes.update', $cliente) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Tipo de Identificación <span class="text-red-500">*</span></label>
                                <select name="tipo_identificacion" required 
                                        class="focus:shadow-soft-primary-outline text-base leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow @error('tipo_identificacion') border-red-500 @enderror">
                                    <option value="cedula" {{ old('tipo_identificacion', $cliente->tipo_identificacion) == 'cedula' ? 'selected' : '' }}>Cédula</option>
                                    <option value="ruc" {{ old('tipo_identificacion', $cliente->tipo_identificacion) == 'ruc' ? 'selected' : '' }}>RUC</option>
                                    <option value="pasaporte" {{ old('tipo_identificacion', $cliente->tipo_identificacion) == 'pasaporte' ? 'selected' : '' }}>Pasaporte</option>
                                </select>
                                @error('tipo_identificacion')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Número de Identificación <span class="text-red-500">*</span></label>
                                <input type="text" name="identificacion" value="{{ old('identificacion', $cliente->identificacion) }}" required 
                                       class="focus:shadow-soft-primary-outline text-base leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow @error('identificacion') border-red-500 @enderror">
                                @error('identificacion')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Nombres <span class="text-red-500">*</span></label>
                                <input type="text" name="nombres" value="{{ old('nombres', $cliente->nombres) }}" required 
                                       class="focus:shadow-soft-primary-outline text-base leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow @error('nombres') border-red-500 @enderror">
                                @error('nombres')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Apellidos <span class="text-red-500">*</span></label>
                                <input type="text" name="apellidos" value="{{ old('apellidos', $cliente->apellidos) }}" required 
                                       class="focus:shadow-soft-primary-outline text-base leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow @error('apellidos') border-red-500 @enderror">
                                @error('apellidos')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Fecha de Nacimiento <span class="text-red-500">*</span></label>
                                <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $cliente->fecha_nacimiento) }}" required 
                                       max="{{ now()->subYears(18)->format('Y-m-d') }}"
                                       class="focus:shadow-soft-primary-outline text-base leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow @error('fecha_nacimiento') border-red-500 @enderror">
                                @error('fecha_nacimiento')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Teléfono <span class="text-red-500">*</span></label>
                                <input type="text" name="telefono" value="{{ old('telefono', $cliente->telefono) }}" required 
                                       class="focus:shadow-soft-primary-outline text-base leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow @error('telefono') border-red-500 @enderror">
                                @error('telefono')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Correo Electrónico</label>
                                <input type="email" name="correo" value="{{ old('correo', $cliente->correo) }}" 
                                       class="focus:shadow-soft-primary-outline text-base leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow @error('correo') border-red-500 @enderror">
                                @error('correo')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Dirección <span class="text-red-500">*</span></label>
                                <textarea name="direccion" rows="2" required 
                                          class="focus:shadow-soft-primary-outline text-base leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow @error('direccion') border-red-500 @enderror">{{ old('direccion', $cliente->direccion) }}</textarea>
                                @error('direccion')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Estado <span class="text-red-500">*</span></label>
                                <select name="estado" required 
                                        class="focus:shadow-soft-primary-outline text-base leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow @error('estado') border-red-500 @enderror">
                                    <option value="activo" {{ old('estado', $cliente->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                                    <option value="inactivo" {{ old('estado', $cliente->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                                @error('estado')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="flex gap-2 mt-6">
                            <button type="submit" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all bg-gradient-to-tl from-purple-700 to-pink-500 rounded-lg cursor-pointer leading-pro text-sm ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 hover:shadow-soft-xs active:opacity-85">
                                <i class="fas fa-save mr-2"></i>Actualizar Cliente
                            </button>
                            <a href="{{ route('clientes.index') }}" class="inline-block px-6 py-3 font-bold text-center text-slate-700 uppercase align-middle transition-all bg-white border border-slate-300 rounded-lg cursor-pointer leading-pro text-sm ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 hover:shadow-soft-xs active:opacity-85">
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

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
                                <h6 class="mb-0 text-lg font-semibold">Editar Asignatura</h6>
                                <p class="mb-0 text-sm leading-normal text-slate-400">Modifique la información de la asignatura
                                    <span class="font-semibold text-slate-600">"{{ $asignatura->nombre }}"</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex-auto p-6">
                        <form action="{{ route('asignaturas.update', $asignatura->id) }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <div class="flex flex-wrap -mx-3">
                                <div class="w-full max-w-full px-3 md:w-6/12 md:flex-none">
                                    <div class="mb-4">
                                        <label for="nombre"
                                            class="inline-block mb-2 ml-1 font-bold text-sm text-slate-700">
                                            Nombre de la Asignatura
                                        </label>
                                        <input type="text" id="nombre" name="nombre"
                                            value="{{ old('nombre', $asignatura->nombre) }}"
                                            class="focus:shadow-soft-primary-outline text-lg leading-6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                                            placeholder="Ingrese el nombre de la asignatura">
                                        @error('nombre')
                                            <p class="mb-0 text-sm leading-tight text-red-500 mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="w-full max-w-full px-3 md:w-6/12 md:flex-none">
                                    <div class="mb-4">
                                        <label for="codigo"
                                            class="inline-block mb-2 ml-1 font-bold text-sm text-slate-700">
                                            Código de la Asignatura <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="codigo" name="codigo"
                                            value="{{ old('codigo', $asignatura->codigo) }}" required
                                            class="focus:shadow-soft-primary-outline text-lg leading-6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                                            placeholder="Ej: ASG-001">
                                        @error('codigo')
                                            <p class="mb-0 text-sm leading-tight text-red-500 mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-wrap -mx-3">
                                <div class="w-full max-w-full px-3">
                                    <div class="flex items-center justify-between pt-4">
                                        <a href="{{ route('asignaturas.index') }}"
                                            class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-slate-600 to-slate-300 leading-pro text-sm ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                            <i class="fas fa-arrow-left mr-2"></i>
                                            Volver
                                        </a>
                                        <button type="submit"
                                            class="inline-block px-8 py-2 mb-0 font-bold text-center uppercase align-middle transition-all bg-transparent border border-solid rounded-lg shadow-none cursor-pointer leading-pro text-sm ease-soft-in tracking-tight-soft bg-gradient-to-tl from-purple-700 to-pink-500 bg-150 bg-x-25 border-purple-700 text-white hover:scale-102 hover:shadow-soft-xs active:opacity-85">
                                            <i class="fas fa-save mr-2"></i>
                                            Actualizar Asignatura
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

@extends('layouts.app')
@section('content')
    <div class="w-full px-6 py-6 mx-auto text-lg">
        <div class="container-fluid py-4">
            <div class="flex items-center justify-between">
                <h1 class="mb-0 text-2xl font-semibold text-slate-700">DETALLE DE LA ASIGNATURA</h1>
                <a href="{{ route('asignaturas.index') }}"
                    class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-slate-500 to-slate-600 rounded-lg hover:from-slate-600 hover:to-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-200 transition-all duration-200 shadow-sm hover:shadow-md">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </a>
            </div>
        </div>

        <div class="flex flex-wrap -mx-3">
            <div class="w-full px-3">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6">
                        <div class="text-center">
                            <div
                                class="w-24 h-24 mx-auto bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-book text-3xl text-white"></i>
                            </div>
                            <h5 class="text-xl font-semibold text-slate-700">{{ $asignatura->nombre }}</h5>
                            <p class="text-slate-500 mb-4">Código: {{ $asignatura->codigo }}</p>

                            <div class="text-sm text-slate-500 space-y-2">
                                <p><i class="fas fa-calendar-plus mr-2"></i>Creado:
                                    {{ $asignatura->created_at->format('d/m/Y H:i') }}</p>
                                <p><i class="fas fa-calendar-edit mr-2"></i>Actualizado:
                                    {{ $asignatura->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de estudiantes asignados -->
            <div class="w-full px-3 mt-6">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <h6 class="mb-0 text-xl font-semibold text-slate-700">
                            <i class="fas fa-users mr-2 text-slate-700"></i>
                            Estudiantes Asignados
                        </h6>
                    </div>
                    <div class="flex-auto p-6">
                        @if ($asignatura->users->count() > 0)
                            <ul class="space-y-2">
                                @foreach ($asignatura->users as $user)
                                    <li class="flex items-center justify-between p-4 bg-slate-50 rounded-lg shadow-sm">
                                        <span class="text-lg font-medium text-slate-700">{{ $user->name }}</span>
                                        <span class="text-sm text-slate-500">{{ $user->email }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-center text-slate-500">No hay estudiantes asignados a esta asignatura.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Asignar nuevos estudiantes -->
            <div class="w-full px-3 mt-6">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <h6 class="mb-0 text-xl font-semibold text-slate-700">
                            <i class="fas fa-user-graduate mr-2 text-slate-700"></i>
                            Asignar Estudiantes
                        </h6>
                    </div>
                    <div class="flex-auto p-6">
                        <form action="{{ route('asignaturas.assign-users', $asignatura->id) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="users" class="inline-block mb-2 ml-1 font-bold text-sm text-slate-700">
                                    Seleccione Estudiantes
                                </label>
                                <select id="users" name="users[]" multiple
                                    class="focus:shadow-soft-primary-outline text-lg leading-6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow">
                                    @foreach ($estudiantes->diff($asignatura->users) as $estudiante)
                                        <option value="{{ $estudiante->id }}">{{ $estudiante->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-center justify-end pt-4 space-x-4">
                                <button type="submit"
                                    class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-purple-700 to-pink-500 leading-pro text-sm ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                    <i class="fas fa-save mr-2"></i>
                                    Asignar Estudiantes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

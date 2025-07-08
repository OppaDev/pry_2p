@extends('layouts.app')

@section('content')
    @if(Auth::user()->hasRole('docente'))
        @include('notas.docente.index')
    @elseif(Auth::user()->hasRole('estudiante'))
        @include('notas.estudiante.index')
    @else
        <div class="w-full px-6 py-6 mx-auto text-lg">
            <div class="container-fluid py-4">
                <div class="flex flex-col items-center justify-center min-h-96">
                    <i class="fas fa-user-slash text-6xl text-red-400 mb-6"></i>
                    <h1 class="text-3xl font-bold text-slate-700 mb-4">Acceso Denegado</h1>
                    <p class="text-lg text-slate-500 text-center max-w-md">
                        No tienes los permisos necesarios para acceder al sistema de notas. 
                        Contacta al administrador para asignar un rol apropiado.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('dashboard') }}" 
                           class="inline-block px-6 py-3 font-bold text-center uppercase align-middle transition-all bg-transparent border border-solid rounded-lg shadow-none cursor-pointer leading-pro text-sm ease-soft-in tracking-tight-soft bg-gradient-to-tl from-slate-600 to-slate-300 bg-150 bg-x-25 border-slate-600 text-white hover:scale-102 hover:shadow-soft-xs active:opacity-85">
                            <i class="fas fa-home mr-2"></i>
                            Volver al Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

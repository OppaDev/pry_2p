@extends('layouts.app')
@section('content')
    <div class="w-full px-6 py-6 mx-auto text-lg">
        <div class="container-fluid py-4">
            <div class="flex items-center justify-between">
                <h1 class="mb-0 text-2xl font-semibold text-slate-700">PERFIL DE USUARIO</h1>
                <a href="{{ route('users.index') }}" 
                   class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-slate-500 to-slate-600 rounded-lg hover:from-slate-600 hover:to-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-200 transition-all duration-200 shadow-sm hover:shadow-md">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </a>
            </div>
        </div>

        <div class="flex flex-wrap -mx-3">
            <!-- Información del Usuario -->
            <div class="w-full lg:w-1/3 px-3 mb-6">
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6">
                        <div class="text-center">
                            <img src="../assets/img/team-2.jpg" 
                                 class="w-24 h-24 mx-auto rounded-full border-4 border-white shadow-lg mb-4" 
                                 alt="Usuario">
                            <h5 class="text-xl font-semibold text-slate-700">{{ $user->name }}</h5>
                            <p class="text-slate-500 mb-4">{{ $user->email }}</p>
                            
                            <div class="mb-4">
                                <span class="bg-gradient-to-tl {{ $user->email_verified_at ? 'from-green-600 to-lime-400' : 'from-slate-600 to-slate-300' }} px-3 py-1 text-sm rounded-full text-white font-semibold">
                                    {{ $user->email_verified_at ? 'Email Verificado' : 'Email No Verificado' }}
                                </span>
                            </div>
                            
                            <div class="text-sm text-slate-500 space-y-2">
                                <p><i class="fas fa-calendar-plus mr-2"></i>Creado: {{ $user->created_at->format('d/m/Y H:i') }}</p>
                                <p><i class="fas fa-calendar-edit mr-2"></i>Actualizado: {{ $user->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historial de Auditoría -->
            <div class="w-full lg:w-2/3 px-3">
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <div class="flex justify-between items-center">
                            <h6 class="mb-0 text-xl font-semibold text-slate-700">
                                <i class="fas fa-history mr-2 text-slate-700"></i>
                                HISTORIAL DE CAMBIOS
                            </h6>
                            <a href="{{ route('users.audit-history', $user->id) }}" 
                               class="px-3 py-1.5 text-sm font-semibold text-white bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg hover:from-indigo-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-200 transition-all duration-200 shadow-sm hover:shadow-md">
                                <i class="fas fa-external-link-alt mr-1"></i>
                                Ver Completo
                            </a>
                        </div>
                    </div>
                    
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-0 overflow-x-auto">
                            @if($audits->count() > 0)
                                <div class="space-y-4 p-6">
                                    @foreach($audits->take(5) as $audit)
                                        <div class="flex items-start space-x-4 p-4 bg-slate-50 rounded-lg">
                                            <div class="flex-shrink-0">
                                                @switch($audit->event)
                                                    @case('created')
                                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                            <i class="fas fa-plus text-green-600"></i>
                                                        </div>
                                                        @break
                                                    @case('updated')
                                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                            <i class="fas fa-edit text-blue-600"></i>
                                                        </div>
                                                        @break
                                                    @case('deleted')
                                                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                                            <i class="fas fa-trash text-red-600"></i>
                                                        </div>
                                                        @break
                                                    @case('restored')
                                                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                                            <i class="fas fa-undo text-yellow-600"></i>
                                                        </div>
                                                        @break
                                                @endswitch
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between">
                                                    <h4 class="text-sm font-semibold text-slate-700 capitalize">
                                                        {{ $audit->event == 'created' ? 'Creado' : 
                                                           ($audit->event == 'updated' ? 'Actualizado' : 
                                                           ($audit->event == 'deleted' ? 'Eliminado' : 'Restaurado')) }}
                                                    </h4>
                                                    <span class="text-xs text-slate-500">
                                                        {{ $audit->created_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                                <p class="text-sm text-slate-600 mb-2">
                                                    Por: {{ $audit->user ? $audit->user->name : 'Sistema' }}
                                                </p>
                                                @if($audit->new_values)
                                                    <div class="text-xs text-slate-500">
                                                        <strong>Cambios:</strong>
                                                        @foreach($audit->new_values as $key => $value)
                                                            @if($key !== 'password')
                                                                <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded mr-1 mb-1">
                                                                    {{ ucfirst($key) }}: {{ is_array($value) ? 'Array' : Str::limit($value, 30) }}
                                                                </span>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="flex flex-col items-center py-8">
                                    <i class="fas fa-history text-4xl text-slate-300 mb-4"></i>
                                    <p class="text-slate-500 text-lg">No hay historial de cambios disponible</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

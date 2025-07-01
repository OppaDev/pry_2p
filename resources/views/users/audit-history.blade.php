@extends('layouts.app')
@section('content')
    <div class="w-full px-6 py-6 mx-auto text-lg">
        <div class="container-fluid py-4">
            <div class="flex items-center justify-between">
                <h1 class="mb-0 text-2xl font-semibold text-slate-700">HISTORIAL DE AUDITORÍA</h1>
                <div class="flex space-x-2">
                    <a href="{{ route('users.show', $user->id) }}" 
                       class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg hover:from-indigo-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-200 transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class="fas fa-user mr-2"></i>
                        Ver Perfil
                    </a>
                    <a href="{{ route('users.index') }}" 
                       class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-slate-500 to-slate-600 rounded-lg hover:from-slate-600 hover:to-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-200 transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver
                    </a>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap -mx-3">
            <!-- Información del Usuario (pequeña) -->
            <div class="w-full px-3 mb-4">
                <div class="flex items-center p-4 bg-gradient-to-r from-indigo-50 to-blue-50 rounded-xl border border-indigo-200/60">
                    <img src="../assets/img/team-2.jpg" 
                         class="w-12 h-12 rounded-full border-2 border-white shadow-sm mr-4" 
                         alt="Usuario">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-700">{{ $user->name }}</h3>
                        <p class="text-slate-600">{{ $user->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Historial Completo -->
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <div class="flex justify-between items-center">
                            <h6 class="mb-0 text-xl font-semibold text-slate-700">
                                <i class="fas fa-history mr-2 text-slate-700"></i>
                                HISTORIAL COMPLETO DE CAMBIOS
                            </h6>
                            <div class="flex items-center space-x-3">
                                <!-- Filtros -->
                                <form method="GET" action="{{ route('users.audit-history', $user->id) }}" class="flex items-center space-x-2">
                                    <input type="hidden" name="per_page" value="{{ $perPage }}">
                                    
                                    <div class="flex items-center space-x-2 bg-gradient-to-r from-slate-50 to-slate-100 px-4 py-2 rounded-xl border border-slate-200/60 shadow-sm">
                                        <label for="event" class="text-sm font-medium text-slate-600">
                                            <i class="fas fa-filter mr-1"></i>
                                            Evento:
                                        </label>
                                        <select id="event" name="event" onchange="this.form.submit()"
                                            class="px-3 py-1 text-sm bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-200">
                                            <option value="">Todos</option>
                                            <option value="created" {{ $eventFilter == 'created' ? 'selected' : '' }}>Creado</option>
                                            <option value="updated" {{ $eventFilter == 'updated' ? 'selected' : '' }}>Actualizado</option>
                                            <option value="deleted" {{ $eventFilter == 'deleted' ? 'selected' : '' }}>Eliminado</option>
                                            <option value="restored" {{ $eventFilter == 'restored' ? 'selected' : '' }}>Restaurado</option>
                                        </select>
                                    </div>
                                    
                                    <div class="flex items-center space-x-2 bg-gradient-to-r from-slate-50 to-slate-100 px-4 py-2 rounded-xl border border-slate-200/60 shadow-sm">
                                        <label for="per_page" class="text-sm font-medium text-slate-600">
                                            <i class="fas fa-eye mr-1"></i>
                                            Mostrar:
                                        </label>
                                        <select id="per_page" name="per_page" onchange="this.form.submit()"
                                            class="px-3 py-1 text-sm bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-200">
                                            <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                            <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
                                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                        </select>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        @if($eventFilter)
                            <div class="relative w-full p-3 text-indigo-700 bg-indigo-100 border border-indigo-300 rounded-lg mt-4">
                                <div class="flex items-center">
                                    <i class="fas fa-filter mr-2"></i>
                                    <span>Filtrando por: <strong>{{ ucfirst($eventFilter) }}</strong></span>
                                    <a href="{{ route('users.audit-history', $user->id) }}" class="ml-2 text-indigo-600 hover:text-indigo-800">
                                        <i class="fas fa-times"></i> Limpiar filtro
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-0 overflow-x-auto">
                            @if($audits->count() > 0)
                                <div class="space-y-4 p-6">
                                    @foreach($audits as $audit)
                                        <div class="relative p-6 bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200">
                                            <!-- Header del evento -->
                                            <div class="flex items-start justify-between mb-4">
                                                <div class="flex items-center space-x-3">
                                                    @switch($audit->event)
                                                        @case('created')
                                                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                                                <i class="fas fa-plus text-green-600"></i>
                                                            </div>
                                                            <div>
                                                                <h4 class="text-lg font-semibold text-slate-700">Usuario Creado</h4>
                                                                <p class="text-sm text-slate-500">Se registró un nuevo usuario</p>
                                                            </div>
                                                            @break
                                                        @case('updated')
                                                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                                <i class="fas fa-edit text-blue-600"></i>
                                                            </div>
                                                            <div>
                                                                <h4 class="text-lg font-semibold text-slate-700">Usuario Actualizado</h4>
                                                                <p class="text-sm text-slate-500">Se modificaron los datos del usuario</p>
                                                            </div>
                                                            @break
                                                        @case('deleted')
                                                            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                                                <i class="fas fa-trash text-red-600"></i>
                                                            </div>
                                                            <div>
                                                                <h4 class="text-lg font-semibold text-slate-700">Usuario Eliminado</h4>
                                                                <p class="text-sm text-slate-500">El usuario fue eliminado</p>
                                                            </div>
                                                            @break
                                                        @case('restored')
                                                            <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                                                                <i class="fas fa-undo text-yellow-600"></i>
                                                            </div>
                                                            <div>
                                                                <h4 class="text-lg font-semibold text-slate-700">Usuario Restaurado</h4>
                                                                <p class="text-sm text-slate-500">El usuario fue restaurado</p>
                                                            </div>
                                                            @break
                                                    @endswitch
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-sm text-slate-600 font-medium">
                                                        {{ $audit->user ? $audit->user->name : 'Sistema' }}
                                                    </p>
                                                    <p class="text-xs text-slate-500">
                                                        {{ $audit->created_at->format('d/m/Y H:i:s') }}
                                                    </p>
                                                    <p class="text-xs text-slate-400">
                                                        {{ $audit->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Motivo (si existe) -->
                                            @if(in_array($audit->event, ['deleted', 'restored']) && $audit->tags)
                                                @php
                                                    $motivo = null;
                                                    $tags = $audit->tags;
                                                    
                                                    // Si tags es un string JSON, decodificarlo
                                                    if (is_string($tags)) {
                                                        $tags = json_decode($tags, true) ?? [];
                                                    }
                                                    
                                                    // Buscar el motivo en los tags
                                                    if (is_array($tags)) {
                                                        foreach ($tags as $tag) {
                                                            if (is_string($tag) && str_starts_with($tag, 'motivo:')) {
                                                                $motivo = substr($tag, 7); // Remove 'motivo:' prefix
                                                                break;
                                                            }
                                                        }
                                                    }
                                                @endphp
                                                @if($motivo)
                                                    <div class="mb-4 p-4 bg-slate-50 rounded-lg border border-slate-200">
                                                        <div class="flex items-start space-x-2">
                                                            <i class="fas fa-comment-alt text-slate-500 mt-1"></i>
                                                            <div>
                                                                <h6 class="text-sm font-semibold text-slate-700 mb-1">
                                                                    Motivo de {{ $audit->event == 'deleted' ? 'eliminación' : 'restauración' }}:
                                                                </h6>
                                                                <p class="text-sm text-slate-600 italic">
                                                                    "{{ $motivo }}"
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif

                                            <!-- Detalles de los cambios -->
                                            @if($audit->old_values || $audit->new_values)
                                                <div class="border-t border-slate-100 pt-4">
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        @if($audit->old_values && $audit->event == 'updated')
                                                            <div>
                                                                <h5 class="text-sm font-semibold text-slate-600 mb-2">
                                                                    <i class="fas fa-arrow-left mr-1 text-red-500"></i>
                                                                    Valores Anteriores
                                                                </h5>
                                                                <div class="space-y-1">
                                                                    @foreach($audit->old_values as $key => $value)
                                                                        @if($key !== 'password')
                                                                            <div class="flex justify-between text-sm">
                                                                                <span class="font-medium text-slate-600">{{ ucfirst($key) }}:</span>
                                                                                <span class="text-slate-500 bg-red-50 px-2 py-1 rounded">
                                                                                    {{ is_array($value) ? 'Array' : ($value ?: 'Vacío') }}
                                                                                </span>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif
                                                        
                                                        @if($audit->new_values)
                                                            <div>
                                                                <h5 class="text-sm font-semibold text-slate-600 mb-2">
                                                                    <i class="fas fa-arrow-right mr-1 text-green-500"></i>
                                                                    {{ $audit->event == 'created' ? 'Valores Iniciales' : 'Nuevos Valores' }}
                                                                </h5>
                                                                <div class="space-y-1">
                                                                    @foreach($audit->new_values as $key => $value)
                                                                        @if($key !== 'password')
                                                                            <div class="flex justify-between text-sm">
                                                                                <span class="font-medium text-slate-600">{{ ucfirst($key) }}:</span>
                                                                                <span class="text-slate-700 bg-green-50 px-2 py-1 rounded font-medium">
                                                                                    {{ is_array($value) ? 'Array' : ($value ?: 'Vacío') }}
                                                                                </span>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Información adicional -->
                                            @if($audit->ip_address || $audit->user_agent)
                                                <div class="border-t border-slate-100 pt-3 mt-3">
                                                    <div class="flex flex-wrap items-center text-xs text-slate-500 space-x-4">
                                                        @if($audit->ip_address)
                                                            <span>
                                                                <i class="fas fa-globe mr-1"></i>
                                                                IP: {{ $audit->ip_address }}
                                                            </span>
                                                        @endif
                                                        @if($audit->user_agent)
                                                            <span>
                                                                <i class="fas fa-desktop mr-1"></i>
                                                                {{ Str::limit($audit->user_agent, 50) }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Paginación -->
                                <div class="flex items-center mt-6 px-6">
                                    <div class="w-full max-w-4xl">
                                        {{ $audits->appends(request()->input())->links('pagination::tailwind') }}
                                    </div>
                                </div>
                            @else
                                <div class="flex flex-col items-center py-12">
                                    <i class="fas fa-history text-6xl text-slate-300 mb-4"></i>
                                    <h3 class="text-xl font-semibold text-slate-600 mb-2">No hay historial disponible</h3>
                                    <p class="text-slate-500 text-center">
                                        @if($eventFilter)
                                            No se encontraron eventos de tipo "{{ ucfirst($eventFilter) }}" para este usuario.
                                        @else
                                            Este usuario aún no tiene historial de cambios registrado.
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')
@section('content')
    <div class="w-full px-6 py-6 mx-auto text-lg">
        <div class="container-fluid py-4">
            <div class="flex items-center justify-between mb-6">
                <h1 class="mb-0 text-2xl font-semibold text-slate-700">DETALLE DE AUDITORÍA</h1>
                <div class="flex space-x-2">
                    <a href="{{ route('audits.by-user') }}" 
                       class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-slate-500 to-slate-600 rounded-lg hover:from-slate-600 hover:to-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-200 transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver a Auditorías
                    </a>
                </div>
            </div>

            <div class="flex flex-wrap -mx-3">
                <div class="w-full max-w-full px-3">
                    <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                        <div class="flex-auto p-6">
                            
                            <!-- Header del evento -->
                            <div class="flex items-center justify-between mb-6 p-4 bg-gradient-to-r from-slate-50 to-slate-100 rounded-xl">
                                <div class="flex items-center space-x-4">
                                    @switch($audit->event)
                                        @case('created')
                                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-plus text-green-600 text-lg"></i>
                                            </div>
                                            <div>
                                                <h3 class="text-xl font-semibold text-slate-700">Registro Creado</h3>
                                                <p class="text-slate-500">Se creó un nuevo registro en el sistema</p>
                                            </div>
                                            @break
                                        @case('updated')
                                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-edit text-blue-600 text-lg"></i>
                                            </div>
                                            <div>
                                                <h3 class="text-xl font-semibold text-slate-700">Registro Actualizado</h3>
                                                <p class="text-slate-500">Se modificó un registro existente</p>
                                            </div>
                                            @break
                                        @case('deleted')
                                            <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-trash text-orange-600 text-lg"></i>
                                            </div>
                                            <div>
                                                <h3 class="text-xl font-semibold text-slate-700">Registro Eliminado</h3>
                                                <p class="text-slate-500">Se eliminó un registro (soft delete)</p>
                                            </div>
                                            @break
                                        @case('restored')
                                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-undo text-purple-600 text-lg"></i>
                                            </div>
                                            <div>
                                                <h3 class="text-xl font-semibold text-slate-700">Registro Restaurado</h3>
                                                <p class="text-slate-500">Se restauró un registro eliminado</p>
                                            </div>
                                            @break
                                        @case('force_deleted')
                                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-times text-red-600 text-lg"></i>
                                            </div>
                                            <div>
                                                <h3 class="text-xl font-semibold text-slate-700">Eliminación Permanente</h3>
                                                <p class="text-slate-500">Se eliminó permanentemente un registro</p>
                                            </div>
                                            @break
                                        @default
                                            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-question text-gray-600 text-lg"></i>
                                            </div>
                                            <div>
                                                <h3 class="text-xl font-semibold text-slate-700">{{ ucfirst($audit->event) }}</h3>
                                                <p class="text-slate-500">Evento de auditoría</p>
                                            </div>
                                    @endswitch
                                </div>
                                
                                <div class="text-right">
                                    <p class="text-sm font-medium text-slate-600">ID de Auditoría</p>
                                    <p class="text-lg font-bold text-slate-700">#{{ $audit->id }}</p>
                                </div>
                            </div>

                            <!-- Información del evento -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                
                                <!-- Usuario que realizó la acción -->
                                <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl">
                                    <h4 class="text-lg font-semibold text-slate-700 mb-3">
                                        <i class="fas fa-user mr-2 text-blue-600"></i>
                                        Usuario que realizó la acción
                                    </h4>
                                    @if($audit->user)
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full flex items-center justify-center">
                                                <span class="text-white font-bold">{{ substr($audit->user->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-slate-700">{{ $audit->user->name }}</p>
                                                <p class="text-sm text-slate-500">{{ $audit->user->email }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-gray-400 rounded-full flex items-center justify-center">
                                                <i class="fas fa-robot text-white"></i>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-slate-500">Sistema</p>
                                                <p class="text-sm text-slate-400">Usuario no disponible</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Modelo afectado -->
                                <div class="p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl">
                                    <h4 class="text-lg font-semibold text-slate-700 mb-3">
                                        <i class="fas fa-database mr-2 text-purple-600"></i>
                                        Modelo afectado
                                    </h4>
                                    @if($audit->auditable_type === 'App\Models\User')
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-slate-700">Usuario</p>
                                                <p class="text-sm text-slate-500">ID: {{ $audit->auditable_id }}</p>
                                                @if($auditableModel)
                                                    <p class="text-sm text-slate-600">{{ $auditableModel->name ?? 'Nombre no disponible' }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @elseif($audit->auditable_type === 'App\Models\Producto')
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center">
                                                <i class="fas fa-box text-white"></i>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-slate-700">Producto</p>
                                                <p class="text-sm text-slate-500">ID: {{ $audit->auditable_id }}</p>
                                                @if($auditableModel)
                                                    <p class="text-sm text-slate-600">{{ $auditableModel->nombre ?? 'Nombre no disponible' }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-gray-400 rounded-full flex items-center justify-center">
                                                <i class="fas fa-question text-white"></i>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-slate-500">Modelo desconocido</p>
                                                <p class="text-sm text-slate-400">{{ $audit->auditable_type }}</p>
                                                <p class="text-sm text-slate-400">ID: {{ $audit->auditable_id }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Información de fecha y contexto -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl text-center">
                                    <i class="fas fa-calendar text-2xl text-gray-600 mb-2"></i>
                                    <p class="text-sm text-slate-500">Fecha</p>
                                    <p class="font-semibold text-slate-700">{{ $audit->created_at->format('d/m/Y H:i:s') }}</p>
                                </div>
                                
                                @if($audit->ip_address)
                                <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl text-center">
                                    <i class="fas fa-globe text-2xl text-gray-600 mb-2"></i>
                                    <p class="text-sm text-slate-500">Dirección IP</p>
                                    <p class="font-semibold text-slate-700">{{ $audit->ip_address }}</p>
                                </div>
                                @endif
                                
                                @if($audit->url)
                                <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl text-center">
                                    <i class="fas fa-link text-2xl text-gray-600 mb-2"></i>
                                    <p class="text-sm text-slate-500">URL</p>
                                    <p class="font-semibold text-slate-700 truncate">{{ $audit->url }}</p>
                                </div>
                                @endif
                            </div>

                            <!-- Motivo (si existe) -->
                            @if($audit->tags)
                                @php
                                    $tags = is_string($audit->tags) ? json_decode($audit->tags, true) : $audit->tags;
                                    $motivoTag = collect($tags)->first(function ($tag) {
                                        return str_starts_with($tag, 'motivo:');
                                    });
                                @endphp
                                @if($motivoTag)
                                    <div class="mb-6 p-4 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl border-l-4 border-yellow-400">
                                        <h4 class="text-lg font-semibold text-slate-700 mb-2">
                                            <i class="fas fa-info-circle mr-2 text-yellow-600"></i>
                                            Motivo de la acción
                                        </h4>
                                        <p class="text-slate-600">{{ str_replace('motivo:', '', $motivoTag) }}</p>
                                    </div>
                                @endif
                            @endif

                            <!-- Cambios realizados -->
                            @if($audit->old_values || $audit->new_values)
                                <div class="space-y-6">
                                    <h4 class="text-lg font-semibold text-slate-700 border-b border-slate-200 pb-2">
                                        <i class="fas fa-exchange-alt mr-2 text-slate-600"></i>
                                        Cambios realizados
                                    </h4>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Valores anteriores -->
                                        @if($audit->old_values && $audit->event == 'updated')
                                            <div class="p-4 bg-gradient-to-r from-red-50 to-pink-50 rounded-xl">
                                                <h5 class="font-semibold text-slate-700 mb-3">
                                                    <i class="fas fa-arrow-left mr-2 text-red-500"></i>
                                                    Valores Anteriores
                                                </h5>
                                                <div class="space-y-2">
                                                    @foreach($audit->old_values as $key => $value)
                                                        @if($key !== 'password')
                                                            <div class="flex justify-between items-center p-2 bg-white rounded border">
                                                                <span class="font-medium text-slate-600">{{ ucfirst($key) }}</span>
                                                                <span class="text-slate-700 bg-red-100 px-2 py-1 rounded text-sm">
                                                                    {{ is_array($value) ? 'Array' : ($value ?: 'Vacío') }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                        
                                        <!-- Nuevos valores -->
                                        @if($audit->new_values)
                                            <div class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl">
                                                <h5 class="font-semibold text-slate-700 mb-3">
                                                    <i class="fas fa-arrow-right mr-2 text-green-500"></i>
                                                    {{ $audit->event == 'created' ? 'Valores Iniciales' : 'Nuevos Valores' }}
                                                </h5>
                                                <div class="space-y-2">
                                                    @foreach($audit->new_values as $key => $value)
                                                        @if($key !== 'password')
                                                            <div class="flex justify-between items-center p-2 bg-white rounded border">
                                                                <span class="font-medium text-slate-600">{{ ucfirst($key) }}</span>
                                                                <span class="text-slate-700 bg-green-100 px-2 py-1 rounded text-sm font-medium">
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

                            <!-- User Agent -->
                            @if($audit->user_agent)
                                <div class="mt-6 p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                                    <h4 class="text-lg font-semibold text-slate-700 mb-2">
                                        <i class="fas fa-desktop mr-2 text-gray-600"></i>
                                        Información del navegador
                                    </h4>
                                    <p class="text-sm text-slate-600 font-mono bg-white p-2 rounded border">{{ $audit->user_agent }}</p>
                                </div>
                            @endif

                            <!-- Todas las etiquetas -->
                            @if($audit->tags)
                                @php
                                    $tags = is_string($audit->tags) ? json_decode($audit->tags, true) : $audit->tags;
                                @endphp
                                @if($tags && count($tags) > 0)
                                    <div class="mt-6 p-4 bg-gradient-to-r from-indigo-50 to-blue-50 rounded-xl">
                                        <h4 class="text-lg font-semibold text-slate-700 mb-3">
                                            <i class="fas fa-tags mr-2 text-indigo-600"></i>
                                            Etiquetas adicionales
                                        </h4>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($tags as $tag)
                                                <span class="px-3 py-1 text-sm font-medium bg-indigo-100 text-indigo-800 rounded-full">
                                                    {{ $tag }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

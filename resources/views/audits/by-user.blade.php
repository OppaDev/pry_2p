@extends('layouts.app')
@section('content')
    <div class="w-full px-6 py-6 mx-auto text-lg">
        <div class="container-fluid py-4">
            <!-- Header con estadísticas -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="mb-2 text-2xl font-semibold text-slate-700">AUDITORÍAS POR USUARIO</h1>
                    <p class="text-slate-500">Historial completo de todas las actividades del sistema</p>
                </div>
                
                <!-- Estadísticas rápidas -->
                <div class="flex space-x-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ number_format($stats['total_audits']) }}</div>
                        <div class="text-sm text-slate-500">Total Auditorías</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">{{ number_format($stats['total_users_with_audits']) }}</div>
                        <div class="text-sm text-slate-500">Usuarios Activos</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600">{{ number_format($stats['recent_activity']) }}</div>
                        <div class="text-sm text-slate-500">Última Semana</div>
                    </div>
                </div>
            </div>

            <!-- Gráfico de eventos -->
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full max-w-full px-3 mb-6 md:mb-0 md:w-full">
                    <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                        <div class="flex-auto p-6">
                            <div class="flex flex-wrap -mx-3">
                                <div class="w-full max-w-full px-3 mb-6 md:mb-0 md:w-1/2">
                                    <h6 class="mb-4 text-lg font-semibold text-slate-700">Distribución de Eventos</h6>
                                    <div class="space-y-2">
                                        @foreach($stats['events_count'] as $event => $count)
                                            <div class="flex items-center justify-between p-3 bg-gradient-to-r from-slate-50 to-slate-100 rounded-lg">
                                                <div class="flex items-center space-x-3">
                                                    @switch($event)
                                                        @case('created')
                                                            <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                                                            <span class="font-medium text-slate-700">Creados</span>
                                                            @break
                                                        @case('updated')
                                                            <div class="w-4 h-4 bg-blue-500 rounded-full"></div>
                                                            <span class="font-medium text-slate-700">Actualizados</span>
                                                            @break
                                                        @case('deleted')
                                                            <div class="w-4 h-4 bg-orange-500 rounded-full"></div>
                                                            <span class="font-medium text-slate-700">Eliminados</span>
                                                            @break
                                                        @case('restored')
                                                            <div class="w-4 h-4 bg-purple-500 rounded-full"></div>
                                                            <span class="font-medium text-slate-700">Restaurados</span>
                                                            @break
                                                        @case('force_deleted')
                                                            <div class="w-4 h-4 bg-red-500 rounded-full"></div>
                                                            <span class="font-medium text-slate-700">Eliminados Permanentemente</span>
                                                            @break
                                                        @default
                                                            <div class="w-4 h-4 bg-gray-500 rounded-full"></div>
                                                            <span class="font-medium text-slate-700">{{ ucfirst($event) }}</span>
                                                    @endswitch
                                                </div>
                                                <span class="text-xl font-bold text-slate-600">{{ number_format($count) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="w-full max-w-full px-3 md:w-1/2">
                                    <h6 class="mb-4 text-lg font-semibold text-slate-700">Actividad Reciente</h6>
                                    <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg">
                                        <div class="text-center">
                                            <div class="text-3xl font-bold text-indigo-600 mb-2">{{ number_format($stats['recent_activity']) }}</div>
                                            <p class="text-slate-600">Auditorías en los últimos 7 días</p>
                                            <div class="mt-3 text-sm text-slate-500">
                                                <i class="fas fa-chart-line mr-1"></i>
                                                Promedio: {{ number_format($stats['recent_activity'] / 7, 1) }} por día
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de auditorías -->
            <div class="flex flex-wrap -mx-3">
                <div class="w-full max-w-full px-3">
                    <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                        <div class="flex-auto px-0 pt-0 pb-2">
                            
                            <!-- Filtros -->
                            <div class="p-6 pb-0">
                                <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                                    <div class="flex items-center space-x-4">
                                        <h6 class="mb-0 text-lg font-semibold text-slate-700">Historial de Auditorías</h6>
                                        <span class="px-3 py-1 text-sm font-medium bg-blue-100 text-blue-800 rounded-full">
                                            {{ $audits->total() }} registros
                                        </span>
                                    </div>
                                    
                                    <!-- Filtros por página -->
                                    <div class="flex items-center space-x-2">
                                        <form method="GET" action="{{ route('audits.by-user') }}" class="flex items-center space-x-2">
                                            <input type="hidden" name="search" value="{{ $search }}">
                                            <input type="hidden" name="event" value="{{ $eventFilter }}">
                                            <input type="hidden" name="auditable_type" value="{{ $auditableTypeFilter }}">
                                            
                                            <label for="per_page" class="text-sm font-medium text-slate-600">Mostrar:</label>
                                            <select id="per_page" name="per_page" onchange="this.form.submit()"
                                                class="px-3 py-1 text-sm bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                                                <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                                <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
                                                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                            </select>
                                        </form>
                                    </div>
                                </div>
                                
                                <!-- Filtros avanzados -->
                                <div class="flex flex-wrap items-center gap-4 mb-4">
                                    <!-- Búsqueda -->
                                    <form method="GET" action="{{ route('audits.by-user') }}" class="flex items-center">
                                        <input type="hidden" name="per_page" value="{{ $perPage }}">
                                        <input type="hidden" name="event" value="{{ $eventFilter }}">
                                        <input type="hidden" name="auditable_type" value="{{ $auditableTypeFilter }}">
                                        
                                        <div class="relative">
                                            <input type="text" name="search" value="{{ $search }}" 
                                                placeholder="Buscar usuarios, emails, productos..."
                                                class="w-64 px-4 py-2 pl-10 text-sm bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                                            <i class="fas fa-search absolute left-3 top-3 text-slate-400"></i>
                                        </div>
                                        <button type="submit" class="ml-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200 transition-colors">
                                            <i class="fas fa-search mr-1"></i>
                                            Buscar
                                        </button>
                                    </form>
                                    
                                    <!-- Filtro por evento -->
                                    <form method="GET" action="{{ route('audits.by-user') }}" class="flex items-center">
                                        <input type="hidden" name="per_page" value="{{ $perPage }}">
                                        <input type="hidden" name="search" value="{{ $search }}">
                                        <input type="hidden" name="auditable_type" value="{{ $auditableTypeFilter }}">
                                        
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
                                                <option value="force_deleted" {{ $eventFilter == 'force_deleted' ? 'selected' : '' }}>Eliminado Permanentemente</option>
                                            </select>
                                        </div>
                                    </form>
                                    
                                    <!-- Filtro por tipo de modelo -->
                                    <form method="GET" action="{{ route('audits.by-user') }}" class="flex items-center">
                                        <input type="hidden" name="per_page" value="{{ $perPage }}">
                                        <input type="hidden" name="search" value="{{ $search }}">
                                        <input type="hidden" name="event" value="{{ $eventFilter }}">
                                        
                                        <div class="flex items-center space-x-2 bg-gradient-to-r from-slate-50 to-slate-100 px-4 py-2 rounded-xl border border-slate-200/60 shadow-sm">
                                            <label for="auditable_type" class="text-sm font-medium text-slate-600">
                                                <i class="fas fa-cube mr-1"></i>
                                                Tipo:
                                            </label>
                                            <select id="auditable_type" name="auditable_type" onchange="this.form.submit()"
                                                class="px-3 py-1 text-sm bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-200">
                                                <option value="">Todos</option>
                                                <option value="App\Models\User" {{ $auditableTypeFilter == 'App\Models\User' ? 'selected' : '' }}>Usuarios</option>
                                                <option value="App\Models\Producto" {{ $auditableTypeFilter == 'App\Models\Producto' ? 'selected' : '' }}>Productos</option>
                                            </select>
                                        </div>
                                    </form>
                                    
                                    <!-- Limpiar filtros -->
                                    @if($search || $eventFilter || $auditableTypeFilter)
                                        <div class="flex items-center">
                                            <a href="{{ route('audits.by-user') }}" class="ml-2 text-red-600 hover:text-red-800 text-sm">
                                                <i class="fas fa-times"></i> Limpiar filtros
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Tabla de auditorías -->
                            <div class="overflow-x-auto">
                                @if($audits->count() > 0)
                                    <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                                        <thead class="align-bottom">
                                            <tr>
                                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                                    Evento
                                                </th>
                                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                                    Usuario que realizó
                                                </th>
                                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                                    Modelo afectado
                                                </th>
                                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                                    Fecha
                                                </th>
                                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                                    Detalles
                                                </th>
                                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                                    Acciones
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($audits as $audit)
                                                <tr class="hover:bg-gray-50 transition-colors">
                                                    <!-- Evento -->
                                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                        <div class="flex items-center px-2 py-1">
                                                            @switch($audit->event)
                                                                @case('created')
                                                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                                                    <span class="text-sm font-medium text-green-700 bg-green-100 px-2 py-1 rounded">Creado</span>
                                                                    @break
                                                                @case('updated')
                                                                    <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                                                                    <span class="text-sm font-medium text-blue-700 bg-blue-100 px-2 py-1 rounded">Actualizado</span>
                                                                    @break
                                                                @case('deleted')
                                                                    <div class="w-3 h-3 bg-orange-500 rounded-full mr-2"></div>
                                                                    <span class="text-sm font-medium text-orange-700 bg-orange-100 px-2 py-1 rounded">Eliminado</span>
                                                                    @break
                                                                @case('restored')
                                                                    <div class="w-3 h-3 bg-purple-500 rounded-full mr-2"></div>
                                                                    <span class="text-sm font-medium text-purple-700 bg-purple-100 px-2 py-1 rounded">Restaurado</span>
                                                                    @break
                                                                @case('force_deleted')
                                                                    <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                                                                    <span class="text-sm font-medium text-red-700 bg-red-100 px-2 py-1 rounded">Eliminado Permanentemente</span>
                                                                    @break
                                                                @default
                                                                    <div class="w-3 h-3 bg-gray-500 rounded-full mr-2"></div>
                                                                    <span class="text-sm font-medium text-gray-700 bg-gray-100 px-2 py-1 rounded">{{ ucfirst($audit->event) }}</span>
                                                            @endswitch
                                                        </div>
                                                    </td>
                                                    
                                                    <!-- Usuario que realizó la acción -->
                                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                        <div class="flex items-center px-2 py-1">
                                                            @if($audit->user)
                                                                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full flex items-center justify-center mr-3">
                                                                    <span class="text-white text-xs font-bold">{{ substr($audit->user->name, 0, 1) }}</span>
                                                                </div>
                                                                <div>
                                                                    <p class="mb-0 text-sm font-semibold leading-tight text-slate-700">{{ $audit->user->name }}</p>
                                                                    <p class="mb-0 text-xs leading-tight text-slate-400">{{ $audit->user->email }}</p>
                                                                </div>
                                                            @else
                                                                <div class="w-8 h-8 bg-gray-400 rounded-full flex items-center justify-center mr-3">
                                                                    <i class="fas fa-user text-white text-xs"></i>
                                                                </div>
                                                                <div>
                                                                    <p class="mb-0 text-sm font-semibold leading-tight text-slate-500">Sistema</p>
                                                                    <p class="mb-0 text-xs leading-tight text-slate-400">Usuario no disponible</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    
                                                    <!-- Modelo afectado -->
                                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                        <div class="flex items-center px-2 py-1">
                                                            @if($audit->auditable_type === 'App\Models\User')
                                                                <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center mr-3">
                                                                    <i class="fas fa-user text-white text-xs"></i>
                                                                </div>
                                                                <div>
                                                                    <p class="mb-0 text-sm font-semibold leading-tight text-slate-700">
                                                                        {{ $audit->auditable_user_name ?? 'Usuario eliminado' }}
                                                                    </p>
                                                                    <p class="mb-0 text-xs leading-tight text-slate-400">
                                                                        {{ $audit->auditable_user_email ?? 'Email no disponible' }}
                                                                    </p>
                                                                </div>
                                                            @elseif($audit->auditable_type === 'App\Models\Producto')
                                                                <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center mr-3">
                                                                    <i class="fas fa-box text-white text-xs"></i>
                                                                </div>
                                                                <div>
                                                                    <p class="mb-0 text-sm font-semibold leading-tight text-slate-700">
                                                                        {{ $audit->auditable_producto_nombre ?? 'Producto eliminado' }}
                                                                    </p>
                                                                    <p class="mb-0 text-xs leading-tight text-slate-400">
                                                                        {{ $audit->auditable_producto_codigo ?? 'Código no disponible' }}
                                                                    </p>
                                                                </div>
                                                            @else
                                                                <div class="w-8 h-8 bg-gray-400 rounded-full flex items-center justify-center mr-3">
                                                                    <i class="fas fa-question text-white text-xs"></i>
                                                                </div>
                                                                <div>
                                                                    <p class="mb-0 text-sm font-semibold leading-tight text-slate-500">Modelo desconocido</p>
                                                                    <p class="mb-0 text-xs leading-tight text-slate-400">ID: {{ $audit->auditable_id }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    
                                                    <!-- Fecha -->
                                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                        <div class="px-2 py-1">
                                                            <p class="mb-0 text-sm font-semibold leading-tight text-slate-700">
                                                                {{ $audit->created_at->format('d/m/Y') }}
                                                            </p>
                                                            <p class="mb-0 text-xs leading-tight text-slate-400">
                                                                {{ $audit->created_at->format('H:i:s') }}
                                                            </p>
                                                        </div>
                                                    </td>
                                                    
                                                    <!-- Detalles -->
                                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                        <div class="px-2 py-1">
                                                            @if($audit->tags)
                                                                @php
                                                                    $tags = is_string($audit->tags) ? json_decode($audit->tags, true) : $audit->tags;
                                                                    $motivoTag = collect($tags)->first(function ($tag) {
                                                                        return str_starts_with($tag, 'motivo:');
                                                                    });
                                                                @endphp
                                                                @if($motivoTag)
                                                                    <span class="text-xs font-medium text-indigo-700 bg-indigo-100 px-2 py-1 rounded">
                                                                        <i class="fas fa-info-circle mr-1"></i>
                                                                        {{ str_replace('motivo:', '', $motivoTag) }}
                                                                    </span>
                                                                @endif
                                                            @endif
                                                            
                                                            @if($audit->ip_address)
                                                                <div class="mt-1">
                                                                    <span class="text-xs text-slate-500">
                                                                        <i class="fas fa-globe mr-1"></i>
                                                                        {{ $audit->ip_address }}
                                                                    </span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    
                                                    <!-- Acciones -->
                                                    <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                        <a href="{{ route('audits.show', $audit->id) }}" 
                                                           class="inline-flex items-center px-3 py-1 text-xs font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200 transition-all duration-200 shadow-sm hover:shadow-md">
                                                            <i class="fas fa-eye mr-1"></i>
                                                            Ver Detalles
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    
                                    <!-- Paginación -->
                                    <div class="flex items-center mt-6 px-6">
                                        <div class="w-full max-w-4xl">
                                            {{ $audits->appends(request()->input())->links('pagination::tailwind') }}
                                        </div>
                                    </div>
                                @else
                                    <div class="flex flex-col items-center py-12">
                                        <i class="fas fa-history text-6xl text-slate-300 mb-4"></i>
                                        <p class="text-xl text-slate-500 mb-2">No hay auditorías disponibles</p>
                                        <p class="text-slate-400">Los filtros aplicados no devolvieron resultados</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

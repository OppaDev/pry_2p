@extends('layouts.app')
@section('content')
    <div class="w-full px-6 py-6 mx-auto text-lg">
        <div class="container-fluid py-4">
            <div class="flex items-center justify-between">
                <h1 class="mb-0 text-2xl font-semibold text-slate-700">PERFIL DE USUARIO</h1>
                <div class="flex space-x-2">
                    @can('usuarios.asignar_rol')
                    <a href="{{ route('users.edit-roles', $user->id) }}" 
                       class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-orange-500 to-yellow-500 rounded-lg hover:from-orange-600 hover:to-yellow-600 focus:outline-none focus:ring-2 focus:ring-orange-200 transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class="fas fa-user-tag mr-2"></i>
                        Gestionar Roles
                    </a>
                    @endcan
                    <a href="{{ route('users.index') }}" 
                       class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-slate-500 to-slate-600 rounded-lg hover:from-slate-600 hover:to-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-200 transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver
                    </a>
                </div>
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

                <!-- Roles Asignados -->
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border mt-6">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <div class="flex items-center justify-between">
                            <h6 class="mb-0 text-lg font-semibold text-slate-700">
                                <i class="fas fa-shield-alt mr-2 text-slate-700"></i>
                                ROLES
                            </h6>
                            @can('usuarios.asignar_rol')
                            <a href="{{ route('users.edit-roles', $user->id) }}" 
                               class="px-3 py-1.5 text-xs font-semibold text-white bg-gradient-to-r from-orange-500 to-yellow-500 rounded-lg hover:from-orange-600 hover:to-yellow-600 focus:outline-none focus:ring-2 focus:ring-orange-200 transition-all duration-200 shadow-sm hover:shadow-md">
                                <i class="fas fa-edit mr-1"></i>
                                Editar
                            </a>
                            @endcan
                        </div>
                    </div>
                    <div class="flex-auto p-6">
                        @if($user->roles->count() > 0)
                            <div class="space-y-2">
                                @foreach($user->roles as $role)
                                    <div class="flex items-center justify-between p-3 rounded-lg bg-gradient-to-r 
                                        @switch($role->name)
                                            @case('administrador')
                                                from-red-50 to-rose-100 border-red-200
                                                @break
                                            @case('vendedor')
                                                from-green-50 to-lime-100 border-green-200
                                                @break
                                            @case('jefe_bodega')
                                                from-blue-50 to-cyan-100 border-blue-200
                                                @break
                                            @default
                                                from-slate-50 to-slate-100 border-slate-200
                                        @endswitch
                                        border">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 mr-3 flex items-center justify-center rounded-lg bg-gradient-to-tl 
                                                @switch($role->name)
                                                    @case('administrador')
                                                        from-red-600 to-rose-400
                                                        @break
                                                    @case('vendedor')
                                                        from-green-600 to-lime-400
                                                        @break
                                                    @case('jefe_bodega')
                                                        from-blue-600 to-cyan-400
                                                        @break
                                                    @default
                                                        from-slate-600 to-slate-400
                                                @endswitch
                                                shadow-soft-md">
                                                <i class="
                                                    @switch($role->name)
                                                        @case('administrador')
                                                            fas fa-crown
                                                            @break
                                                        @case('vendedor')
                                                            fas fa-shopping-cart
                                                            @break
                                                        @case('jefe_bodega')
                                                            fas fa-warehouse
                                                            @break
                                                        @default
                                                            fas fa-user
                                                    @endswitch
                                                    text-white text-lg"></i>
                                            </div>
                                            <div>
                                                <h6 class="font-semibold text-slate-700 capitalize text-base">
                                                    {{ str_replace('_', ' ', $role->name) }}
                                                </h6>
                                                <p class="text-xs text-slate-500">
                                                    {{ $role->permissions->count() }} permisos asignados
                                                </p>
                                            </div>
                                        </div>
                                        <span class="text-xs bg-white px-2 py-1 rounded shadow-sm">
                                            <i class="fas fa-check text-green-600 mr-1"></i>Activo
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-user-slash text-3xl text-slate-300 mb-2"></i>
                                <p class="text-slate-500 text-sm">Sin roles asignados</p>
                                @can('usuarios.asignar_rol')
                                <a href="{{ route('users.edit-roles', $user->id) }}" 
                                   class="inline-block mt-3 px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-orange-500 to-yellow-500 rounded-lg hover:from-orange-600 hover:to-yellow-600">
                                    <i class="fas fa-plus mr-1"></i>
                                    Asignar Roles
                                </a>
                                @endcan
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Permisos Resumen -->
                @if($user->getAllPermissions()->count() > 0)
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border mt-6">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <h6 class="mb-0 text-lg font-semibold text-slate-700">
                            <i class="fas fa-key mr-2 text-slate-700"></i>
                            PERMISOS TOTALES
                        </h6>
                    </div>
                    <div class="flex-auto p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($user->getAllPermissions()->take(12) as $permission)
                                <div class="flex items-center p-2 bg-blue-50 rounded-lg border border-blue-200">
                                    <i class="fas fa-check-circle text-blue-600 mr-2 text-sm"></i>
                                    <span class="text-sm text-blue-700">{{ $permission->name }}</span>
                                </div>
                            @endforeach
                        </div>
                        @if($user->getAllPermissions()->count() > 12)
                            <div class="mt-4 text-center">
                                <span class="text-sm text-slate-500">
                                    +{{ $user->getAllPermissions()->count() - 12 }} permisos más
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
                @endif
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

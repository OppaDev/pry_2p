@extends('layouts.app')
@section('content')
    <div class="w-full px-6 py-6 mx-auto text-lg">
        <div class="container-fluid py-4">
            <div class="flex items-center justify-between">
                <h1 class="mb-0 text-2xl font-semibold text-slate-700">ASIGNAR ROLES</h1>
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
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-12 h-12 mr-4 text-center bg-center rounded-lg shadow-soft-2xl bg-gradient-to-tl from-purple-700 to-pink-500">
                                <i class="fas fa-user text-white text-lg"></i>
                            </div>
                            <h6 class="mb-0 text-lg font-semibold text-slate-700">INFORMACIÓN DEL USUARIO</h6>
                        </div>
                    </div>
                    <div class="flex-auto p-6">
                        <div class="text-center mb-4">
                            <img src="{{ asset('assets/img/team-2.jpg') }}" 
                                 class="w-24 h-24 mx-auto rounded-full border-4 border-white shadow-lg mb-4" 
                                 alt="Usuario">
                            <h5 class="text-xl font-semibold text-slate-700">{{ $user->name }}</h5>
                            <p class="text-slate-500 mb-4">{{ $user->email }}</p>
                            
                            <div class="mb-4">
                                <span class="bg-gradient-to-tl {{ $user->email_verified_at ? 'from-green-600 to-lime-400' : 'from-slate-600 to-slate-300' }} px-3 py-1 text-sm rounded-full text-white font-semibold">
                                    {{ $user->email_verified_at ? 'Email Verificado' : 'Email No Verificado' }}
                                </span>
                            </div>
                        </div>

                        <div class="space-y-3 text-sm text-slate-600">
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                                <span class="font-semibold">
                                    <i class="fas fa-calendar-plus mr-2 text-blue-500"></i>Creado:
                                </span>
                                <span>{{ $user->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                                <span class="font-semibold">
                                    <i class="fas fa-calendar-edit mr-2 text-purple-500"></i>Actualizado:
                                </span>
                                <span>{{ $user->updated_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Roles Actuales -->
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border mt-6">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-12 h-12 mr-4 text-center bg-center rounded-lg shadow-soft-2xl bg-gradient-to-tl from-blue-600 to-cyan-400">
                                <i class="fas fa-user-tag text-white text-lg"></i>
                            </div>
                            <h6 class="mb-0 text-lg font-semibold text-slate-700">ROLES ACTUALES</h6>
                        </div>
                    </div>
                    <div class="flex-auto p-6">
                        @if(count($userRoles) > 0)
                            <div class="space-y-2">
                                @foreach($userRoles as $roleName)
                                    <div class="flex items-center p-3 bg-blue-50 rounded-lg border border-blue-200">
                                        <i class="fas fa-shield-alt text-blue-600 mr-3"></i>
                                        <span class="font-semibold text-blue-700 capitalize">{{ $roleName }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-user-slash text-3xl text-slate-300 mb-2"></i>
                                <p class="text-slate-500 text-sm">Sin roles asignados</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Formulario de Asignación de Roles -->
            <div class="w-full lg:w-2/3 px-3">
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-12 h-12 mr-4 text-center bg-center rounded-lg shadow-soft-2xl bg-gradient-to-tl from-purple-700 to-pink-500">
                                <i class="fas fa-user-cog text-white text-lg"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 text-lg font-semibold text-slate-700">GESTIONAR ROLES</h6>
                                <p class="mb-0 text-sm leading-normal text-slate-400">
                                    Seleccione los roles que desea asignar a <span class="font-semibold text-slate-600">"{{ $user->name }}"</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex-auto p-6">
                        <form action="{{ route('users.update-roles', $user->id) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            @if($errors->any())
                                <div class="relative w-full p-4 mb-6 text-white bg-gradient-to-r from-red-500 to-rose-400 rounded-lg shadow-soft-lg">
                                    <div class="flex items-start">
                                        <i class="fas fa-exclamation-circle text-2xl mr-3 mt-1"></i>
                                        <div>
                                            <h6 class="mb-2 font-semibold text-white">Error al asignar roles:</h6>
                                            <ul class="list-disc list-inside space-y-1">
                                                @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-start">
                                    <i class="fas fa-info-circle text-blue-500 text-xl mr-3 mt-1"></i>
                                    <div class="text-sm text-blue-700">
                                        <p class="font-semibold mb-1">Información sobre roles:</p>
                                        <ul class="list-disc list-inside space-y-1 text-blue-600">
                                            <li><strong>Administrador:</strong> Acceso completo al sistema</li>
                                            <li><strong>Vendedor:</strong> Gestión de ventas y clientes</li>
                                            <li><strong>Jefe de Bodega:</strong> Gestión de inventario y productos</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <label class="block text-sm font-bold text-slate-700 mb-3">
                                    <i class="fas fa-shield-alt mr-2"></i>Roles Disponibles 
                                    <span class="text-red-500">*</span>
                                </label>

                                <div class="grid grid-cols-1 gap-4">
                                    @foreach($roles as $role)
                                        <div class="relative">
                                            <input type="checkbox" 
                                                   id="role-{{ $role->id }}" 
                                                   name="roles[]" 
                                                   value="{{ $role->name }}"
                                                   {{ in_array($role->name, $userRoles) ? 'checked' : '' }}
                                                   class="peer sr-only">
                                            <label for="role-{{ $role->id }}" 
                                                   class="flex items-center justify-between p-4 bg-white border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-slate-50 peer-checked:border-purple-600 peer-checked:bg-purple-50 transition-all duration-200 shadow-sm hover:shadow-md">
                                                <div class="flex items-center">
                                                    <div class="flex items-center justify-center w-10 h-10 mr-4 rounded-lg bg-gradient-to-tl 
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
                                                        <p class="text-sm text-slate-500">
                                                            @switch($role->name)
                                                                @case('administrador')
                                                                    Control total del sistema y gestión de usuarios
                                                                    @break
                                                                @case('vendedor')
                                                                    Registro y gestión de ventas y clientes
                                                                    @break
                                                                @case('jefe_bodega')
                                                                    Administración de inventario y productos
                                                                    @break
                                                                @default
                                                                    {{ $role->name }}
                                                            @endswitch
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center">
                                                    <div class="w-6 h-6 border-2 border-gray-300 rounded peer-checked:bg-purple-600 peer-checked:border-purple-600 flex items-center justify-center transition-all duration-200">
                                                        <i class="fas fa-check text-white text-sm opacity-0 peer-checked:opacity-100"></i>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                @error('roles')
                                    <p class="text-sm text-red-600 mt-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
                                <a href="{{ route('users.index') }}"
                                    class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-slate-600 to-slate-300 leading-pro text-sm ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Cancelar
                                </a>
                                <button type="submit"
                                    class="inline-block px-8 py-3 mb-0 font-bold text-center uppercase align-middle transition-all bg-transparent border-0 rounded-lg shadow-none cursor-pointer leading-pro text-sm ease-soft-in tracking-tight-soft bg-gradient-to-tl from-purple-700 to-pink-500 bg-150 bg-x-25 text-white hover:scale-102 hover:shadow-soft-xs active:opacity-85">
                                    <i class="fas fa-save mr-2"></i>
                                    Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Información de Permisos Asociados -->
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border mt-6">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-12 h-12 mr-4 text-center bg-center rounded-lg shadow-soft-2xl bg-gradient-to-tl from-indigo-600 to-purple-400">
                                <i class="fas fa-key text-white text-lg"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 text-lg font-semibold text-slate-700">PERMISOS POR ROL</h6>
                                <p class="mb-0 text-sm leading-normal text-slate-400">
                                    Vista rápida de los permisos que otorga cada rol
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="flex-auto p-6">
                        <div class="space-y-4">
                            @foreach($roles as $role)
                                <div class="p-4 border border-slate-200 rounded-lg hover:border-purple-300 transition-all duration-200">
                                    <div class="flex items-center justify-between mb-3">
                                        <h6 class="font-semibold text-slate-700 capitalize flex items-center">
                                            <div class="w-8 h-8 mr-3 flex items-center justify-center rounded-lg bg-gradient-to-tl 
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
                                                shadow-soft-sm">
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
                                                    text-white text-sm"></i>
                                            </div>
                                            {{ str_replace('_', ' ', $role->name) }}
                                        </h6>
                                        <span class="text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded">
                                            {{ $role->permissions->count() }} permisos
                                        </span>
                                    </div>
                                    @if($role->permissions->count() > 0)
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($role->permissions->take(8) as $permission)
                                                <span class="text-xs bg-blue-50 text-blue-700 px-2 py-1 rounded border border-blue-200">
                                                    {{ $permission->name }}
                                                </span>
                                            @endforeach
                                            @if($role->permissions->count() > 8)
                                                <span class="text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded">
                                                    +{{ $role->permissions->count() - 8 }} más
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <p class="text-sm text-slate-400">Sin permisos asignados</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Estilos para el checkbox personalizado con peer */
        input[type="checkbox"]:checked + label .fa-check {
            opacity: 1 !important;
        }
        input[type="checkbox"]:checked + label > div:last-child > div {
            background-color: rgb(147 51 234) !important;
            border-color: rgb(147 51 234) !important;
        }
    </style>
@endsection

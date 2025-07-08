<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Role Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Your current role and permissions in the system.') }}
        </p>
    </header>

    <div class="mt-6 space-y-6">
        <!-- Mostrar rol actual -->
        <div class="p-4 bg-gray-50 rounded-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    @if($userRole === 'admin')
                        <div class="flex items-center justify-center w-8 h-8 bg-red-100 rounded-full">
                            <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    @elseif($userRole === 'docente')
                        <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-full">
                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    @elseif($userRole === 'estudiante')
                        <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-full">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">
                        Rol actual:
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($userRole === 'admin') bg-red-100 text-red-800
                            @elseif($userRole === 'docente') bg-blue-100 text-blue-800
                            @elseif($userRole === 'estudiante') bg-green-100 text-green-800
                            @endif">
                            {{ ucfirst($userRole ?? 'Sin rol') }}
                        </span>
                    </div>
                    <div class="text-sm text-gray-500">
                        @if($userRole === 'admin')
                            Tienes acceso completo al sistema y puedes gestionar todos los usuarios y contenidos.
                        @elseif($userRole === 'docente')
                            Puedes gestionar asignaturas, estudiantes y calificaciones.
                        @elseif($userRole === 'estudiante')
                            Puedes ver tus asignaturas y calificaciones.
                        @else
                            No tienes un rol asignado. Contacta al administrador.
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario para cambiar roles (solo para administradores) -->
        @if($isAdmin)
            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <h3 class="text-sm font-medium text-yellow-800 mb-3">
                    🔒 Panel de Administrador - Gestión de Roles
                </h3>

                <form method="post" action="{{ route('profile.update-role') }}" class="space-y-4">
                    @csrf
                    @method('patch')

                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700">
                            Seleccionar Usuario
                        </label>
                        <select id="user_id" name="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Selecciona un usuario...</option>
                            @foreach(\App\Models\User::with('roles')->get() as $userOption)
                                <option value="{{ $userOption->id }}">
                                    {{ $userOption->name }} ({{ $userOption->email }}) - Rol actual: {{ $userOption->getRoleNames()->first() ?? 'Sin rol' }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('user_id')" />
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">
                            Nuevo Rol
                        </label>
                        <select id="role" name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Selecciona un rol...</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('role')" />
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button class="bg-yellow-600 hover:bg-yellow-700">
                            {{ __('Actualizar Rol') }}
                        </x-primary-button>

                        @if (session('status') === 'role-updated')
                            <p
                                x-data="{ show: true }"
                                x-show="show"
                                x-transition
                                x-init="setTimeout(() => show = false, 3000)"
                                class="text-sm text-green-600"
                            >¡Rol actualizado correctamente!</p>
                        @endif
                    </div>
                </form>
            </div>
        @endif

        <!-- Mostrar mensajes de error -->
        @if (session('error'))
            <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-sm text-red-600">{{ session('error') }}</p>
            </div>
        @endif
    </div>
</section>

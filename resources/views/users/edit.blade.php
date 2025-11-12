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
                                <i class="fas fa-user-edit text-white text-lg"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 text-lg font-semibold">Editar Usuario</h6>
                                <p class="mb-0 text-sm leading-normal text-slate-400">Modifique la información del usuario
                                    <span class="font-semibold text-slate-600">"{{ $user->name }}"</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex-auto p-6">
                        <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <!-- Primera fila -->
                            <div class="flex flex-wrap -mx-3">
                                <div class="w-full max-w-full px-3 md:w-6/12 md:flex-none">
                                    <div class="mb-4">
                                        <label for="name"
                                            class="inline-block mb-2 ml-1 font-bold text-sm text-slate-700">
                                            Nombre del Usuario <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="name" name="name"
                                            value="{{ old('name', $user->name) }}" required
                                            class="focus:shadow-soft-primary-outline text-lg leading-6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                                            placeholder="Ingrese el nombre del usuario">
                                        @error('name')
                                            <p class="mb-0 text-sm leading-tight text-red-500 mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="w-full max-w-full px-3 md:w-6/12 md:flex-none">
                                    <div class="mb-4">
                                        <label for="email"
                                            class="inline-block mb-2 ml-1 font-bold text-sm text-slate-700">
                                            Email del Usuario <span class="text-red-500">*</span>
                                        </label>
                                        <input type="email" id="email" name="email"
                                            value="{{ old('email', $user->email) }}" required
                                            class="focus:shadow-soft-primary-outline text-lg leading-6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                                            placeholder="usuario@example.com">
                                        @error('email')
                                            <p class="mb-0 text-sm leading-tight text-red-500 mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Segunda fila - Contraseñas -->
                            <div class="flex flex-wrap -mx-3">
                                <div class="w-full max-w-full px-3 md:w-6/12 md:flex-none">
                                    <div class="mb-4">
                                        <label for="password"
                                            class="inline-block mb-2 ml-1 font-bold text-sm text-slate-700">
                                            Nueva Contraseña
                                        </label>
                                        <input type="password" id="password" name="password"
                                            class="focus:shadow-soft-primary-outline text-lg leading-6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                                            placeholder="Dejar vacío para mantener la actual">
                                        @error('password')
                                            <p class="mb-0 text-sm leading-tight text-red-500 mt-2">{{ $message }}</p>
                                        @enderror
                                        <p class="mb-0 text-sm leading-tight text-slate-400 mt-1">Mínimo 8 caracteres. Dejar
                                            vacío para no cambiar.</p>
                                    </div>
                                </div>

                                <div class="w-full max-w-full px-3 md:w-6/12 md:flex-none">
                                    <div class="mb-4">
                                        <label for="password_confirmation"
                                            class="inline-block mb-2 ml-1 font-bold text-sm text-slate-700">
                                            Confirmar Nueva Contraseña
                                        </label>
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            class="focus:shadow-soft-primary-outline text-lg leading-6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                                            placeholder="Confirme la nueva contraseña">
                                        @error('password_confirmation')
                                            <p class="mb-0 text-sm leading-tight text-red-500 mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Información adicional -->
                            <div class="flex flex-wrap -mx-3">
                                <div class="w-full max-w-full px-3">
                                    <div class="mb-4 p-4 bg-slate-50 rounded-lg border border-slate-200">
                                        <div class="flex items-center justify-between mb-3">
                                            <h6 class="mb-0 text-lg font-semibold text-slate-700">Información del Usuario</h6>
                                            @can('usuarios.asignar_rol')
                                            <a href="{{ route('users.edit-roles', $user->id) }}"
                                                class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-orange-500 to-yellow-500 rounded-lg hover:from-orange-600 hover:to-yellow-600 focus:outline-none focus:ring-2 focus:ring-orange-200 transition-all duration-200 shadow-sm hover:shadow-md">
                                                <i class="fas fa-user-tag mr-2"></i>
                                                Gestionar Roles
                                            </a>
                                            @endcan
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-slate-600">
                                            <div>
                                                <span class="font-semibold">Email verificado:</span>
                                                <span
                                                    class="ml-1 px-2 py-1 rounded {{ $user->email_verified_at ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                                    {{ $user->email_verified_at ? 'Sí' : 'No' }}
                                                </span>
                                            </div>
                                            <div>
                                                <span class="font-semibold">Fecha de registro:</span>
                                                <span class="ml-1">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                            <div>
                                                <span class="font-semibold">Última actualización:</span>
                                                <span class="ml-1">{{ $user->updated_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                        </div>
                                        @if($user->roles->count() > 0)
                                            <div class="mt-4 pt-4 border-t border-slate-200">
                                                <div class="flex items-center">
                                                    <span class="font-semibold text-slate-700 mr-3">
                                                        <i class="fas fa-shield-alt mr-1"></i>Roles asignados:
                                                    </span>
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach($user->roles as $role)
                                                            <span class="bg-gradient-to-tl 
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
                                                                        from-slate-600 to-slate-300
                                                                @endswitch
                                                                px-2.5 py-1 text-xs rounded-lg text-white font-bold uppercase">
                                                                {{ str_replace('_', ' ', $role->name) }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="flex flex-wrap -mx-3">
                                <div class="w-full max-w-full px-3">
                                    <div class="flex items-center justify-between pt-4">
                                        <div class="flex space-x-3">
                                            <a href="{{ route('users.index') }}"
                                                class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-slate-600 to-slate-300 leading-pro text-sm ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                                <i class="fas fa-arrow-left mr-2"></i>
                                                Volver
                                            </a>
                                        </div>
                                        <div class="flex space-x-3">
                                            <button type="button" onclick="resetForm()"
                                                class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-slate-600 to-slate-300 leading-pro text-sm ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                                <i class="fas fa-undo mr-2"></i>
                                                Restablecer
                                            </button>
                                            <button type="submit"
                                                class="inline-block px-8 py-2 mb-0 font-bold text-center uppercase align-middle transition-all bg-transparent border border-solid rounded-lg shadow-none cursor-pointer leading-pro text-sm ease-soft-in tracking-tight-soft bg-gradient-to-tl from-purple-700 to-pink-500 bg-150 bg-x-25 border-purple-700 text-white hover:scale-102 hover:shadow-soft-xs active:opacity-85">
                                                <i class="fas fa-save mr-2"></i>
                                                Actualizar Usuario
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function resetForm() {
            // Resetear a los valores originales
            document.getElementById('name').value = '{{ $user->name }}';
            document.getElementById('email').value = '{{ $user->email }}';
            document.getElementById('password').value = '';
            document.getElementById('password_confirmation').value = '';
        }
    </script>
@endsection

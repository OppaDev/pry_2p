@extends('layouts.app')
@section('content')
    <div class="w-full px-6 py-6 mx-auto text-lg">
        <div class="container-fluid py-4">
            <div class="flex items-center justify-between">
                <h1 class="mb-0 text-2xl font-semibold text-slate-700">USUARIOS ELIMINADOS</h1>
                <a href="{{ route('users.index') }}" 
                    class="inline-block px-6 py-2 mb-0 font-bold text-center uppercase align-middle transition-all bg-transparent border border-solid rounded-lg shadow-none cursor-pointer leading-pro text-base ease-soft-in tracking-tight-soft bg-gradient-to-tl from-blue-600 to-blue-400 bg-150 bg-x-25 border-blue-600 text-white hover:scale-102 hover:shadow-soft-xs active:opacity-85">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver a Usuarios
                </a>
            </div>
        </div>
        <div class="flex flex-wrap -mx-3">            
            <div class="flex-none w-full max-w-full px-3">
                <div
                    class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <div class="flex justify-between items-center">
                            <h6 class="mb-0 text-xl font-semibold text-slate-700">
                                <i class="fas fa-user-slash mr-2 text-slate-700"></i>
                                USUARIOS ELIMINADOS
                            </h6>
                            <div class="flex items-center space-x-3">
                                <!-- Formulario de búsqueda -->
                                <form method="GET" action="{{ route('users.deleted') }}" class="flex items-center space-x-2">
                                    <!-- Mantener el parámetro per_page -->
                                    <input type="hidden" name="per_page" value="{{ $perPage }}">

                                    <div
                                        class="flex items-center space-x-2 bg-gradient-to-r from-red-50 to-red-100 px-4 py-2 rounded-xl border border-red-200/60 shadow-sm">
                                        <label for="search" class="text-lg font-medium text-red-600 flex items-center">
                                            <i class="fas fa-search mr-2 text-red-500"></i>
                                            <span>Buscar:</span>
                                        </label>
                                        <input type="text" id="search" name="search" value="{{ $search }}"
                                            placeholder="Nombre o email..."
                                            class="px-3 py-1.5 text-lg bg-white/80 backdrop-blur-sm border border-red-200/60 rounded-lg shadow-soft-xs hover:shadow-soft-sm focus:shadow-soft-md focus:outline-none focus:ring-2 focus:ring-red-200/50 focus:border-red-300 transition-all duration-300 ease-soft-in-out text-slate-700 min-w-[200px]">
                                        <button type="submit"
                                            class="px-3 py-1.5 text-lg bg-red-500 hover:bg-red-600 text-white rounded-lg shadow-soft-xs hover:shadow-soft-sm focus:shadow-soft-md focus:outline-none focus:ring-2 focus:ring-red-200/50 transition-all duration-300 ease-soft-in-out">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        @if ($search)
                                            <a href="{{ route('users.deleted', ['per_page' => $perPage]) }}"
                                                class="px-3 py-1.5 text-lg bg-gray-500 hover:bg-gray-600 text-white rounded-lg shadow-soft-xs hover:shadow-soft-sm focus:shadow-soft-md focus:outline-none focus:ring-2 focus:ring-gray-200/50 transition-all duration-300 ease-soft-in-out"
                                                title="Limpiar búsqueda">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        @endif
                                    </div>
                                </form>

                                <!-- Selector de per_page -->
                                <div
                                    class="flex items-center space-x-2 bg-gradient-to-r from-slate-50 to-slate-100 px-4 py-2 rounded-xl border border-slate-200/60 shadow-sm">
                                    <label for="per_page" class="text-lg font-medium text-slate-600 flex items-center">
                                        <i class="fas fa-eye mr-2 text-slate-500"></i>
                                        <span>Mostrar:</span>
                                    </label>
                                    <select id="per_page" name="per_page" onchange="changePerPage(this.value)"
                                        class="px-3 py-1.5 text-lg bg-white/80 backdrop-blur-sm border border-slate-200/60 rounded-lg shadow-soft-xs hover:shadow-soft-sm focus:shadow-soft-md focus:outline-none focus:ring-2 focus:ring-slate-200/50 focus:border-slate-300 transition-all duration-300 ease-soft-in-out text-slate-700 cursor-pointer">
                                        <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                        <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
                                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                    </select>
                                    <span class="text-lg font-medium text-slate-600">por página</span>
                                </div>
                            </div>
                        </div>
                        @if ($errors->has('per_page'))
                            <div class="relative w-full p-4 text-white bg-red-500 rounded-lg mt-4">
                                {{ $errors->first('per_page') }}
                            </div>
                        @endif
                        @if ($errors->has('search'))
                            <div class="relative w-full p-4 text-white bg-red-500 rounded-lg mt-4">
                                {{ $errors->first('search') }}
                            </div>
                        @endif
                        @if ($search)
                            <div
                                class="relative w-full p-4 text-red-700 bg-red-100 border border-red-300 rounded-lg mt-4">
                                <div class="flex items-center">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <span>Mostrando resultados para: <strong>"{{ $search }}"</strong></span>
                                    <span class="ml-2 text-sm text-red-600">({{ $usuarios->total() }}
                                        {{ $usuarios->total() == 1 ? 'resultado' : 'resultados' }})</span>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-0 overflow-x-auto">
                            <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                                <thead class="align-bottom">
                                    <tr>
                                        <th
                                            class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-base border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            USUARIO</th>
                                        <th
                                            class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-base border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            EMAIL</th>
                                        <th
                                            class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-base border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            ELIMINADO</th>
                                        <th
                                            class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-base border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            VERIFICADO</th>
                                        <th
                                            class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-base border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            CREADO</th>
                                        <th
                                            class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-gray-200 border-solid shadow-none tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($usuarios as $user)
                                        <tr class="table-row-hover transition-all duration-200 bg-red-50/30">
                                            <td
                                                class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <div class="flex px-2 py-1">
                                                    <div>
                                                        <img src="../assets/img/team-2.jpg"
                                                            class="inline-flex items-center justify-center mr-4 text-sm text-white transition-all duration-200 ease-soft-in-out h-9 w-9 rounded-xl opacity-60"
                                                            alt="user" />
                                                    </div>
                                                    <div class="flex flex-col justify-center">
                                                        <h6 class="mb-0 text-lg leading-normal text-slate-600">{{ $user->name }}</h6>
                                                        <p class="mb-0 text-base leading-tight text-slate-400">
                                                            {{ $user->email }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td
                                                class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <p class="mb-0 text-lg font-semibold leading-tight text-slate-500">{{ $user->email }}</p>
                                            </td>
                                            <td
                                                class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <div class="flex flex-col">
                                                    <span class="text-lg font-semibold leading-tight text-red-600">{{ $user->deleted_at->format('d/m/y') }}</span>
                                                    <span class="text-sm text-slate-400">{{ $user->deleted_at->diffForHumans() }}</span>
                                                </div>
                                            </td>
                                            <td
                                                class="p-2 text-lg leading-normal text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <span
                                                    class="bg-gradient-to-tl {{ $user->email_verified_at ? 'from-green-600 to-lime-400' : 'from-slate-600 to-slate-300' }} px-2.5 text-base rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white opacity-80">
                                                    {{ $user->email_verified_at ? 'Verificado' : 'No Verificado' }}
                                                </span>
                                            </td>
                                            <td
                                                class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <span
                                                    class="text-lg font-semibold leading-tight text-slate-400">{{ $user->created_at->format('d/m/y') }}</span>
                                            </td>
                                            <td
                                                class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <div class="flex items-center space-x-2 action-buttons">
                                                    @if(auth()->id() !== $user->id)
                                                        <button type="button" onclick="openModal('restore-user-{{ $user->id }}-modal')"
                                                            class="px-3 py-1.5 text-sm font-semibold text-white bg-gradient-to-r from-green-500 to-green-600 rounded-lg hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-green-200 transition-all duration-200 shadow-sm hover:shadow-md btn-soft-transition">
                                                            <i class="fas fa-undo mr-1"></i>
                                                            Restaurar
                                                        </button>
                                                        <button type="button" onclick="openModal('force-delete-user-{{ $user->id }}-modal')"
                                                            class="px-3 py-1.5 text-sm font-semibold text-white bg-gradient-to-r from-red-500 to-red-600 rounded-lg hover:from-red-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-red-200 transition-all duration-200 shadow-sm hover:shadow-md btn-soft-transition">
                                                            <i class="fas fa-trash-alt mr-1"></i>
                                                            Eliminar Definitivo
                                                        </button>
                                                    @else
                                                        <span class="px-3 py-1.5 text-sm font-semibold text-slate-400 bg-gradient-to-r from-slate-200 to-slate-300 rounded-lg cursor-not-allowed">
                                                            <i class="fas fa-lock mr-1"></i>
                                                            Tu cuenta
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6"
                                                class="p-4 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <div class="flex flex-col items-center py-8">
                                                    <i class="fas fa-user-slash text-4xl text-slate-300 mb-4"></i>
                                                    <p class="text-xl font-medium text-slate-500">No hay usuarios eliminados</p>
                                                    <p class="text-lg text-slate-400">
                                                        @if($search)
                                                            No se encontraron usuarios eliminados que coincidan con tu búsqueda.
                                                        @else
                                                            La papelera de usuarios está vacía.
                                                        @endif
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <!-- Paginación -->
                            <div class="flex items-center mt-6 px-6">
                                <div class="w-full max-w-4xl">
                                    {{ $usuarios->appends(request()->input())->links('pagination::tailwind') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modales de Confirmación -->
    @foreach($usuarios as $user)
        @if(auth()->id() !== $user->id)
            <!-- Modal de Restauración -->
            @include('components.restore-modal', [
                'modalId' => 'restore-user-' . $user->id . '-modal',
                'title' => 'Confirmar Restauración de Usuario',
                'message' => '¿Estás seguro de que deseas restaurar este usuario? Volverá a estar disponible en el sistema.',
                'itemName' => $user->name,
                'itemDetails' => $user->email,
                'restoreRoute' => route('users.restore', $user->id),
                'confirmText' => 'Restaurar Usuario'
            ])
            
            <!-- Modal de Eliminación Permanente -->
            @include('components.force-delete-modal', [
                'modalId' => 'force-delete-user-' . $user->id . '-modal',
                'title' => 'Confirmar Eliminación Permanente',
                'message' => '⚠️ ATENCIÓN: Esta acción eliminará permanentemente al usuario y NO SE PUEDE DESHACER. Todos los datos asociados se perderán para siempre.',
                'itemName' => $user->name,
                'itemDetails' => $user->email,
                'deleteRoute' => route('users.forceDelete', $user->id),
                'confirmText' => 'Eliminar Permanentemente'
            ])
        @endif
    @endforeach
    
    <script>
        function changePerPage(value) {
            const url = new URL(window.location);
            url.searchParams.set('per_page', value);
            url.searchParams.delete('page');
            const currentSearch = document.getElementById('search').value;
            if (currentSearch) {
                url.searchParams.set('search', currentSearch);
            }
            window.location.href = url.toString();
        }
    </script>
@endsection

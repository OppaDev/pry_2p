@extends('layouts.app')
@section('content')
    <div class="w-full px-6 py-6 mx-auto text-lg">
        <div class="container-fluid py-4">
            <h1 class="mb-0 text-2xl font-semibold text-slate-700">LISTA DE USUARIOS</h1>
        </div>
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div
                    class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <div class="flex justify-between items-center">
                            <h6 class="mb-0 text-xl font-semibold text-slate-700">
                                <i class="fas fa-users mr-2 text-slate-700"></i>
                                USUARIOS
                            </h6>
                            <div class="flex items-center space-x-3">
                                <!-- Formulario de búsqueda -->
                                <form method="GET" action="{{ route('users.index') }}" class="flex items-center space-x-2">
                                    <!-- Mantener el parámetro per_page -->
                                    <input type="hidden" name="per_page" value="{{ $perPage }}">

                                    <div
                                        class="flex items-center space-x-2 bg-gradient-to-r from-blue-50 to-blue-100 px-4 py-2 rounded-xl border border-blue-200/60 shadow-sm">
                                        <label for="search" class="text-lg font-medium text-blue-600 flex items-center">
                                            <i class="fas fa-search mr-2 text-blue-500"></i>
                                            <span>Buscar:</span>
                                        </label>
                                        <input type="text" id="search" name="search" value="{{ $search }}"
                                            placeholder="Nombre o email..."
                                            class="px-3 py-1.5 text-lg bg-white/80 backdrop-blur-sm border border-blue-200/60 rounded-lg shadow-soft-xs hover:shadow-soft-sm focus:shadow-soft-md focus:outline-none focus:ring-2 focus:ring-blue-200/50 focus:border-blue-300 transition-all duration-300 ease-soft-in-out text-slate-700 min-w-[200px]">
                                        <button type="submit"
                                            class="px-3 py-1.5 text-lg bg-blue-500 hover:bg-blue-600 text-white rounded-lg shadow-soft-xs hover:shadow-soft-sm focus:shadow-soft-md focus:outline-none focus:ring-2 focus:ring-blue-200/50 transition-all duration-300 ease-soft-in-out">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        @if ($search)
                                            <a href="{{ route('users.index', ['per_page' => $perPage]) }}"
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
                                class="relative w-full p-4 text-blue-700 bg-blue-100 border border-blue-300 rounded-lg mt-4">
                                <div class="flex items-center">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <span>Mostrando resultados para: <strong>"{{ $search }}"</strong></span>
                                    <span class="ml-2 text-sm text-blue-600">({{ $usuarios->total() }}
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
                                            USERNAME</th>
                                        <th
                                            class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-base border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            EMAIL</th>
                                        <th
                                            class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-base border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            CREADO</th>
                                        <th
                                            class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-base border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            VERIFICADO</th>
                                        <th
                                            class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-base border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            ESTADO</th>
                                        <th
                                            class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-base border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            ACTUALIZADO</th>
                                        <th
                                            class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-gray-200 border-solid shadow-none tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($usuarios as $user)
                                        <tr class="table-row-hover transition-all duration-200">
                                            <td
                                                class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <div class="flex px-2 py-1">
                                                    <div>
                                                        <img src="../assets/img/team-2.jpg"
                                                            class="inline-flex items-center justify-center mr-4 text-sm text-white transition-all duration-200 ease-soft-in-out h-9 w-9 rounded-xl"
                                                            alt="user" />
                                                    </div>
                                                    <div class="flex flex-col justify-center">
                                                        <h6 class="mb-0 text-lg leading-normal">{{ $user->name }}</h6>
                                                        <p class="mb-0 text-base leading-tight text-slate-400">
                                                            {{ $user->email }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td
                                                class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <p class="mb-0 text-lg font-semibold leading-tight">{{ $user->email }}</p>
                                            </td>
                                            <td
                                                class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <span
                                                    class="text-lg font-semibold leading-tight text-slate-400">{{ $user->created_at->format('d/m/y') }}</span>
                                            </td>
                                            <td
                                                class="p-2 text-lg leading-normal text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <span
                                                    class="bg-gradient-to-tl {{ $user->email_verified_at ? 'from-green-600 to-lime-400' : 'from-slate-600 to-slate-300' }} px-2.5 text-base rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">
                                                    {{ $user->email_verified_at ? 'Verificado' : 'No Verificado' }}
                                                </span>
                                            </td>
                                            <td
                                                class="p-2 text-lg leading-normal text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <span
                                                    class="bg-gradient-to-tl {{ $user->estado === 'activo' ? 'from-green-600 to-lime-400' : 'from-red-600 to-red-400' }} px-2.5 text-base rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">
                                                    {{ $user->estado === 'activo' ? 'Activo' : 'Inactivo' }}
                                                </span>
                                                @if($user->estado === 'inactivo' && $user->motivo)
                                                    <div class="text-xs text-slate-500 mt-1" title="{{ $user->motivo }}">
                                                        {{ Str::limit($user->motivo, 30) }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td
                                                class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <span
                                                    class="text-lg font-semibold leading-tight text-slate-400">{{ $user->updated_at->format('d/m/y') }}</span>
                                            </td>                            <td
                                class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                <div class="flex items-center space-x-2 action-buttons">
                                    <a href="{{ route('users.show', $user->id) }}"
                                        class="px-3 py-1.5 text-sm font-semibold text-white bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg hover:from-indigo-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-200 transition-all duration-200 shadow-sm hover:shadow-md btn-soft-transition">
                                        <i class="fas fa-eye mr-1"></i>
                                        Ver
                                    </a>
                                    <a href="{{ route('users.audit-history', $user->id) }}"
                                        class="px-3 py-1.5 text-sm font-semibold text-white bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg hover:from-purple-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-200 transition-all duration-200 shadow-sm hover:shadow-md btn-soft-transition">
                                        <i class="fas fa-history mr-1"></i>
                                        Historial
                                    </a>
                                    <a href="{{ route('users.edit', $user->id) }}"
                                        class="px-3 py-1.5 text-sm font-semibold text-white bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200 transition-all duration-200 shadow-sm hover:shadow-md btn-soft-transition">
                                        <i class="fas fa-edit mr-1"></i>
                                        Editar
                                    </a>
                                    @if(auth()->id() !== $user->id)
                                        @if($user->estado === 'activo')
                                            <button type="button" onclick="openModal('deactivate-user-{{ $user->id }}-modal')"
                                                class="px-3 py-1.5 text-sm font-semibold text-white bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg hover:from-orange-600 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-200 transition-all duration-200 shadow-sm hover:shadow-md btn-soft-transition">
                                                <i class="fas fa-user-slash mr-1"></i>
                                                Desactivar
                                            </button>
                                        @else
                                            <button type="button" onclick="activateUser({{ $user->id }})"
                                                class="px-3 py-1.5 text-sm font-semibold text-white bg-gradient-to-r from-green-500 to-green-600 rounded-lg hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-green-200 transition-all duration-200 shadow-sm hover:shadow-md btn-soft-transition">
                                                <i class="fas fa-user-check mr-1"></i>
                                                Activar
                                            </button>
                                        @endif
                                        <button type="button" onclick="openModal('delete-user-{{ $user->id }}-modal')"
                                            class="px-3 py-1.5 text-sm font-semibold text-white bg-gradient-to-r from-red-500 to-red-600 rounded-lg hover:from-red-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-red-200 transition-all duration-200 shadow-sm hover:shadow-md btn-soft-transition">
                                            <i class="fas fa-trash mr-1"></i>
                                            Eliminar
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
                                            <td colspan="7"
                                                class="p-4 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <div class="flex flex-col items-center py-8">
                                                    <i class="fas fa-user-slash text-4xl text-slate-300 mb-4"></i>
                                                    <p class="text-slate-500 text-lg">
                                                        @if ($search)
                                                            No se encontraron usuarios que coincidan con
                                                            "<strong>{{ $search }}</strong>"
                                                        @else
                                                            No hay usuarios registrados
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

    <!-- Modales de Confirmación de Eliminación -->
    @foreach($usuarios as $user)
        @if(auth()->id() !== $user->id)
            @include('components.delete-modal', [
                'modalId' => 'delete-user-' . $user->id . '-modal',
                'title' => 'Confirmar Eliminación de Usuario',
                'message' => '¿Estás seguro de que deseas eliminar este usuario? Esta acción eliminará permanentemente toda la información asociada.',
                'itemName' => $user->name,
                'itemDetails' => $user->email,
                'deleteRoute' => route('users.destroy', $user->id),
                'confirmText' => 'Eliminar Usuario'
            ])
        @endif
    @endforeach

    <!-- Modales de Desactivación de Usuario -->
    @foreach($usuarios as $user)
        @if(auth()->id() !== $user->id && $user->estado === 'activo')
            <!-- Modal de desactivación -->
            <div id="deactivate-user-{{ $user->id }}-modal" class="fixed inset-0 z-50 items-center justify-center hidden bg-black bg-opacity-50">
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0 w-10 h-10 mx-auto bg-orange-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user-slash text-orange-600"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Desactivar Usuario</h3>
                                <p class="text-sm text-gray-500">{{ $user->name }} ({{ $user->email }})</p>
                            </div>
                        </div>

                        <form id="deactivate-form-{{ $user->id }}" action="{{ route('users.deactivate', $user->id) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="mb-4">
                                <label for="motivo-{{ $user->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                    Motivo de desactivación <span class="text-red-500">*</span>
                                </label>
                                <textarea id="motivo-{{ $user->id }}" name="motivo" rows="3" required
                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-200 focus:border-orange-300"
                                    placeholder="Explique el motivo de la desactivación..."></textarea>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <button type="button" onclick="closeModal('deactivate-user-{{ $user->id }}-modal')"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 border border-gray-300 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-200">
                                    Cancelar
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-orange-600 border border-transparent rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-200">
                                    Desactivar Usuario
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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

        function activateUser(userId) {
            if (confirm('¿Estás seguro de que deseas activar este usuario?')) {
                // Crear formulario dinámico para activar usuario
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/users/${userId}/activate`;

                // Token CSRF
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                // Método PATCH
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'PATCH';
                form.appendChild(methodField);

                document.body.appendChild(form);
                form.submit();
            }
        }

        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
            document.getElementById(modalId).classList.add('flex');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            document.getElementById(modalId).classList.remove('flex');
        }

        // Cerrar modales con ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const modals = document.querySelectorAll('[id$="-modal"]');
                modals.forEach(modal => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                });
            }
        });
    </script>
@endsection

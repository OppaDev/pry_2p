@extends('layouts.app')

@section('content')
    <div class="w-full px-6 py-6 mx-auto text-lg">
        <div class="container-fluid py-4">
            <h1 class="mb-0 text-2xl font-semibold text-slate-700">GESTIÓN DE NOTAS</h1>
            <p class="text-slate-500">Panel de control para docentes</p>
        </div>

        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    
                    <!-- Header del panel -->
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <div class="flex justify-between items-center">
                            <div>
                                <h6 class="mb-0 text-xl font-semibold text-slate-700">
                                    <i class="fas fa-clipboard-list mr-2 text-slate-700"></i>
                                    NOTAS REGISTRADAS
                                </h6>
                                <p class="text-sm text-slate-500 mt-1">
                                    Total de registros: {{ $notas->total() }}
                                </p>
                            </div>
                            
                            <!-- Botón para crear nueva nota -->
                            <div class="flex space-x-3">
                                <a href="{{ route('notas.create') }}"
                                    class="inline-block px-6 py-2 mb-0 font-bold text-center uppercase align-middle transition-all bg-transparent border border-solid rounded-lg shadow-none cursor-pointer leading-pro text-sm ease-soft-in tracking-tight-soft bg-gradient-to-tl from-purple-700 to-pink-500 bg-150 bg-x-25 border-purple-700 text-white hover:scale-102 hover:shadow-soft-xs active:opacity-85">
                                    <i class="fas fa-plus mr-2"></i>
                                    Registrar Notas
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Filtros -->
                    <div class="p-6 pt-4">
                        <form method="GET" action="{{ route('notas.index') }}" class="flex items-center space-x-4 mb-4">
                            <!-- Filtro por asignatura -->
                            <div class="flex items-center space-x-2">
                                <label for="asignatura_id" class="text-sm font-medium text-slate-600">
                                    <i class="fas fa-book mr-1"></i>
                                    Asignatura:
                                </label>
                                <select name="asignatura_id" id="asignatura_id" 
                                        class="px-3 py-2 text-sm bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-purple-200 focus:border-purple-300">
                                    <option value="">Todas las asignaturas</option>
                                    @foreach($asignaturasDocente as $asignatura)
                                        <option value="{{ $asignatura->id }}" 
                                                {{ request('asignatura_id') == $asignatura->id ? 'selected' : '' }}>
                                            {{ $asignatura->nombre }} ({{ $asignatura->codigo }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Búsqueda por estudiante -->
                            <div class="flex items-center space-x-2">
                                <label for="search" class="text-sm font-medium text-slate-600">
                                    <i class="fas fa-search mr-1"></i>
                                    Estudiante:
                                </label>
                                <input type="text" name="search" id="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Buscar por nombre o email..."
                                       class="px-3 py-2 text-sm bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-purple-200 focus:border-purple-300">
                            </div>

                            <button type="submit" 
                                    class="px-4 py-2 text-sm bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-all duration-200">
                                <i class="fas fa-filter mr-1"></i>
                                Filtrar
                            </button>

                            @if(request('asignatura_id') || request('search'))
                                <a href="{{ route('notas.index') }}" 
                                   class="px-4 py-2 text-sm bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-all duration-200">
                                    <i class="fas fa-times mr-1"></i>
                                    Limpiar
                                </a>
                            @endif
                        </form>
                    </div>

                    <!-- Tabla de notas -->
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-0 overflow-x-auto">
                            <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                                <thead class="align-bottom">
                                    <tr>
                                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-sm border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            ESTUDIANTE
                                        </th>
                                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-sm border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            ASIGNATURA
                                        </th>
                                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-sm border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            NOTA 1
                                        </th>
                                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-sm border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            NOTA 2
                                        </th>
                                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-sm border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            NOTA 3
                                        </th>
                                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-sm border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            PROMEDIO
                                        </th>
                                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-sm border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            ESTADO
                                        </th>
                                        <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-gray-200 border-solid shadow-none tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            ACCIONES
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($notas as $nota)
                                        <tr class="hover:bg-gray-50 transition-all duration-200">
                                            <!-- Estudiante -->
                                            <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <div class="flex px-2 py-1">
                                                    <div class="flex flex-col justify-center">
                                                        <h6 class="mb-0 text-sm leading-normal font-semibold">{{ $nota->estudiante->name }}</h6>
                                                        <p class="mb-0 text-xs leading-tight text-slate-400">{{ $nota->estudiante->email }}</p>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Asignatura -->
                                            <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <div class="flex flex-col">
                                                    <span class="text-sm font-semibold text-slate-700">{{ $nota->asignatura->nombre }}</span>
                                                    <span class="text-xs text-slate-400">{{ $nota->asignatura->codigo }}</span>
                                                </div>
                                            </td>

                                            <!-- Notas -->
                                            <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <span class="text-sm font-semibold text-slate-700">{{ number_format($nota->nota_1, 2) }}</span>
                                            </td>
                                            <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <span class="text-sm font-semibold text-slate-700">{{ number_format($nota->nota_2, 2) }}</span>
                                            </td>
                                            <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <span class="text-sm font-semibold text-slate-700">{{ number_format($nota->nota_3, 2) }}</span>
                                            </td>

                                            <!-- Promedio -->
                                            <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <span class="text-sm font-bold 
                                                    {{ $nota->promedio >= 14.5 ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ number_format($nota->promedio, 2) }}
                                                </span>
                                            </td>

                                            <!-- Estado -->
                                            <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full
                                                    {{ $nota->estado_final === 'aprobado' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ strtoupper($nota->estado_final) }}
                                                </span>
                                            </td>

                                            <!-- Acciones -->
                                            <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <div class="flex items-center space-x-2">
                                                    <a href="{{ route('notas.show', $nota->id) }}"
                                                        class="px-3 py-1.5 text-xs font-semibold text-white bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg hover:from-indigo-600 hover:to-indigo-700 transition-all duration-200">
                                                        <i class="fas fa-eye mr-1"></i>
                                                        Ver
                                                    </a>
                                                    <a href="{{ route('notas.edit', $nota->id) }}"
                                                        class="px-3 py-1.5 text-xs font-semibold text-white bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200">
                                                        <i class="fas fa-edit mr-1"></i>
                                                        Editar
                                                    </a>
                                                    <button type="button" onclick="openModal('delete-nota-{{ $nota->id }}-modal')"
                                                        class="px-3 py-1.5 text-xs font-semibold text-white bg-gradient-to-r from-red-500 to-red-600 rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200">
                                                        <i class="fas fa-trash mr-1"></i>
                                                        Eliminar
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="p-4 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <div class="flex flex-col items-center py-8">
                                                    <i class="fas fa-clipboard-list text-4xl text-slate-300 mb-4"></i>
                                                    <p class="text-xl font-medium text-slate-500">No hay notas registradas</p>
                                                    <p class="text-lg text-slate-400">
                                                        @if(request('asignatura_id') || request('search'))
                                                            No se encontraron notas que coincidan con los filtros aplicados.
                                                        @else
                                                            Aún no has registrado notas para tus estudiantes.
                                                        @endif
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <!-- Paginación -->
                            @if($notas->hasPages())
                                <div class="flex justify-between items-center mt-6 p-6 pt-0">
                                    <div class="flex-1">
                                        {{ $notas->appends(request()->query())->links() }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modales de Confirmación de Eliminación -->
    @if($notas->count() > 0)
        @foreach($notas as $nota)
            @include('components.delete-modal-with-motivo', [
                'modalId' => 'delete-nota-' . $nota->id . '-modal',
                'title' => 'Confirmar Eliminación de Notas',
                'message' => '¿Estás seguro de que deseas eliminar las notas de este estudiante?',
                'itemName' => $nota->estudiante->name,
                'itemDetails' => 'Asignatura: ' . $nota->asignatura->nombre . ' | Promedio: ' . number_format($nota->promedio, 2),
                'deleteRoute' => route('notas.destroy', $nota->id),
                'confirmText' => 'Eliminar Notas'
            ])
        @endforeach
    @endif
@endsection

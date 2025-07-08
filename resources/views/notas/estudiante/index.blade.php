@extends('layouts.app')

@section('content')
    <div class="w-full px-6 py-6 mx-auto text-lg">
        <div class="container-fluid py-4">
            <h1 class="mb-0 text-2xl font-semibold text-slate-700">MIS NOTAS</h1>
            <p class="text-slate-500">Consulta tus calificaciones y promedios</p>
        </div>

        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    
                    <!-- Header del panel -->
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <div class="flex justify-between items-center">
                            <div>
                                <h6 class="mb-0 text-xl font-semibold text-slate-700">
                                    <i class="fas fa-graduation-cap mr-2 text-slate-700"></i>
                                    TUS CALIFICACIONES
                                </h6>
                                <p class="text-sm text-slate-500 mt-1">
                                    Total de asignaturas: {{ $notas->total() }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Resumen de rendimiento -->
                    @if($notas->count() > 0)
                        <div class="p-6 pt-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                <!-- Promedio General -->
                                <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-4 rounded-xl border border-blue-200">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-chart-line text-2xl text-blue-600"></i>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-blue-600">Promedio General</p>
                                            <p class="text-2xl font-bold text-blue-800">
                                                {{ number_format($notas->avg('promedio'), 2) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Asignaturas Aprobadas -->
                                <div class="bg-gradient-to-r from-green-50 to-green-100 p-4 rounded-xl border border-green-200">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-check-circle text-2xl text-green-600"></i>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-green-600">Aprobadas</p>
                                            <p class="text-2xl font-bold text-green-800">
                                                {{ $notas->where('estado_final', 'aprobado')->count() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Asignaturas Reprobadas -->
                                <div class="bg-gradient-to-r from-red-50 to-red-100 p-4 rounded-xl border border-red-200">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-times-circle text-2xl text-red-600"></i>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-red-600">Reprobadas</p>
                                            <p class="text-2xl font-bold text-red-800">
                                                {{ $notas->where('estado_final', 'reprobado')->count() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Tabla de notas -->
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-0 overflow-x-auto">
                            <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                                <thead class="align-bottom">
                                    <tr>
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
                                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-sm border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            FECHA
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($notas as $nota)
                                        <tr class="hover:bg-gray-50 transition-all duration-200">
                                            <!-- Asignatura -->
                                            <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <div class="flex flex-col px-2 py-1">
                                                    <h6 class="mb-0 text-sm leading-normal font-semibold text-slate-700">{{ $nota->asignatura->nombre }}</h6>
                                                    <p class="mb-0 text-xs leading-tight text-slate-400">{{ $nota->asignatura->codigo }}</p>
                                                </div>
                                            </td>

                                            <!-- Notas individuales -->
                                            <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <span class="inline-block px-3 py-1 text-sm font-semibold rounded-lg
                                                    {{ $nota->nota_1 >= 14.5 ? 'bg-green-100 text-green-800' : ($nota->nota_1 >= 10 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                    {{ number_format($nota->nota_1, 2) }}
                                                </span>
                                            </td>
                                            <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <span class="inline-block px-3 py-1 text-sm font-semibold rounded-lg
                                                    {{ $nota->nota_2 >= 14.5 ? 'bg-green-100 text-green-800' : ($nota->nota_2 >= 10 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                    {{ number_format($nota->nota_2, 2) }}
                                                </span>
                                            </td>
                                            <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <span class="inline-block px-3 py-1 text-sm font-semibold rounded-lg
                                                    {{ $nota->nota_3 >= 14.5 ? 'bg-green-100 text-green-800' : ($nota->nota_3 >= 10 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                    {{ number_format($nota->nota_3, 2) }}
                                                </span>
                                            </td>

                                            <!-- Promedio -->
                                            <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <span class="inline-block px-4 py-2 text-lg font-bold rounded-lg
                                                    {{ $nota->promedio >= 14.5 ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-red-100 text-red-800 border border-red-300' }}">
                                                    {{ number_format($nota->promedio, 2) }}
                                                </span>
                                            </td>

                                            <!-- Estado -->
                                            <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <span class="inline-block px-3 py-2 text-sm font-bold rounded-full
                                                    {{ $nota->estado_final === 'aprobado' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                                    <i class="fas {{ $nota->estado_final === 'aprobado' ? 'fa-check' : 'fa-times' }} mr-1"></i>
                                                    {{ strtoupper($nota->estado_final) }}
                                                </span>
                                            </td>

                                            <!-- Fecha -->
                                            <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <span class="text-sm text-slate-600">{{ $nota->created_at->format('d/m/Y') }}</span>
                                                <br>
                                                <span class="text-xs text-slate-400">{{ $nota->created_at->format('H:i') }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="p-4 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <div class="flex flex-col items-center py-12">
                                                    <i class="fas fa-graduation-cap text-6xl text-slate-300 mb-6"></i>
                                                    <h3 class="text-2xl font-semibold text-slate-500 mb-2">¡Aún no tienes notas registradas!</h3>
                                                    <p class="text-lg text-slate-400 mb-4">
                                                        Tus calificaciones aparecerán aquí cuando tus docentes las registren.
                                                    </p>
                                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 max-w-md">
                                                        <p class="text-sm text-blue-600">
                                                            <i class="fas fa-info-circle mr-2"></i>
                                                            Las notas se actualizan automáticamente una vez que son ingresadas por tus profesores.
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <!-- Paginación -->
                            @if($notas->hasPages())
                                <div class="flex justify-center mt-6 p-6 pt-0">
                                    {{ $notas->links() }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Mensaje motivacional para estudiantes -->
                    @if($notas->count() > 0)
                        <div class="p-6 border-t border-gray-100">
                            <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-4 rounded-xl border border-purple-200">
                                <div class="flex items-start">
                                    <i class="fas fa-lightbulb text-purple-600 text-xl mt-1 mr-3"></i>
                                    <div>
                                        <h4 class="text-sm font-semibold text-purple-800 mb-1">Recuerda:</h4>
                                        <p class="text-sm text-purple-700">
                                            El promedio mínimo para aprobar es <strong>14.5 puntos</strong>. 
                                            Si tienes dudas sobre alguna calificación, no dudes en consultar con tu docente.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

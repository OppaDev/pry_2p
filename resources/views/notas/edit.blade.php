@extends('layouts.app')

@section('content')
    <div class="w-full px-6 py-6 mx-auto text-lg">
        <div class="container-fluid py-4">
            <h1 class="mb-0 text-2xl font-semibold text-slate-700">EDITAR NOTAS</h1>
            <p class="text-slate-500">Modifica las calificaciones del estudiante</p>
        </div>

        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    
                    <!-- Header -->
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <div class="flex items-center justify-between">
                            <div>
                                <h6 class="mb-0 text-xl font-semibold text-slate-700">
                                    <i class="fas fa-edit mr-2 text-slate-700"></i>
                                    MODIFICAR REGISTRO DE NOTAS
                                </h6>
                                <p class="mb-0 text-sm leading-normal text-slate-400 mt-1">
                                    Estudiante: <span class="font-semibold text-slate-600">{{ $nota->estudiante->name }}</span> |
                                    Asignatura: <span class="font-semibold text-slate-600">{{ $nota->asignatura->nombre }}</span>
                                </p>
                            </div>
                            <a href="{{ route('notas.index') }}" 
                               class="inline-block px-6 py-2 mb-0 font-bold text-center uppercase align-middle transition-all bg-transparent border border-solid rounded-lg shadow-none cursor-pointer leading-pro text-sm ease-soft-in tracking-tight-soft bg-gradient-to-tl from-slate-600 to-slate-300 bg-150 bg-x-25 border-slate-600 text-white hover:scale-102 hover:shadow-soft-xs active:opacity-85">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Volver
                            </a>
                        </div>
                    </div>

                    <div class="flex-auto p-6">
                        <form action="{{ route('notas.update', $nota->id) }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <!-- Alerta de modificación -->
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="flex items-start">
                                    <i class="fas fa-exclamation-triangle text-yellow-600 text-lg mt-1 mr-3"></i>
                                    <div>
                                        <h4 class="text-sm font-semibold text-yellow-800 mb-1">¡Importante!</h4>
                                        <ul class="text-sm text-yellow-700 space-y-1">
                                            <li>• Esta acción modificará las calificaciones existentes</li>
                                            <li>• Debe proporcionar un motivo válido para la auditoría</li>
                                            <li>• El cambio quedará registrado en el historial</li>
                                            <li>• El promedio y estado se recalcularán automáticamente</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Información del registro actual -->
                            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                                <h5 class="text-lg font-semibold text-slate-700 mb-4">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Información Actual
                                </h5>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-sm font-medium text-slate-600">Estudiante:</span>
                                            <span class="text-sm text-slate-800">{{ $nota->estudiante->name }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-sm font-medium text-slate-600">Email:</span>
                                            <span class="text-sm text-slate-800">{{ $nota->estudiante->email }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-sm font-medium text-slate-600">Asignatura:</span>
                                            <span class="text-sm text-slate-800">{{ $nota->asignatura->nombre }}</span>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-sm font-medium text-slate-600">Promedio Actual:</span>
                                            <span class="text-sm font-bold {{ $nota->promedio >= 14.5 ? 'text-green-600' : 'text-red-600' }}">
                                                {{ number_format($nota->promedio, 2) }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-sm font-medium text-slate-600">Estado Actual:</span>
                                            <span class="text-sm font-bold {{ $nota->estado_final === 'aprobado' ? 'text-green-600' : 'text-red-600' }}">
                                                {{ strtoupper($nota->estado_final) }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-sm font-medium text-slate-600">Registrado:</span>
                                            <span class="text-sm text-slate-800">{{ $nota->created_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Nuevas calificaciones -->
                            <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                                <h5 class="text-lg font-semibold text-slate-700 mb-4">
                                    <i class="fas fa-edit mr-2"></i>
                                    Nuevas Calificaciones (Escala: 0 - 20 puntos)
                                </h5>
                                
                                <div class="flex flex-wrap -mx-3">
                                    <div class="w-full max-w-full px-3 md:w-4/12 md:flex-none">
                                        <div class="mb-4">
                                            <label for="nota_1" class="inline-block mb-2 ml-1 font-bold text-sm text-slate-700">
                                                Primera Nota <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <input type="number" id="nota_1" name="nota_1" 
                                                       value="{{ old('nota_1', $nota->nota_1) }}" 
                                                       min="0" max="20" step="0.01" required
                                                       class="focus:shadow-soft-primary-outline text-lg leading-6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-blue-300 focus:outline-none focus:transition-shadow">
                                                <div class="absolute right-3 top-2 text-xs text-slate-400">
                                                    Anterior: {{ number_format($nota->nota_1, 2) }}
                                                </div>
                                            </div>
                                            @error('nota_1')
                                                <p class="mb-0 text-sm leading-tight text-red-500 mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="w-full max-w-full px-3 md:w-4/12 md:flex-none">
                                        <div class="mb-4">
                                            <label for="nota_2" class="inline-block mb-2 ml-1 font-bold text-sm text-slate-700">
                                                Segunda Nota <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <input type="number" id="nota_2" name="nota_2" 
                                                       value="{{ old('nota_2', $nota->nota_2) }}" 
                                                       min="0" max="20" step="0.01" required
                                                       class="focus:shadow-soft-primary-outline text-lg leading-6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-blue-300 focus:outline-none focus:transition-shadow">
                                                <div class="absolute right-3 top-2 text-xs text-slate-400">
                                                    Anterior: {{ number_format($nota->nota_2, 2) }}
                                                </div>
                                            </div>
                                            @error('nota_2')
                                                <p class="mb-0 text-sm leading-tight text-red-500 mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="w-full max-w-full px-3 md:w-4/12 md:flex-none">
                                        <div class="mb-4">
                                            <label for="nota_3" class="inline-block mb-2 ml-1 font-bold text-sm text-slate-700">
                                                Tercera Nota <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <input type="number" id="nota_3" name="nota_3" 
                                                       value="{{ old('nota_3', $nota->nota_3) }}" 
                                                       min="0" max="20" step="0.01" required
                                                       class="focus:shadow-soft-primary-outline text-lg leading-6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-blue-300 focus:outline-none focus:transition-shadow">
                                                <div class="absolute right-3 top-2 text-xs text-slate-400">
                                                    Anterior: {{ number_format($nota->nota_3, 2) }}
                                                </div>
                                            </div>
                                            @error('nota_3')
                                                <p class="mb-0 text-sm leading-tight text-red-500 mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Calculadora de promedio en tiempo real -->
                                <div class="bg-white border border-gray-300 rounded-lg p-4 mt-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <span class="text-sm font-medium text-slate-600">Nuevo promedio:</span>
                                            <p id="promedio-preview" class="text-2xl font-bold text-slate-700">{{ number_format($nota->promedio, 2) }}</p>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-slate-600">Nuevo estado:</span>
                                            <p id="estado-preview" class="text-lg font-semibold {{ $nota->estado_final === 'aprobado' ? 'text-green-600' : 'text-red-600' }}">
                                                {{ strtoupper($nota->estado_final) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Motivo de la modificación -->
                            <div class="bg-red-50 rounded-lg p-6 border border-red-200">
                                <h5 class="text-lg font-semibold text-slate-700 mb-4">
                                    <i class="fas fa-clipboard-list mr-2"></i>
                                    Motivo de la Modificación <span class="text-red-500">*</span>
                                </h5>
                                
                                <div class="mb-4">
                                    <label for="motivo" class="inline-block mb-2 ml-1 font-bold text-sm text-slate-700">
                                        Describa detalladamente el motivo de esta modificación
                                    </label>
                                    <textarea id="motivo" name="motivo" rows="4" required
                                              class="focus:shadow-soft-primary-outline text-lg leading-6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-red-300 focus:outline-none focus:transition-shadow"
                                              placeholder="Ej: Corrección de error en el cálculo de la segunda evaluación. La nota original fue ingresada incorrectamente debido a...">{{ old('motivo') }}</textarea>
                                    @error('motivo')
                                        <p class="mb-0 text-sm leading-tight text-red-500 mt-2">{{ $message }}</p>
                                    @enderror
                                    <p class="text-xs text-slate-500 mt-1">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Mínimo 10 caracteres. Este motivo será registrado en el historial de auditoría.
                                    </p>
                                </div>
                            </div>

                            <!-- Botones de acción -->
                            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                                <a href="{{ route('notas.index') }}" 
                                   class="inline-block px-6 py-3 font-bold text-center text-slate-700 uppercase align-middle transition-all rounded-lg cursor-pointer bg-gray-200 hover:bg-gray-300 leading-pro text-sm ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85">
                                    <i class="fas fa-times mr-2"></i>
                                    Cancelar
                                </a>
                                <button type="submit"
                                        class="inline-block px-8 py-3 mb-0 font-bold text-center uppercase align-middle transition-all bg-transparent border border-solid rounded-lg shadow-none cursor-pointer leading-pro text-sm ease-soft-in tracking-tight-soft bg-gradient-to-tl from-orange-600 to-orange-400 bg-150 bg-x-25 border-orange-600 text-white hover:scale-102 hover:shadow-soft-xs active:opacity-85">
                                    <i class="fas fa-save mr-2"></i>
                                    Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Calculadora de promedio en tiempo real
        document.addEventListener('DOMContentLoaded', function() {
            const nota1Input = document.getElementById('nota_1');
            const nota2Input = document.getElementById('nota_2');
            const nota3Input = document.getElementById('nota_3');
            const promedioPreview = document.getElementById('promedio-preview');
            const estadoPreview = document.getElementById('estado-preview');

            function calcularPromedio() {
                const nota1 = parseFloat(nota1Input.value) || 0;
                const nota2 = parseFloat(nota2Input.value) || 0;
                const nota3 = parseFloat(nota3Input.value) || 0;

                const promedio = (nota1 + nota2 + nota3) / 3;
                promedioPreview.textContent = promedio.toFixed(2);
                
                if (promedio >= 14.5) {
                    estadoPreview.textContent = 'APROBADO';
                    estadoPreview.className = 'text-lg font-semibold text-green-600';
                } else {
                    estadoPreview.textContent = 'REPROBADO';
                    estadoPreview.className = 'text-lg font-semibold text-red-600';
                }
            }

            nota1Input.addEventListener('input', calcularPromedio);
            nota2Input.addEventListener('input', calcularPromedio);
            nota3Input.addEventListener('input', calcularPromedio);

            // Validación de rango
            [nota1Input, nota2Input, nota3Input].forEach(input => {
                input.addEventListener('blur', function() {
                    const value = parseFloat(this.value);
                    if (value < 0) {
                        this.value = '0.00';
                    } else if (value > 20) {
                        this.value = '20.00';
                    }
                    calcularPromedio();
                });
            });
        });
    </script>
@endsection

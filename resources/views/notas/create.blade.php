@extends('layouts.app')

@section('content')
    <div class="w-full px-6 py-6 mx-auto text-lg">
        <div class="container-fluid py-4">
            <h1 class="mb-0 text-2xl font-semibold text-slate-700">REGISTRAR NOTAS</h1>
            <p class="text-slate-500">Ingresa las calificaciones de un estudiante</p>
        </div>

        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    
                    <!-- Header -->
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <div class="flex items-center justify-between">
                            <div>
                                <h6 class="mb-0 text-xl font-semibold text-slate-700">
                                    <i class="fas fa-plus-circle mr-2 text-slate-700"></i>
                                    NUEVO REGISTRO DE NOTAS
                                </h6>
                                <p class="mb-0 text-sm leading-normal text-slate-400 mt-1">
                                    Complete todos los campos para registrar las calificaciones
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
                        <form action="{{ route('notas.store') }}" method="POST" class="space-y-6">
                            @csrf

                            <!-- Información importante -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-start">
                                    <i class="fas fa-info-circle text-blue-600 text-lg mt-1 mr-3"></i>
                                    <div>
                                        <h4 class="text-sm font-semibold text-blue-800 mb-1">Información importante:</h4>
                                        <ul class="text-sm text-blue-700 space-y-1">
                                            <li>• Las notas deben estar en escala de 0 a 20 puntos</li>
                                            <li>• El promedio se calcula automáticamente</li>
                                            <li>• Se aprueba con promedio ≥ 14.5 puntos</li>
                                            <li>• Solo puedes registrar notas para tus asignaturas asignadas</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Primera fila: Estudiante y Asignatura -->
                            <div class="flex flex-wrap -mx-3">
                                <div class="w-full max-w-full px-3 md:w-6/12 md:flex-none">
                                    <div class="mb-4">
                                        <label for="user_id" class="inline-block mb-2 ml-1 font-bold text-sm text-slate-700">
                                            Estudiante <span class="text-red-500">*</span>
                                        </label>
                                        <select name="user_id" id="user_id" required
                                                class="focus:shadow-soft-primary-outline text-lg leading-6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-purple-300 focus:outline-none focus:transition-shadow">
                                            <option value="">Seleccione un estudiante</option>
                                            @foreach($estudiantes as $estudiante)
                                                <option value="{{ $estudiante->id }}" {{ old('user_id') == $estudiante->id ? 'selected' : '' }}>
                                                    {{ $estudiante->name }} ({{ $estudiante->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                            <p class="mb-0 text-sm leading-tight text-red-500 mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="w-full max-w-full px-3 md:w-6/12 md:flex-none">
                                    <div class="mb-4">
                                        <label for="asignatura_id" class="inline-block mb-2 ml-1 font-bold text-sm text-slate-700">
                                            Asignatura <span class="text-red-500">*</span>
                                        </label>
                                        <select name="asignatura_id" id="asignatura_id" required
                                                class="focus:shadow-soft-primary-outline text-lg leading-6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-purple-300 focus:outline-none focus:transition-shadow">
                                            <option value="">Seleccione una asignatura</option>
                                            @foreach($asignaturas as $asignatura)
                                                <option value="{{ $asignatura->id }}" 
                                                        {{ (old('asignatura_id') ?? $asignaturaSeleccionada) == $asignatura->id ? 'selected' : '' }}>
                                                    {{ $asignatura->nombre }} ({{ $asignatura->codigo }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('asignatura_id')
                                            <p class="mb-0 text-sm leading-tight text-red-500 mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Segunda fila: Las tres notas -->
                            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                                <h5 class="text-lg font-semibold text-slate-700 mb-4">
                                    <i class="fas fa-edit mr-2"></i>
                                    Calificaciones (Escala: 0 - 20 puntos)
                                </h5>
                                
                                <div class="flex flex-wrap -mx-3">
                                    <div class="w-full max-w-full px-3 md:w-4/12 md:flex-none">
                                        <div class="mb-4">
                                            <label for="nota_1" class="inline-block mb-2 ml-1 font-bold text-sm text-slate-700">
                                                Primera Nota <span class="text-red-500">*</span>
                                            </label>
                                            <input type="number" id="nota_1" name="nota_1" 
                                                   value="{{ old('nota_1') }}" 
                                                   min="0" max="20" step="0.01" required
                                                   class="focus:shadow-soft-primary-outline text-lg leading-6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-purple-300 focus:outline-none focus:transition-shadow"
                                                   placeholder="Ej: 15.50">
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
                                            <input type="number" id="nota_2" name="nota_2" 
                                                   value="{{ old('nota_2') }}" 
                                                   min="0" max="20" step="0.01" required
                                                   class="focus:shadow-soft-primary-outline text-lg leading-6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-purple-300 focus:outline-none focus:transition-shadow"
                                                   placeholder="Ej: 16.75">
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
                                            <input type="number" id="nota_3" name="nota_3" 
                                                   value="{{ old('nota_3') }}" 
                                                   min="0" max="20" step="0.01" required
                                                   class="focus:shadow-soft-primary-outline text-lg leading-6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-purple-300 focus:outline-none focus:transition-shadow"
                                                   placeholder="Ej: 14.25">
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
                                            <label class="text-sm font-medium text-slate-600">Vista previa del promedio:</label>
                                            <p id="promedio-preview" class="text-2xl font-bold text-slate-700">--</p>
                                        </div>
                                        <div>
                                            <label class="text-sm font-medium text-slate-600">Estado estimado:</label>
                                            <p id="estado-preview" class="text-lg font-semibold">--</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones de acción -->
                            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                                <button type="reset" 
                                        class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-slate-600 to-slate-300 leading-pro text-sm ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                    <i class="fas fa-undo mr-2"></i>
                                    Limpiar
                                </button>
                                <button type="submit"
                                        class="inline-block px-8 py-3 mb-0 font-bold text-center uppercase align-middle transition-all bg-transparent border border-solid rounded-lg shadow-none cursor-pointer leading-pro text-sm ease-soft-in tracking-tight-soft bg-gradient-to-tl from-purple-700 to-pink-500 bg-150 bg-x-25 border-purple-700 text-white hover:scale-102 hover:shadow-soft-xs active:opacity-85">
                                    <i class="fas fa-save mr-2"></i>
                                    Registrar Notas
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

                if (nota1 > 0 || nota2 > 0 || nota3 > 0) {
                    const promedio = (nota1 + nota2 + nota3) / 3;
                    promedioPreview.textContent = promedio.toFixed(2);
                    
                    if (promedio >= 14.5) {
                        estadoPreview.textContent = 'APROBADO';
                        estadoPreview.className = 'text-lg font-semibold text-green-600';
                    } else {
                        estadoPreview.textContent = 'REPROBADO';
                        estadoPreview.className = 'text-lg font-semibold text-red-600';
                    }
                } else {
                    promedioPreview.textContent = '--';
                    estadoPreview.textContent = '--';
                    estadoPreview.className = 'text-lg font-semibold text-slate-500';
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

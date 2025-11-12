@extends('layouts.app')

@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <!-- Header -->
    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <div class="flex items-center justify-between">
                        <h6 class="font-bold text-xl">Gestión de Clientes</h6>
                        @can('clientes.crear')
                        <a href="{{ route('clientes.create') }}" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all bg-gradient-to-tl from-purple-700 to-pink-500 rounded-lg cursor-pointer leading-pro text-sm ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 hover:shadow-soft-xs active:opacity-85">
                            <i class="fas fa-plus mr-2"></i>Nuevo Cliente
                        </a>
                        @endcan
                    </div>
                </div>
                
                <!-- Filtros -->
                <div class="p-6 border-b border-gray-200">
                    <form method="GET" action="{{ route('clientes.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Buscar por Identificación</label>
                            <input type="text" name="buscar_identificacion" value="{{ request('buscar_identificacion') }}" 
                                   class="focus:shadow-soft-primary-outline text-base leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" 
                                   placeholder="Cédula o RUC">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Buscar por Nombre</label>
                            <input type="text" name="buscar_nombre" value="{{ request('buscar_nombre') }}" 
                                   class="focus:shadow-soft-primary-outline text-base leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" 
                                   placeholder="Nombre o apellido">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Tipo Identificación</label>
                            <select name="tipo_identificacion" class="focus:shadow-soft-primary-outline text-base leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow">
                                <option value="">Todos</option>
                                <option value="cedula" {{ request('tipo_identificacion') == 'cedula' ? 'selected' : '' }}>Cédula</option>
                                <option value="ruc" {{ request('tipo_identificacion') == 'ruc' ? 'selected' : '' }}>RUC</option>
                                <option value="pasaporte" {{ request('tipo_identificacion') == 'pasaporte' ? 'selected' : '' }}>Pasaporte</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Estado</label>
                            <select name="estado" class="focus:shadow-soft-primary-outline text-base leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow">
                                <option value="">Activos</option>
                                <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                        
                        <div class="md:col-span-4 flex gap-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="solo_mayores_edad" value="1" {{ request('solo_mayores_edad') ? 'checked' : '' }} 
                                       class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-slate-700">Solo mayores de 18 años</span>
                            </label>
                            
                            <button type="submit" class="inline-block px-6 py-2 font-bold text-center text-white uppercase align-middle transition-all bg-gradient-to-tl from-purple-700 to-pink-500 rounded-lg cursor-pointer leading-pro text-sm ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 hover:shadow-soft-xs active:opacity-85">
                                <i class="fas fa-search mr-2"></i>Buscar
                            </button>
                            
                            <a href="{{ route('clientes.index') }}" class="inline-block px-6 py-2 font-bold text-center text-slate-700 uppercase align-middle transition-all bg-white border border-slate-300 rounded-lg cursor-pointer leading-pro text-sm ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 hover:shadow-soft-xs active:opacity-85">
                                <i class="fas fa-times mr-2"></i>Limpiar
                            </a>
                        </div>
                    </form>
                </div>
                
                <div class="flex-auto px-0 pt-0 pb-2">
                    <div class="p-0 overflow-x-auto">
                        <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                            <thead class="align-bottom">
                                <tr>
                                    <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-base border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Identificación</th>
                                    <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-base border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Cliente</th>
                                    <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-base border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Edad</th>
                                    <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-base border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Contacto</th>
                                    <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-base border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Estado</th>
                                    <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-base border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($clientes as $cliente)
                                <tr>
                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <div class="px-3 py-1">
                                            <p class="mb-0 font-semibold leading-tight text-base">{{ $cliente->tipo_identificacion }}</p>
                                            <p class="mb-0 leading-tight text-base text-slate-400">{{ $cliente->identificacion }}</p>
                                        </div>
                                    </td>
                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <div class="px-3 py-1">
                                            <p class="mb-0 font-semibold leading-tight text-base">{{ $cliente->nombre_completo }}</p>
                                            <p class="mb-0 leading-tight text-base text-slate-400">{{ \Carbon\Carbon::parse($cliente->fecha_nacimiento)->format('d/m/Y') }}</p>
                                        </div>
                                    </td>
                                    <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <span class="text-base font-semibold leading-tight text-slate-700">{{ $cliente->edad }} años</span>
                                        @if($cliente->es_mayor_edad)
                                            <span class="ml-2 text-xs font-semibold text-green-600"><i class="fas fa-check-circle"></i></span>
                                        @else
                                            <span class="ml-2 text-xs font-semibold text-red-600"><i class="fas fa-times-circle"></i></span>
                                        @endif
                                    </td>
                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <div class="px-3 py-1">
                                            <p class="mb-0 text-base text-slate-700">{{ $cliente->telefono }}</p>
                                            <p class="mb-0 text-base text-slate-400">{{ $cliente->correo }}</p>
                                        </div>
                                    </td>
                                    <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <span class="bg-gradient-to-tl {{ $cliente->estado == 'activo' ? 'from-green-600 to-lime-400' : 'from-slate-600 to-slate-300' }} px-3.6 text-sm rounded-1.8 py-2.2 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">
                                            {{ ucfirst($cliente->estado) }}
                                        </span>
                                    </td>
                                    <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <a href="{{ route('clientes.show', $cliente) }}" class="text-base font-semibold leading-tight text-slate-400 hover:text-slate-700 mr-2" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @can('clientes.editar')
                                        <a href="{{ route('clientes.edit', $cliente) }}" class="text-base font-semibold leading-tight text-slate-400 hover:text-slate-700 mr-2" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endcan
                                        @can('clientes.eliminar')
                                        <button onclick="openModal('delete-modal-{{ $cliente->id }}')" class="text-base font-semibold leading-tight text-slate-400 hover:text-red-600" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endcan
                                    </td>
                                </tr>
                                
                                <!-- Modal Eliminar -->
                                @can('clientes.eliminar')
                                <div id="delete-modal-{{ $cliente->id }}" class="hidden modal-backdrop fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                                    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                                        <div class="mt-3">
                                            <div class="flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mx-auto">
                                                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                                            </div>
                                            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4 text-center">Eliminar Cliente</h3>
                                            <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="mt-4">
                                                @csrf
                                                @method('DELETE')
                                                <div class="mb-4">
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Motivo de eliminación</label>
                                                    <textarea name="motivo" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500"></textarea>
                                                </div>
                                                <div class="mb-4">
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirme su contraseña</label>
                                                    <input type="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                                                </div>
                                                <div class="flex gap-2 justify-center">
                                                    <button type="button" onclick="closeModal('delete-modal-{{ $cliente->id }}')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Cancelar</button>
                                                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Eliminar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endcan
                                @empty
                                <tr>
                                    <td colspan="6" class="p-4 text-center text-slate-400">
                                        No se encontraron clientes.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Paginación -->
                    @if($clientes->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $clientes->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <div class="flex items-center justify-between">
                        <h6 class="font-bold text-xl">Papelera de Clientes</h6>
                        <a href="{{ route('clientes.index') }}" class="inline-block px-6 py-3 font-bold text-center text-slate-700 uppercase align-middle transition-all bg-white border border-slate-300 rounded-lg cursor-pointer leading-pro text-sm ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 hover:shadow-soft-xs active:opacity-85">
                            <i class="fas fa-arrow-left mr-2"></i>Volver a Clientes
                        </a>
                    </div>
                </div>
                
                <div class="flex-auto px-0 pt-0 pb-2">
                    <div class="p-0 overflow-x-auto">
                        <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                            <thead class="align-bottom">
                                <tr>
                                    <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-base border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Cliente</th>
                                    <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-base border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Identificación</th>
                                    <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-base border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Fecha Eliminación</th>
                                    <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-base border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($clientes as $cliente)
                                <tr>
                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <div class="px-3 py-1">
                                            <p class="mb-0 font-semibold leading-tight text-base">{{ $cliente->nombre_completo }}</p>
                                            <p class="mb-0 leading-tight text-base text-slate-400">{{ $cliente->correo }}</p>
                                        </div>
                                    </td>
                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <p class="mb-0 text-base font-semibold text-slate-700">{{ $cliente->identificacion }}</p>
                                    </td>
                                    <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <span class="text-base font-semibold text-slate-700">{{ \Carbon\Carbon::parse($cliente->deleted_at)->format('d/m/Y H:i') }}</span>
                                    </td>
                                    <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        @can('clientes.restaurar')
                                        <button onclick="openModal('restore-modal-{{ $cliente->id }}')" class="text-base font-semibold leading-tight text-blue-600 hover:text-blue-800 mr-2" title="Restaurar">
                                            <i class="fas fa-undo"></i> Restaurar
                                        </button>
                                        @endcan
                                    </td>
                                </tr>
                                
                                <!-- Modal Restaurar -->
                                @can('clientes.restaurar')
                                <div id="restore-modal-{{ $cliente->id }}" class="hidden modal-backdrop fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                                    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                                        <div class="mt-3">
                                            <div class="flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 mx-auto">
                                                <i class="fas fa-undo text-blue-600 text-xl"></i>
                                            </div>
                                            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4 text-center">Restaurar Cliente</h3>
                                            <form action="{{ route('clientes.restore', $cliente->id) }}" method="POST" class="mt-4">
                                                @csrf
                                                @method('PATCH')
                                                <p class="text-sm text-gray-500 text-center mb-4">¿Está seguro que desea restaurar este cliente?</p>
                                                <div class="flex gap-2 justify-center">
                                                    <button type="button" onclick="closeModal('restore-modal-{{ $cliente->id }}')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Cancelar</button>
                                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Restaurar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endcan
                                @empty
                                <tr>
                                    <td colspan="4" class="p-4 text-center text-slate-400">
                                        No hay clientes eliminados.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
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

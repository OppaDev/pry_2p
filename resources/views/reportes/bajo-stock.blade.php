@extends('layouts.app')

@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <div class="container-fluid py-4">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="mb-1 text-2xl font-semibold text-slate-700">PRODUCTOS CON BAJO STOCK</h1>
                <p class="text-sm text-slate-500">Alertas de productos que requieren reposición inmediata</p>
            </div>
            <a href="{{ route('reportes.index') }}" class="px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Volver
            </a>
        </div>

        @if(isset($datos))
        <!-- Estadísticas de Alerta -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <!-- Total Productos Afectados -->
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-4">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-12 h-12 text-center bg-gradient-to-tl from-red-500 to-rose-500 rounded-xl shadow-soft-2xl">
                            <i class="fas fa-exclamation-triangle text-lg text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="mb-0 text-sm font-semibold leading-normal text-slate-500">Productos Afectados</p>
                            <h5 class="mb-0 font-bold text-red-600">{{ number_format($datos['estadisticas']['total_productos_afectados'] ?? 0) }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Unidades Faltantes -->
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-4">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-12 h-12 text-center bg-gradient-to-tl from-orange-500 to-yellow-500 rounded-xl shadow-soft-2xl">
                            <i class="fas fa-boxes text-lg text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="mb-0 text-sm font-semibold leading-normal text-slate-500">Unidades Faltantes</p>
                            <h5 class="mb-0 font-bold text-orange-600">{{ number_format($datos['estadisticas']['total_unidades_faltantes'] ?? 0) }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Valor Total Reposición -->
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-4">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-12 h-12 text-center bg-gradient-to-tl from-purple-500 to-pink-500 rounded-xl shadow-soft-2xl">
                            <i class="fas fa-dollar-sign text-lg text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="mb-0 text-sm font-semibold leading-normal text-slate-500">Valor Reposición</p>
                            <h5 class="mb-0 font-bold text-purple-600">${{ number_format($datos['estadisticas']['valor_total_reposicion'] ?? 0, 2) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerta Importante -->
        @if(isset($datos['productos']) && count($datos['productos']) > 0)
        <div class="relative p-4 mb-6 bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 rounded-2xl shadow-soft-sm">
            <div class="flex items-center">
                <div class="flex items-center justify-center w-12 h-12 text-center bg-gradient-to-tl from-red-500 to-rose-500 rounded-xl shadow-soft-2xl mr-4">
                    <i class="fas fa-bell text-lg text-white animate-pulse"></i>
                </div>
                <div>
                    <h6 class="mb-1 font-bold text-red-700">¡Atención Requerida!</h6>
                    <p class="mb-0 text-sm text-red-600">Hay {{ count($datos['productos']) }} productos que requieren reposición urgente para evitar quiebres de stock.</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Botones de Exportación -->
        <div class="flex justify-end space-x-3 mb-4">
            <form method="GET" action="{{ route('reportes.bajo-stock') }}" class="inline">
                <input type="hidden" name="formato" value="excel">
                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all shadow-sm hover:shadow-md">
                    <i class="fas fa-file-excel mr-2"></i>Exportar Excel
                </button>
            </form>
            <form method="GET" action="{{ route('reportes.bajo-stock') }}" class="inline">
                <input type="hidden" name="formato" value="pdf">
                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all shadow-sm hover:shadow-md">
                    <i class="fas fa-file-pdf mr-2"></i>Exportar PDF
                </button>
            </form>
        </div>

        <!-- Tabla de Productos Bajo Stock -->
        <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                <h6 class="mb-4 text-lg font-semibold text-slate-700">
                    <i class="fas fa-list mr-2 text-slate-600"></i>Productos que Requieren Reposición
                </h6>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-0 overflow-x-auto">
                    <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                        <thead class="align-bottom">
                            <tr>
                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Código</th>
                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Producto</th>
                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Categoría</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Stock Actual</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Stock Mínimo</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Unidades Faltantes</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Precio Unit.</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Valor Faltante</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Criticidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($datos['productos'] as $producto)
                            @php
                                $unidades_faltantes = $producto->unidades_faltantes ?? 0;
                                $valor_faltante = $producto->valor_faltante ?? 0;
                                $porcentaje = $producto->stock_minimo > 0 ? ($producto->stock_actual / $producto->stock_minimo * 100) : 0;
                                
                                if($producto->stock_actual == 0) {
                                    $criticidad = 'CRÍTICO';
                                    $criticidadClass = 'bg-red-500 text-white animate-pulse';
                                } elseif($porcentaje < 50) {
                                    $criticidad = 'URGENTE';
                                    $criticidadClass = 'bg-red-100 text-red-700';
                                } else {
                                    $criticidad = 'BAJO';
                                    $criticidadClass = 'bg-orange-100 text-orange-700';
                                }
                            @endphp
                            <tr class="hover:bg-slate-50 transition-colors {{ $producto->stock_actual == 0 ? 'bg-red-50' : 'bg-yellow-50' }}">
                                <td class="px-6 py-3 align-middle border-b whitespace-nowrap text-sm">
                                    <span class="font-semibold text-slate-700">{{ $producto->codigo }}</span>
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-sm">
                                    <div>
                                        <p class="mb-0 font-semibold text-slate-700">{{ $producto->nombre }}</p>
                                        <p class="mb-0 text-xs text-slate-500">{{ $producto->marca }} - {{ ucfirst($producto->presentacion) }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-sm">
                                    <span class="px-2 py-1 text-xs font-semibold bg-purple-100 text-purple-700 rounded-full">
                                        {{ $producto->categoria->nombre ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-center text-sm">
                                    <span class="font-bold {{ $producto->stock_actual == 0 ? 'text-red-600 text-lg' : 'text-orange-600' }}">
                                        {{ number_format($producto->stock_actual) }}
                                    </span>
                                    @if($producto->stock_actual == 0)
                                        <i class="fas fa-times-circle text-red-500 ml-1"></i>
                                    @else
                                        <i class="fas fa-exclamation-triangle text-orange-500 ml-1"></i>
                                    @endif
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-center text-sm">
                                    <span class="text-slate-600">{{ number_format($producto->stock_minimo) }}</span>
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-center text-sm">
                                    <span class="px-3 py-1 font-bold text-red-600 bg-red-100 rounded-lg">
                                        {{ number_format($unidades_faltantes) }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-center text-sm">
                                    <span class="font-semibold text-slate-700">${{ number_format($producto->precio, 2) }}</span>
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-center text-sm">
                                    <span class="font-bold text-purple-600">${{ number_format($valor_faltante, 2) }}</span>
                                </td>
                                <td class="px-6 py-3 align-middle border-b text-center text-sm">
                                    <span class="px-2 py-1 text-xs font-bold rounded-full {{ $criticidadClass }}">
                                        {{ $criticidad }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="px-6 py-8 text-center text-slate-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="flex items-center justify-center w-16 h-16 mb-4 bg-gradient-to-tl from-green-400 to-lime-400 rounded-full">
                                            <i class="fas fa-check text-2xl text-white"></i>
                                        </div>
                                        <h6 class="mb-2 font-bold text-green-600">¡Todo está bien!</h6>
                                        <p class="mb-0 text-slate-500">No hay productos con stock bajo en este momento.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if(isset($datos['productos']) && count($datos['productos']) > 0)
        <!-- Resumen de Acciones Recomendadas -->
        <div class="relative flex flex-col min-w-0 mt-6 break-words bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-200 shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6">
                <h6 class="mb-4 text-lg font-semibold text-blue-700">
                    <i class="fas fa-lightbulb mr-2"></i>Acciones Recomendadas
                </h6>
                <ul class="space-y-2 text-sm text-slate-700">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                        <span>Generar órdenes de compra para los productos marcados como <strong>CRÍTICO</strong> o <strong>URGENTE</strong></span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                        <span>Revisar el historial de ventas para ajustar los niveles de stock mínimo</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                        <span>Contactar a proveedores para verificar disponibilidad y tiempos de entrega</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                        <span>Presupuesto total estimado para reposición: <strong class="text-purple-600">${{ number_format($datos['estadisticas']['valor_total_reposicion'], 2) }}</strong></span>
                    </li>
                </ul>
            </div>
        </div>
        @endif
        @endif
    </div>
</div>
@endsection

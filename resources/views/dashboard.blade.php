@extends('layouts.app')

@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <!-- Bienvenida -->
    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <div class="flex flex-wrap -mx-3">
                        <div class="flex items-center w-full max-w-full px-3 shrink-0 md:w-8/12 md:flex-none">
                            <h6 class="mb-0 text-xl">¡Bienvenido de vuelta, {{ Auth::user()->name }}! 👋</h6>
                        </div>
                        <div class="w-full max-w-full px-3 text-right shrink-0 md:w-4/12 md:flex-none">
                            <span class="text-base text-slate-400">{{ date('l, j \d\e F \d\e Y') }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex-auto px-0 pt-0 pb-2">
                    <div class="p-6">
                        <p class="leading-normal text-lg text-slate-500">
                            Aquí tienes un resumen general de tu aplicación. Mantén el control de tus usuarios y productos desde este dashboard.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards de Estadísticas -->
    <div class="flex flex-wrap -mx-3">
        <!-- Card 1: Total Usuarios -->
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="flex-auto p-4">
                    <div class="flex flex-row -mx-3">
                        <div class="flex-none w-2/3 max-w-full px-3">
                            <div>
                                <p class="mb-0 font-sans font-semibold leading-normal text-lg">Total Usuarios</p>
                                <h5 class="mb-0 font-bold text-2xl">
                                    {{ number_format($totalUsers) }}
                                    <span class="leading-normal text-lg font-weight-bolder text-lime-500">usuarios</span>
                                </h5>
                            </div>
                        </div>
                        <div class="px-3 text-right basis-1/3">
                            <div class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500">
                                <i class="fas fa-users leading-none ni ni-money-coins text-lg relative top-3.5 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Total Productos -->
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="flex-auto p-4">
                    <div class="flex flex-row -mx-3">
                        <div class="flex-none w-2/3 max-w-full px-3">
                            <div>
                                <p class="mb-0 font-sans font-semibold leading-normal text-lg">Total Productos</p>
                                <h5 class="mb-0 font-bold text-2xl">
                                    {{ number_format($totalProductos) }}
                                    <span class="leading-normal text-lg font-weight-bolder text-cyan-500">productos</span>
                                </h5>
                            </div>
                        </div>
                        <div class="px-3 text-right basis-1/3">
                            <div class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-blue-600 to-cyan-400">
                                <i class="fas fa-box leading-none ni ni-world text-lg relative top-3.5 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Stock Total -->
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="flex-auto p-4">
                    <div class="flex flex-row -mx-3">
                        <div class="flex-none w-2/3 max-w-full px-3">
                            <div>
                                <p class="mb-0 font-sans font-semibold leading-normal text-lg">Stock Total</p>
                                <h5 class="mb-0 font-bold text-2xl">
                                    {{ number_format($totalStock) }}
                                    <span class="leading-normal text-lg font-weight-bolder text-emerald-500">unidades</span>
                                </h5>
                            </div>
                        </div>
                        <div class="px-3 text-right basis-1/3">
                            <div class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-green-600 to-lime-400">
                                <i class="fas fa-warehouse leading-none text-lg relative top-3.5 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Valor del Inventario -->
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="flex-auto p-4">
                    <div class="flex flex-row -mx-3">
                        <div class="flex-none w-2/3 max-w-full px-3">
                            <div>
                                <p class="mb-0 font-sans font-semibold leading-normal text-lg">Valor Inventario</p>
                                <h5 class="mb-0 font-bold text-2xl">
                                    ${{ number_format($valorInventario, 2) }}
                                    <span class="leading-normal text-lg font-weight-bolder text-red-500">total</span>
                                </h5>
                            </div>
                        </div>
                        <div class="px-3 text-right basis-1/3">
                            <div class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-orange-500 to-yellow-500">
                                <i class="fas fa-dollar-sign leading-none text-lg relative top-3.5 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de Acceso Rápido -->
    <div class="flex flex-wrap -mx-3 mt-6">
        <div class="w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <h6 class="mb-0 text-xl">Acceso Rápido</h6>
                </div>
                <div class="flex-auto px-0 pt-0 pb-2">
                    <div class="p-6">
                        <div class="flex flex-wrap -mx-3">
                            <!-- Botón Gestionar Usuarios -->
                            <div class="w-full max-w-full px-3 mb-4 sm:w-1/2 sm:flex-none lg:w-1/3">
                                <a href="{{ route('users.index') }}" class="relative flex flex-col min-w-0 break-words bg-gradient-to-tl from-gray-900 to-slate-800 shadow-soft-xl rounded-2xl bg-clip-border overflow-hidden transition-all hover:scale-105 hover:shadow-soft-2xl">
                                    <div class="flex-auto p-4">
                                        <div class="flex flex-row items-center">
                                            <div class="flex items-center justify-center w-12 h-12 mr-4 text-white bg-white bg-opacity-20 rounded-lg">
                                                <i class="fas fa-users text-lg"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 text-white text-lg">Gestionar Usuarios</h6>
                                                <p class="mb-0 text-base text-white opacity-60">Ver y administrar usuarios</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Botón Gestionar Productos -->
                            <div class="w-full max-w-full px-3 mb-4 sm:w-1/2 sm:flex-none lg:w-1/3">
                                <a href="{{ route('productos.index') }}" class="relative flex flex-col min-w-0 break-words bg-gradient-to-tl from-blue-600 to-cyan-400 shadow-soft-xl rounded-2xl bg-clip-border overflow-hidden transition-all hover:scale-105 hover:shadow-soft-2xl">
                                    <div class="flex-auto p-4">
                                        <div class="flex flex-row items-center">
                                            <div class="flex items-center justify-center w-12 h-12 mr-4 text-white bg-white bg-opacity-20 rounded-lg">
                                                <i class="fas fa-box text-lg"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 text-white text-lg">Gestionar Productos</h6>
                                                <p class="mb-0 text-base text-white opacity-60">Ver y administrar productos</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Botón Perfil -->
                            <div class="w-full max-w-full px-3 mb-4 sm:w-1/2 sm:flex-none lg:w-1/3">
                                <a href="{{ route('profile.edit') }}" class="relative flex flex-col min-w-0 break-words bg-gradient-to-tl from-emerald-500 to-teal-400 shadow-soft-xl rounded-2xl bg-clip-border overflow-hidden transition-all hover:scale-105 hover:shadow-soft-2xl">
                                    <div class="flex-auto p-4">
                                        <div class="flex flex-row items-center">
                                            <div class="flex items-center justify-center w-12 h-12 mr-4 text-white bg-white bg-opacity-20 rounded-lg">
                                                <i class="fas fa-user text-lg"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 text-white text-lg">Mi Perfil</h6>
                                                <p class="mb-0 text-base text-white opacity-60">Configurar mi cuenta</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Botón Auditorías -->
                            <div class="w-full max-w-full px-3 mb-4 sm:w-1/2 sm:flex-none lg:w-1/3">
                                <a href="{{ route('audits.by-user') }}" class="relative flex flex-col min-w-0 break-words bg-gradient-to-tl from-purple-600 to-pink-500 shadow-soft-xl rounded-2xl bg-clip-border overflow-hidden transition-all hover:scale-105 hover:shadow-soft-2xl">
                                    <div class="flex-auto p-4">
                                        <div class="flex flex-row items-center">
                                            <div class="flex items-center justify-center w-12 h-12 mr-4 text-white bg-white bg-opacity-20 rounded-lg">
                                                <i class="fas fa-history text-lg"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 text-white text-lg">Auditorías</h6>
                                                <p class="mb-0 text-base text-white opacity-60">Historial de actividades</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de Actividad Reciente (Opcional - puedes expandir esto más tarde) -->
    <div class="flex flex-wrap -mx-3 mt-6">
        <div class="w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <div class="flex flex-wrap -mx-3">
                        <div class="flex items-center w-full max-w-full px-3 shrink-0 md:w-8/12 md:flex-none">
                            <h6 class="mb-0 text-xl">Resumen del Sistema</h6>
                        </div>
                    </div>
                </div>
                <div class="flex-auto px-0 pt-0 pb-2">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <h6 class="mb-2 font-semibold text-slate-700 text-lg">Estado del Sistema</h6>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                    <span class="text-lg text-slate-500">Sistema funcionando correctamente</span>
                                </div>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <h6 class="mb-2 font-semibold text-slate-700 text-lg">Última Actualización</h6>
                                <span class="text-lg text-slate-500">Hace unos momentos</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, ini                  <ul dropdown-menu class="text-lg duration-250 min-w-44 pointer-events-none absolute right-0 top-0 z-50 origin-top list-none rounded-lg border-0 border-solid border-transparent bg-white bg-clip-padding px-2 py-4 text-left text-slate-500 opacity-0 transition-all lg:absolute lg:right-0 lg:left-auto lg:mt-2 lg:block lg:cursor-pointer lg:shadow-soft-3xl transform-dropdown";ial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Scripts y Estilos de Vite (Ahora controla TODO) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
  </head>

  <body class="m-0 font-sans antialiased font-normal text-lg leading-relaxed bg-gray-50 text-slate-500">
    <!-- 🧭 SIDENAV -->
    <aside class="max-w-62.5 ease-nav-brand z-990 fixed inset-y-0 my-4 ml-4 block w-full -translate-x-full flex-wrap items-center justify-between overflow-y-auto rounded-2xl border-0 bg-white p-0 antialiased shadow-none transition-transform duration-200 xl:left-0 xl:translate-x-0 xl:bg-transparent">
      <div class="h-19.5">
        <i class="absolute top-0 right-0 hidden p-4 opacity-50 cursor-pointer fas fa-times text-slate-400 xl:hidden" sidenav-close></i>
        <a class="block px-8 py-6 m-0 text-lg whitespace-nowrap text-slate-700" href="{{url('/')}}">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <x-application-logo compact="true" class="h-8 w-8" />
            </div>
            <span class="ml-3 font-semibold transition-all duration-200 ease-nav-brand">{{ config('app.name', 'Laravel') }}</span>
          </div>
        </a>
      </div>

      <hr class="h-px mt-0 bg-gradient-to-r from-transparent via-black/40 to-transparent" />

      <div class="items-center block w-auto max-h-screen overflow-auto h-sidenav grow basis-full">
        <ul class="flex flex-col pl-0 mb-0">
          <li class="mt-0.5 w-full">
            <a class="py-2.7 text-lg ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors {{ request()->routeIs('dashboard') ? 'rounded-lg bg-blue-500/13 font-semibold text-slate-700' : 'text-slate-700' }}" href="{{ route('dashboard') }}">
              <div class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                <i class="fas fa-tv text-slate-700"></i>
              </div>
              <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Dashboard</span>
            </a>
          </li>

          @can('clientes.ver')
          <li class="mt-0.5 w-full">
            <a class="py-2.7 text-lg ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors {{ request()->routeIs('clientes.*') ? 'rounded-lg bg-blue-500/13 font-semibold text-slate-700' : 'text-slate-700' }}" href="{{ route('clientes.index') }}">
              <div class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                <i class="fas fa-users text-slate-700"></i>
              </div>
              <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Clientes</span>
            </a>
          </li>
          @endcan

          @can('productos.ver')
          <li class="mt-0.5 w-full">
            <a class="py-2.7 text-lg ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors {{ request()->routeIs('productos.*') || request()->routeIs('categorias.*') ? 'rounded-lg bg-blue-500/13 font-semibold text-slate-700' : 'text-slate-700' }}" 
               onclick="toggleProductosDropdown()" id="productos-trigger">
              <div class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                <i class="fas fa-box text-slate-700"></i>
              </div>
              <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Inventario</span>
              <i class="fas fa-chevron-down ml-auto transition-transform duration-200 text-slate-400" id="productos-chevron"></i>
            </a>
            
            <ul class="pl-0 ml-6 mt-1 list-none h-0 opacity-0 overflow-hidden transition-all duration-300 ease-in-out" id="productos-submenu">
              <li class="mt-0.5 w-full">
                <a class="py-2.7 text-base ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors {{ request()->routeIs('productos.*') && !request()->routeIs('productos.bajos-stock') ? 'rounded-lg bg-blue-500/13 font-semibold text-slate-700' : 'text-slate-600 hover:text-slate-700' }}" 
                   href="{{ route('productos.index') }}">
                  <div class="shadow-soft-2xl mr-2 flex h-6 w-6 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center">
                    <i class="fas fa-box text-xs text-slate-600"></i>
                  </div>
                  <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Productos</span>
                </a>
              </li>
              <li class="mt-0.5 w-full">
                <a class="py-2.7 text-base ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors {{ request()->routeIs('categorias.*') ? 'rounded-lg bg-blue-500/13 font-semibold text-slate-700' : 'text-slate-600 hover:text-slate-700' }}" 
                   href="{{ route('categorias.index') }}">
                  <div class="shadow-soft-2xl mr-2 flex h-6 w-6 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center">
                    <i class="fas fa-tags text-xs text-slate-600"></i>
                  </div>
                  <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Categorías</span>
                </a>
              </li>
              @can('inventario.ver')
              <li class="mt-0.5 w-full">
                <a class="py-2.7 text-base ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors {{ request()->routeIs('productos.bajos-stock') ? 'rounded-lg bg-blue-500/13 font-semibold text-slate-700' : 'text-slate-600 hover:text-slate-700' }}" 
                   href="{{ route('productos.bajos-stock') }}">
                  <div class="shadow-soft-2xl mr-2 flex h-6 w-6 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center">
                    <i class="fas fa-exclamation-triangle text-xs text-slate-600"></i>
                  </div>
                  <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Bajo Stock</span>
                </a>
              </li>
              @endcan
            </ul>
          </li>
          @endcan

          @can('ventas.ver')
          <li class="mt-0.5 w-full">
            <a class="py-2.7 text-lg ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors {{ request()->routeIs('ventas.*') ? 'rounded-lg bg-blue-500/13 font-semibold text-slate-700' : 'text-slate-700' }}" href="{{ route('ventas.index') }}">
              <div class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                <i class="fas fa-cash-register text-slate-700"></i>
              </div>
              <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Ventas</span>
            </a>
          </li>
          @endcan

          @can('facturas.ver')
          <li class="mt-0.5 w-full">
            <a class="py-2.7 text-lg ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors {{ request()->routeIs('facturas.*') ? 'rounded-lg bg-blue-500/13 font-semibold text-slate-700' : 'text-slate-700' }}" href="{{ route('facturas.index') }}">
              <div class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                <i class="fas fa-file-invoice text-slate-700"></i>
              </div>
              <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Facturas</span>
            </a>
          </li>
          @endcan

          @can('usuarios.ver')
          <li class="mt-0.5 w-full">
            <a class="py-2.7 text-lg ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors {{ request()->routeIs('users.*') ? 'rounded-lg bg-blue-500/13 font-semibold text-slate-700' : 'text-slate-700' }}" href="{{ route('users.index') }}">
              <div class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                <i class="fas fa-user-shield text-slate-700"></i>
              </div>
              <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Usuarios</span>
            </a>
          </li>
          @endcan

          <li class="mt-0.5 w-full">
            <a class="py-2.7 text-lg ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors {{ request()->routeIs('audits.*') ? 'rounded-lg bg-blue-500/13 font-semibold text-slate-700' : 'text-slate-700' }}" href="{{ route('audits.by-user') }}">
              <div class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                <i class="fas fa-history text-slate-700"></i>
              </div>
              <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Auditorías</span>
            </a>
          </li>

          <!-- Papelera con dropdown nativo -->
          <li class="mt-0.5 w-full">
            <a class="py-2.7 text-lg ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 cursor-pointer transition-colors {{ request()->routeIs('productos.deleted') ? 'rounded-lg bg-blue-500/13 font-semibold text-slate-700' : 'text-slate-700' }}" 
               onclick="togglePapeleraDropdown()" id="papelera-trigger">
              <div class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                <i class="fas fa-trash text-slate-700"></i>
              </div>
              <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Papelera</span>
              <i class="fas fa-chevron-down ml-auto transition-transform duration-200 text-slate-400" id="papelera-chevron"></i>
            </a>
            
            <!-- Submenu estilo sidebar -->
            <ul class="pl-0 ml-6 mt-1 list-none h-0 opacity-0 overflow-hidden transition-all duration-300 ease-in-out" id="papelera-submenu">
              <li class="mt-0.5 w-full">
                <a class="py-2.7 text-base ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors {{ request()->routeIs('clientes.deleted') ? 'rounded-lg bg-blue-500/13 font-semibold text-slate-700' : 'text-slate-600 hover:text-slate-700' }}" 
                   href="{{ route('clientes.deleted') }}">
                  <div class="shadow-soft-2xl mr-2 flex h-6 w-6 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center">
                    <i class="fas fa-user-times text-xs text-slate-600"></i>
                  </div>
                  <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Papelera Clientes</span>
                </a>
              </li>
              <li class="mt-0.5 w-full">
                <a class="py-2.7 text-base ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors {{ request()->routeIs('users.deleted') ? 'rounded-lg bg-blue-500/13 font-semibold text-slate-700' : 'text-slate-600 hover:text-slate-700' }}" 
                   href="{{ route('users.deleted') }}">
                  <div class="shadow-soft-2xl mr-2 flex h-6 w-6 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center">
                    <i class="fas fa-user-slash text-xs text-slate-600"></i>
                  </div>
                  <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Papelera Usuarios</span>
                </a>
              </li>
              <li class="mt-0.5 w-full">
                <a class="py-2.7 text-base ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors {{ request()->routeIs('productos.deleted') ? 'rounded-lg bg-blue-500/13 font-semibold text-slate-700' : 'text-slate-600 hover:text-slate-700' }}" 
                   href="{{ route('productos.deleted') }}">
                  <div class="shadow-soft-2xl mr-2 flex h-6 w-6 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center">
                    <i class="fas fa-box text-xs text-slate-600"></i>
                  </div>
                  <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Papelera Productos</span>
                </a>
              </li>
            </ul>
          </li>

          <li class="w-full mt-4">
            <h6 class="pl-6 ml-2 text-xs font-bold leading-tight uppercase opacity-60">Cuenta</h6>
          </li>

          <li class="mt-0.5 w-full">
            <a class="py-2.7 text-lg ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors {{ request()->routeIs('profile.*') ? 'rounded-lg bg-blue-500/13 font-semibold text-slate-700' : 'text-slate-700' }}" href="{{ route('profile.edit') }}">
              <div class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                <i class="fas fa-user text-slate-700"></i>
              </div>
              <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Perfil</span>
            </a>
          </li>
        </ul>
      </div>
    </aside>
    
    <!-- 📱 MAIN CONTENT -->
    <main class="ease-soft-in-out xl:ml-68.5 relative h-screen overflow-y-auto transition-all duration-200 text-lg">
      <!-- 🔝 NAVBAR -->
      <nav class="relative flex flex-wrap items-center justify-between px-0 py-2 mx-6 transition-all shadow-none duration-250 ease-soft-in rounded-2xl lg:flex-nowrap lg:justify-start" navbar-main navbar-scroll="true">
        <div class="flex items-center justify-between w-full px-4 py-1 mx-auto flex-wrap-inherit">
          <nav>
            <!-- breadcrumb -->
            <ol class="flex flex-wrap pt-1 mr-12 bg-transparent rounded-lg sm:mr-16">
              <li class="leading-normal text-lg">
                <a class="opacity-50 text-slate-700" href="javascript:;">Páginas</a>
              </li>
              <li class="text-lg pl-2 capitalize leading-normal text-slate-700 before:float-left before:pr-2 before:text-gray-600 before:content-['/']" aria-current="page">{{ ucfirst(request()->segment(1)) ?? 'Dashboard' }}</li>
            </ol>
            <h6 class="mb-0 font-bold capitalize text-xl">{{ ucfirst(request()->segment(1)) ?? 'Dashboard' }}</h6>
          </nav>

          <div class="flex items-center mt-2 grow sm:mt-0 sm:mr-6 md:mr-0 lg:flex lg:basis-auto">
            <div class="flex items-center md:ml-auto md:pr-4">
              
            </div>
            <ul class="flex flex-row justify-end pl-0 mb-0 list-none md-max:w-full">
              <!-- Usuario dropdown -->
              <li class="flex items-center">
                <div class="relative" dropdown-trigger>
                  <a href="#" class="ease-nav-brand p-0 transition-all text-lg" aria-expanded="false">
                    <i class="cursor-pointer fas fa-user sm:mr-1"></i>
                    <span class="hidden sm:inline">{{ Auth::user()->name }}</span>
                  </a>
                  <ul dropdown-menu class="text-lg duration-250 min-w-44 pointer-events-none absolute right-0 top-0 z-50 origin-top list-none rounded-lg border-0 border-solid border-transparent bg-white bg-clip-padding px-2 py-4 text-left text-slate-500 opacity-0 transition-all lg:absolute lg:right-0 lg:left-auto lg:mt-2 lg:block lg:cursor-pointer lg:shadow-soft-3xl">
                    <li class="relative mb-2">
                      <a class="ease-soft py-1.2 lg:ease-soft clear-both block w-full whitespace-nowrap rounded-lg bg-transparent px-4 duration-300 hover:bg-gray-200 hover:text-slate-700 lg:transition-colors" href="{{ route('profile.edit') }}">
                        <div class="flex py-1">
                          <div class="my-auto">
                            <img src="{{ asset('assets/img/team-2.jpg')}}" class="inline-flex items-center justify-center mr-4 text-white text-sm h-9 w-9 max-w-none rounded-xl" />
                          </div>
                          <div class="flex flex-col justify-center">
                            <h6 class="mb-1 leading-normal text-lg">
                              <span class="font-semibold">{{ Auth::user()->name }}</span>
                            </h6>
                            <p class="mb-0 leading-tight text-base text-slate-400">
                              {{ Auth::user()->email }}
                            </p>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li class="relative">
                      <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a class="ease-soft py-1.2 lg:ease-soft clear-both block w-full whitespace-nowrap rounded-lg px-4 transition-colors duration-300 hover:bg-gray-200 hover:text-slate-700" 
                           href="{{ route('logout') }}"
                           onclick="event.preventDefault(); this.closest('form').submit();">
                          <div class="flex py-1">
                            <div class="inline-flex items-center justify-center my-auto mr-4 text-white text-sm bg-gradient-to-tl from-slate-600 to-slate-300 h-9 w-9 max-w-none rounded-xl">
                              <i class="fas fa-sign-out-alt text-sm"></i>
                            </div>
                            <div class="flex flex-col justify-center">
                              <h6 class="mb-1 leading-normal text-lg font-semibold">
                                Cerrar Sesión
                              </h6>
                              <p class="mb-0 leading-tight text-base text-slate-400">
                                Salir de la aplicación
                              </p>
                            </div>
                          </div>
                        </a>
                      </form>
                    </li>
                  </ul>
                </div>  
              </li>
              <li class="flex items-center px-4 xl:hidden">
                <a href="javascript:;" class="ease-nav-brand p-0 transition-colors text-sm" sidenav-trigger>
                  <div class="w-4.5 overflow-hidden">
                    <i class="ease-soft mb-0.75 relative block h-0.5 rounded-sm bg-slate-500 transition-all"></i>
                    <i class="ease-soft mb-0.75 relative block h-0.5 rounded-sm bg-slate-500 transition-all"></i>
                    <i class="ease-soft relative block h-0.5 rounded-sm bg-slate-500 transition-all"></i>
                  </div>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </nav>

      <!-- 📄 PAGE CONTENT -->
      <div class="p-6">
       <!-- Mostrar mensajes de éxito -->
            @if(session('success'))
                <div class="w-full max-w-full px-3 mb-4">
                    <div class="relative w-full p-4 text-white bg-green-500 rounded-lg shadow-soft-xl">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-3"></i>
                            <div>
                                <strong>Éxito:</strong> {{ session('success') }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Mostrar mensajes de error -->
            @if(session('error'))
                <div class="w-full max-w-full px-3 mb-4">
                    <div class="relative w-full p-4 text-white bg-red-500 rounded-lg shadow-soft-xl">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-3"></i>
                            <div>
                                <strong>Error:</strong> {{ session('error') }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
      @yield('content')
      </div>
    </main>
    
    <script>
        // Función para manejar el dropdown de papelera
        function togglePapeleraDropdown() {
            const submenu = document.getElementById('papelera-submenu');
            const chevron = document.getElementById('papelera-chevron');
            
            if (submenu.style.height === '0px' || submenu.style.height === '') {
                // Expandir
                submenu.style.height = 'auto';
                const height = submenu.scrollHeight + 'px';
                submenu.style.height = '0px';
                
                // Forzar reflow y animar
                submenu.offsetHeight;
                submenu.style.height = height;
                submenu.style.opacity = '1';
                chevron.classList.add('rotate-180');
            } else {
                // Colapsar
                submenu.style.height = '0px';
                submenu.style.opacity = '0';
                chevron.classList.remove('rotate-180');
            }
        }
        
        // Función para manejar el dropdown de productos/inventario
        function toggleProductosDropdown() {
            const submenu = document.getElementById('productos-submenu');
            const chevron = document.getElementById('productos-chevron');
            
            if (submenu.style.height === '0px' || submenu.style.height === '') {
                submenu.style.height = 'auto';
                const height = submenu.scrollHeight + 'px';
                submenu.style.height = '0px';
                
                submenu.offsetHeight;
                submenu.style.height = height;
                submenu.style.opacity = '1';
                chevron.classList.add('rotate-180');
            } else {
                submenu.style.height = '0px';
                submenu.style.opacity = '0';
                chevron.classList.remove('rotate-180');
            }
        }
        
        // Funciones globales para modales
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                // Si es un modal de force-delete, usar su función específica
                if (modalId.includes('force-delete')) {
                    openForceDeleteModal(modalId);
                    return;
                }
                
                modal.classList.remove('hidden');
                modal.classList.add('active');
                
                // Enfocar primer input si existe
                const firstInput = modal.querySelector('input, textarea, select');
                if (firstInput) {
                    setTimeout(() => firstInput.focus(), 100);
                }
            }
        }
        
        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                // Si es un modal de force-delete, usar su función específica
                if (modalId.includes('force-delete')) {
                    closeForceDeleteModal(modalId);
                    return;
                }
                
                modal.classList.add('hidden');
                modal.classList.remove('active');
                
                // Limpiar campos
                const inputs = modal.querySelectorAll('input, textarea, select');
                inputs.forEach(input => {
                    if (input.type !== 'hidden' && input.name !== '_token') {
                        input.value = '';
                    }
                });
            }
        }
        
        // Auto-expandir si estamos en una ruta de papelera o productos/categorías
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            if (currentPath.includes('deleted') || currentPath.includes('papelera')) {
                setTimeout(() => {
                    togglePapeleraDropdown();
                }, 100);
            }
            if (currentPath.includes('productos') || currentPath.includes('categorias') || currentPath.includes('inventario')) {
                setTimeout(() => {
                    toggleProductosDropdown();
                }, 100);
            }
        });
        
        // Cerrar modal con tecla Escape (para modales generales)
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const modals = document.querySelectorAll('.modal-backdrop.active');
                modals.forEach(modal => {
                    if (!modal.id.includes('force-delete')) { // Los force-delete tienen su propio handler
                        closeModal(modal.id);
                    }
                });
            }
        });
        
        // Cerrar modal al hacer clic fuera (para modales generales)
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal-backdrop') && !event.target.id.includes('force-delete')) {
                closeModal(event.target.id);
            }
        });
    </script>
  </body>
</html>

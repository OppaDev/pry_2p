<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}" />
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}" />
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- Nucleo Icons -->
    <link href="{{ asset('assets/css/nucleo-icons.css')}}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css')}}" rel="stylesheet" />
    
    <!-- Popper -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    
    <!-- 🎨 ORDEN CORRECTO DE CARGA DE ESTILOS -->
    <!-- 1. Soft UI Dashboard CSS (Base) -->
    <link href="{{ asset('assets/css/soft-ui-dashboard-tailwind.css?v=1.0.5')}}" rel="stylesheet" /> 
    
    <!-- 2. Laravel Vite CSS (Tailwind + Personalizaciones) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- 3. Estilos adicionales para compatibilidad -->
    <style>
        /* 🔧 CORRECCIONES ESPECÍFICAS PARA COMPATIBILIDAD */
        
        /* Asegurar que las clases con punto funcionen correctamente */
        .h-5\.75 { height: 1.4375rem !important; }
        .w-5\.75 { width: 1.4375rem !important; }
        .mr-1\.25 { margin-right: 0.3125rem !important; }
        .before\:text-5\.5::before { font-size: 1.375rem !important; }
        .before\:right-7\.5::before { right: 1.875rem !important; }
        
        /* Correcciones para el dropdown del usuario */
        [dropdown-menu] {
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
            transform-origin: top !important;
        }
        
        [dropdown-menu].opacity-0 {
            opacity: 0 !important;
            pointer-events: none !important;
            transform: perspective(999px) rotateX(-10deg) translateZ(0) translate3d(0,37px,0) !important;
        }
        
        [dropdown-menu]:not(.opacity-0) {
            opacity: 1 !important;
            pointer-events: auto !important;
            transform: perspective(999px) rotateX(0deg) translateZ(0) translate3d(0,37px,5px) !important;
        }
        
        /* Asegurar que el sidenav funcione correctamente */
        aside {
            transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
        
        @media (max-width: 1279px) {
            aside.-translate-x-full {
                transform: translateX(-100%) !important;
            }
            
            aside.translate-x-0 {
                transform: translateX(0) !important;
            }
        }
        
        @media (min-width: 1280px) {
            aside {
                transform: translateX(0) !important;
            }
        }
        
        /* Correcciones para botones y elementos interactivos */
        .hover\:scale-102:hover {
            transform: scale(1.02) !important;
        }
        
        .active\:opacity-85:active {
            opacity: 0.85 !important;
        }
        
        /* Asegurar que los gradientes funcionen */
        .bg-gradient-to-tl {
            background-image: linear-gradient(310deg, var(--tw-gradient-from), var(--tw-gradient-to)) !important;
        }
        
        /* Corrección para el configurador */
        [fixed-plugin-card] {
            transition: right 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
        
        [fixed-plugin-card].-right-90 {
            right: -22.5rem !important;
        }
        
        [fixed-plugin-card].right-0 {
            right: 0 !important;
        }
        
        /* Asegurar visibilidad de elementos */
        .pointer-events-none {
            pointer-events: none !important;
        }
        
        .pointer-events-auto {
            pointer-events: auto !important;
        }
        
        /* Correcciones para responsive */
        .flex-wrap-inherit {
            flex-wrap: inherit !important;
        }
        
        /* Asegurar que los pseudo-elementos funcionen */
        .before\:content-\[\'\\f0d8\'\]::before {
            content: '\f0d8' !important;
            font-family: 'Font Awesome 5 Free' !important;
            font-weight: 900 !important;
        }
    </style>
  </head>

  <body class="m-0 font-sans antialiased font-normal text-base leading-default bg-gray-50 text-slate-500">
    <!-- 🧭 SIDENAV -->
    <aside class="max-w-62.5 ease-nav-brand z-990 absolute inset-y-0 my-4 ml-4 block w-full -translate-x-full flex-wrap items-center justify-between overflow-y-auto rounded-2xl border-0 bg-white p-0 antialiased shadow-none transition-transform duration-200 xl:left-0 xl:translate-x-0 xl:bg-transparent">
      <div class="h-19.5">
        <i class="absolute top-0 right-0 hidden p-4 opacity-50 cursor-pointer fas fa-times text-slate-400 xl:hidden" sidenav-close></i>
        <a class="block px-8 py-6 m-0 text-sm whitespace-nowrap text-slate-700" href="{{url('/')}}">
          <img src="{{ asset('assets/img/logo-ct.png')}}" class="inline h-full max-w-full transition-all duration-200 ease-nav-brand max-h-8" alt="main_logo" />
          <span class="ml-1 font-semibold transition-all duration-200 ease-nav-brand">{{ config('app.name', 'Laravel') }}</span>
        </a>
      </div>

      <hr class="h-px mt-0 bg-gradient-to-r from-transparent via-black/40 to-transparent" />

      <div class="items-center block w-auto max-h-screen overflow-auto h-sidenav grow basis-full">
        <ul class="flex flex-col pl-0 mb-0">
          <li class="mt-0.5 w-full">
            <a class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors {{ request()->routeIs('dashboard') ? 'rounded-lg bg-blue-500/13 font-semibold text-slate-700' : 'text-slate-700' }}" href="{{ route('dashboard') }}">
              <div class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                <i class="fas fa-tv text-slate-700"></i>
              </div>
              <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Dashboard</span>
            </a>
          </li>

          <li class="mt-0.5 w-full">
            <a class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors {{ request()->routeIs('productos.*') ? 'rounded-lg bg-blue-500/13 font-semibold text-slate-700' : 'text-slate-700' }}" href="{{ route('productos.index') }}">
              <div class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                <i class="fas fa-box text-slate-700"></i>
              </div>
              <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Productos</span>
            </a>
          </li>

          <li class="mt-0.5 w-full">
            <a class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors {{ request()->routeIs('users.*') ? 'rounded-lg bg-blue-500/13 font-semibold text-slate-700' : 'text-slate-700' }}" href="{{ route('users.index') }}">
              <div class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                <i class="fas fa-users text-slate-700"></i>
              </div>
              <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Usuarios</span>
            </a>
          </li>

          <li class="w-full mt-4">
            <h6 class="pl-6 ml-2 text-xs font-bold leading-tight uppercase opacity-60">Cuenta</h6>
          </li>

          <li class="mt-0.5 w-full">
            <a class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors {{ request()->routeIs('profile.*') ? 'rounded-lg bg-blue-500/13 font-semibold text-slate-700' : 'text-slate-700' }}" href="{{ route('profile.edit') }}">
              <div class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                <i class="fas fa-user text-slate-700"></i>
              </div>
              <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Perfil</span>
            </a>
          </li>
        </ul>
      </div>

      <div class="mx-4">
        <!-- Información del usuario -->
        <div class="p-4 rounded-lg bg-gradient-to-tl from-blue-600 to-cyan-400">
          <div class="text-white">
            <div class="text-sm font-semibold">{{ Auth::user()->name }}</div>
            <div class="text-xs opacity-70">{{ Auth::user()->email }}</div>
          </div>
        </div>
      </div>
    </aside>

    <!-- 📱 MAIN CONTENT -->
    <main class="ease-soft-in-out xl:ml-68.5 relative h-full max-h-screen rounded-xl transition-all duration-200">
      <!-- 🔝 NAVBAR -->
      <nav class="relative flex flex-wrap items-center justify-between px-0 py-2 mx-6 transition-all shadow-none duration-250 ease-soft-in rounded-2xl lg:flex-nowrap lg:justify-start" navbar-main navbar-scroll="true">
        <div class="flex items-center justify-between w-full px-4 py-1 mx-auto flex-wrap-inherit">
          <nav>
            <!-- breadcrumb -->
            <ol class="flex flex-wrap pt-1 mr-12 bg-transparent rounded-lg sm:mr-16">
              <li class="leading-normal text-sm">
                <a class="opacity-50 text-slate-700" href="javascript:;">Páginas</a>
              </li>
              <li class="text-sm pl-2 capitalize leading-normal text-slate-700 before:float-left before:pr-2 before:text-gray-600 before:content-['/']" aria-current="page">{{ ucfirst(request()->segment(1)) ?? 'Dashboard' }}</li>
            </ol>
            <h6 class="mb-0 font-bold capitalize">{{ ucfirst(request()->segment(1)) ?? 'Dashboard' }}</h6>
          </nav>

          <div class="flex items-center mt-2 grow sm:mt-0 sm:mr-6 md:mr-0 lg:flex lg:basis-auto">
            <div class="flex items-center md:ml-auto md:pr-4">
              <div class="relative flex flex-wrap items-stretch w-full transition-all rounded-lg ease-soft">
                <span class="text-sm leading-5.6 absolute z-50 -ml-px flex h-full items-center whitespace-nowrap rounded-lg rounded-tr-none rounded-br-none border border-r-0 border-transparent bg-transparent py-2 px-2.5 text-center font-normal text-slate-500 transition-all">
                  <i class="fas fa-search"></i>
                </span>
                <input type="text" class="pl-8.75 text-sm focus:shadow-soft-primary-outline ease-soft w-1/100 leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" placeholder="Buscar aquí..." />
              </div>
            </div>
            <ul class="flex flex-row justify-end pl-0 mb-0 list-none md-max:w-full">
              <!-- Usuario dropdown -->
              <li class="flex items-center">
                <div class="relative" dropdown-trigger>
                  <a href="#" class="ease-nav-brand p-0 transition-all text-sm" aria-expanded="false">
                    <i class="cursor-pointer fas fa-user sm:mr-1"></i>
                    <span class="hidden sm:inline">{{ Auth::user()->name }}</span>
                  </a>
                  <ul dropdown-menu class="text-sm duration-250 min-w-44 pointer-events-none absolute right-0 top-0 z-50 origin-top list-none rounded-lg border-0 border-solid border-transparent bg-white bg-clip-padding px-2 py-4 text-left text-slate-500 opacity-0 transition-all lg:absolute lg:right-0 lg:left-auto lg:mt-2 lg:block lg:cursor-pointer lg:shadow-soft-3xl">
                    <li class="relative mb-2">
                      <a class="ease-soft py-1.2 lg:ease-soft clear-both block w-full whitespace-nowrap rounded-lg bg-transparent px-4 duration-300 hover:bg-gray-200 hover:text-slate-700 lg:transition-colors" href="{{ route('profile.edit') }}">
                        <div class="flex py-1">
                          <div class="my-auto">
                            <img src="{{ asset('assets/img/team-2.jpg')}}" class="inline-flex items-center justify-center mr-4 text-white text-sm h-9 w-9 max-w-none rounded-xl" />
                          </div>
                          <div class="flex flex-col justify-center">
                            <h6 class="mb-1 leading-normal text-sm">
                              <span class="font-semibold">{{ Auth::user()->name }}</span>
                            </h6>
                            <p class="mb-0 leading-tight text-xs text-slate-400">
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
                              <h6 class="mb-1 leading-normal text-sm font-semibold">
                                Cerrar Sesión
                              </h6>
                              <p class="mb-0 leading-tight text-xs text-slate-400">
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
              <li class="flex items-center px-4">
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
      @yield('content')
    </main>
  </body>

  <!-- 🔧 SCRIPTS -->
  <!-- Plugin for charts -->
  <script src="{{ asset('assets/js/plugins/chartjs.min.js') }}" async></script>
  <!-- Plugin for scrollbar -->
  <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js')}}" async></script>
  <!-- GitHub button -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Main script file -->
  <script src="{{asset('assets/js/soft-ui-dashboard-tailwind.js?v=1.0.5')}}" async></script>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Desarrollo de Software Seguro</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                /* Base styles para modo claro y oscuro */
                :root {
                    --bg-primary: #f0fdf4;
                    --bg-secondary: #dcfce7;
                    --text-primary: #14532d;
                    --text-secondary: #166534;
                    --border-color: #bbf7d0;
                    --card-bg: #ffffff;
                }
                
                .dark {
                    --bg-primary: #14532d;
                    --bg-secondary: #166534;
                    --text-primary: #dcfce7;
                    --text-secondary: #bbf7d0;
                    --border-color: #166534;
                    --card-bg: #052e16;
                }
                
                body {
                    background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
                    color: var(--text-primary);
                    transition: all 0.3s ease;
                }
                
                .main-card {
                    background-color: var(--card-bg);
                    color: var(--text-primary);
                    transition: all 0.3s ease;
                }
                
                .text-primary-custom {
                    color: var(--text-primary);
                }
                
                .text-secondary-custom {
                    color: var(--text-secondary);
                }
                
                .border-custom {
                    border-color: var(--border-color);
                }
                
                /* Estilos personalizados para la paleta verde */
                .gradient-green {
                    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
                }
                .dark .gradient-green {
                    background: linear-gradient(135deg, #14532d 0%, #166534 100%);
                }
                .security-card {
                    backdrop-filter: blur(10px);
                    background: rgba(255, 255, 255, 0.9);
                }
                .dark .security-card {
                    background: rgba(20, 83, 45, 0.9);
                }
                .floating-animation {
                    animation: float 3s ease-in-out infinite;
                }
                @keyframes float {
                    0%, 100% { transform: translateY(0px); }
                    50% { transform: translateY(-10px); }
                }
                .pulse-green {
                    animation: pulse-green 2s infinite;
                }
                @keyframes pulse-green {
                    0%, 100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
                    70% { box-shadow: 0 0 0 10px rgba(34, 197, 94, 0); }
                }
                /* Toggle dark mode styles */
                .dark-toggle {
                    transition: all 0.3s ease;
                    background-color: var(--card-bg);
                    color: var(--text-primary);
                    border: 1px solid var(--border-color);
                    padding: 8px;
                    border-radius: 8px;
                }
                .dark-toggle:hover {
                    transform: scale(1.1);
                    border-color: var(--text-secondary);
                }
                
                .dark-toggle svg {
                    width: 20px;
                    height: 20px;
                }
                
                .dark .dark-toggle .sun-icon {
                    display: none;
                }
                
                .dark .dark-toggle .moon-icon {
                    display: block;
                }
                
                .dark-toggle .sun-icon {
                    display: block;
                }
                
                .dark-toggle .moon-icon {
                    display: none;
                }
            </style>
        @endif
        
        <script>
            // Dark mode toggle functionality with debug
            function toggleDarkMode() {
                const html = document.documentElement;
                const isDark = html.classList.contains('dark');
                
                console.log('Current mode:', isDark ? 'dark' : 'light');
                
                if (isDark) {
                    html.classList.remove('dark');
                    localStorage.setItem('darkMode', 'false');
                    console.log('Switched to light mode');
                } else {
                    html.classList.add('dark');
                    localStorage.setItem('darkMode', 'true');
                    console.log('Switched to dark mode');
                }
                
                // Force a style recalculation
                html.style.display = 'none';
                html.offsetHeight; // Trigger reflow
                html.style.display = '';
            }
            
            // Initialize dark mode based on localStorage or system preference
            function initDarkMode() {
                const savedMode = localStorage.getItem('darkMode');
                const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                
                console.log('Saved mode:', savedMode);
                console.log('System prefers dark:', systemPrefersDark);
                
                if (savedMode === 'true' || (savedMode === null && systemPrefersDark)) {
                    document.documentElement.classList.add('dark');
                    console.log('Dark mode initialized');
                } else {
                    document.documentElement.classList.remove('dark');
                    console.log('Light mode initialized');
                }
            }
            
            // Initialize on page load
            document.addEventListener('DOMContentLoaded', initDarkMode);
            
            // Also initialize immediately
            initDarkMode();
        </script>
        </script>
    </head>
    <body class="bg-gradient-to-br from-green-50 to-emerald-100 dark:from-green-900 dark:to-emerald-950 text-green-900 dark:text-green-100 flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
            @if (Route::has('login'))
                <nav class="flex items-center justify-between gap-4">
                    <!-- Dark Mode Toggle -->
                    <button 
                        onclick="toggleDarkMode()" 
                        class="dark-toggle"
                        title="Alternar modo oscuro"
                    >
                        <!-- Sol (modo claro) -->
                        <svg class="sun-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <!-- Luna (modo oscuro) -->
                        <svg class="moon-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                    </button>
                    
                    <div class="flex items-center gap-4">
                        @auth
                            <a
                                href="{{ url('/dashboard') }}"
                                class="inline-block px-5 py-1.5 text-green-800 dark:text-green-100 border border-green-300 dark:border-green-700 hover:border-green-500 dark:hover:border-green-500 rounded-lg text-sm leading-normal transition-colors"
                            >
                                Panel de Control
                            </a>
                        @else
                            <a
                                href="{{ route('login') }}"
                                class="inline-block px-5 py-1.5 text-green-800 dark:text-green-100 border border-transparent hover:border-green-300 dark:hover:border-green-700 rounded-lg text-sm leading-normal transition-colors"
                            >
                                Iniciar Sesión
                            </a>

                            @if (Route::has('register'))
                                <a
                                    href="{{ route('register') }}"
                                    class="inline-block px-5 py-1.5 text-green-800 dark:text-green-100 border border-green-300 dark:border-green-700 hover:border-green-500 dark:hover:border-green-500 rounded-lg text-sm leading-normal transition-colors">
                                    Registrarse
                                </a>
                            @endif
                        @endauth
                    </div>
                </nav>
            @endif
        </header>
        <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
            <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row">
                <div class="text-[13px] leading-[20px] flex-1 p-6 pb-12 lg:p-20 main-card shadow-[inset_0px_0px_0px_1px_rgba(34,197,94,0.16)] dark:shadow-[inset_0px_0px_0px_1px_rgba(34,197,94,0.3)] rounded-bl-lg rounded-br-lg lg:rounded-tl-lg lg:rounded-br-none">
                    <div class="mb-6">
                        <h1 class="text-2xl lg:text-3xl font-bold text-green-800 dark:text-green-100 mb-3">
                            ¡Bienvenidos al Desarrollo de Software Seguro!
                        </h1>
                        <p class="text-green-700 dark:text-green-200 text-base leading-relaxed">
                            Construye aplicaciones robustas y protegidas con las mejores prácticas de seguridad. 
                            Aprende, implementa y protege tu código desde el primer día.
                        </p>
                    </div>
                    
                    <div class="space-y-6">
                        <div>
                            <h2 class="text-lg font-semibold text-green-800 dark:text-green-100 mb-3">🛡️ Fundamentos de Seguridad</h2>
                            <ul class="space-y-2 text-green-700 dark:text-green-200">
                                <li class="flex items-start gap-2">
                                    <span class="text-green-500 mt-1">•</span>
                                    <span>Principios de seguridad por diseño</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-green-500 mt-1">•</span>
                                    <span>Autenticación y autorización robusta</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-green-500 mt-1">•</span>
                                    <span>Validación y sanitización de datos</span>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h2 class="text-lg font-semibold text-green-800 dark:text-green-100 mb-3">🔒 Protección Avanzada</h2>
                            <ul class="space-y-2 text-green-700 dark:text-green-200">
                                <li class="flex items-start gap-2">
                                    <span class="text-green-500 mt-1">•</span>
                                    <span>Cifrado de datos y comunicaciones</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-green-500 mt-1">•</span>
                                    <span>Prevención de vulnerabilidades OWASP</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-green-500 mt-1">•</span>
                                    <span>Auditorías y monitoreo de seguridad</span>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h2 class="text-lg font-semibold text-green-800 dark:text-green-100 mb-3">🚀 Implementación Práctica</h2>
                            <ul class="space-y-2 text-green-700 dark:text-green-200">
                                <li class="flex items-start gap-2">
                                    <span class="text-green-500 mt-1">•</span>
                                    <span>Configuración segura de Laravel</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-green-500 mt-1">•</span>
                                    <span>Middleware de seguridad personalizado</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-green-500 mt-1">•</span>
                                    <span>Testing de seguridad automatizado</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="mt-8 pt-6 border-t border-green-200 dark:border-green-700">
                        <div class="flex flex-wrap gap-3">
                            <a href="#" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C20.832 18.477 19.246 18 17.5 18c-1.746 0-3.332.477-4.5 1.253z"></path>
                                </svg>
                                Documentación
                            </a>
                            <a href="#" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1a3 3 0 000-6h-1m1 6V4a6 6 0 106 6v3"></path>
                                </svg>
                                Guías de Seguridad
                            </a>
                            <a href="#" class="inline-flex items-center px-4 py-2 border border-green-600 text-green-600 dark:text-green-400 dark:border-green-400 hover:bg-green-50 dark:hover:bg-green-900 rounded-lg text-sm font-medium transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Mejores Prácticas
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-green-100 to-emerald-50 dark:from-green-800 dark:to-emerald-900 relative lg:-ml-px -mb-px lg:mb-0 rounded-t-lg lg:rounded-t-none lg:rounded-r-lg aspect-[335/376] lg:aspect-auto w-full lg:w-[438px] shrink-0 overflow-hidden">
                    <!-- Security Shield Icon with Animation -->
                    <div class="absolute inset-0 flex items-center justify-center p-8">
                        <div class="relative">
                            <!-- Main Shield -->
                            <div class="relative transform transition-all duration-300 hover:scale-105">
                                <svg class="w-48 h-48 lg:w-64 lg:h-64 text-green-600 dark:text-green-400 drop-shadow-2xl" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12,1L3,5V11C3,16.55 6.84,21.74 12,23C17.16,21.74 21,16.55 21,11V5L12,1M12,7C13.4,7 14.8,8.6 14.8,10V11.5C15.4,11.5 16,12.4 16,13V16C16,17 15.4,17.5 14.8,17.5H9.2C8.6,17.5 8,17 8,16V13C8,12.4 8.6,11.5 9.2,11.5V10C9.2,8.6 10.6,7 12,7M12,8.2C11.2,8.2 10.5,8.7 10.5,10V11.5H13.5V10C13.5,8.7 12.8,8.2 12,8.2Z"/>
                                </svg>
                                
                                <!-- Lock inside shield -->
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <svg class="w-8 h-8 lg:w-12 lg:h-12 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12,17A2,2 0 0,0 14,15C14,13.89 13.1,13 12,13A2,2 0 0,0 10,15A2,2 0 0,0 12,17M18,8A2,2 0 0,1 20,10V20A2,2 0 0,1 18,22H6A2,2 0 0,1 4,20V10C4,8.89 4.9,8 6,8H7V6A5,5 0 0,1 12,1A5,5 0 0,1 17,6V8H18M12,3A3,3 0 0,0 9,6V8H15V6A3,3 0 0,0 12,3Z"/>
                                    </svg>
                                </div>
                            </div>
                            
                            <!-- Floating security elements -->
                            <div class="absolute -top-4 -right-4 animate-bounce">
                                <div class="bg-green-500 text-white p-2 rounded-full shadow-lg">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z"/>
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="absolute -bottom-4 -left-4 animate-pulse">
                                <div class="bg-emerald-500 text-white p-2 rounded-full shadow-lg">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12,8A4,4 0 0,1 16,12A4,4 0 0,1 12,16A4,4 0 0,1 8,12A4,4 0 0,1 12,8M12,10A2,2 0 0,0 10,12A2,2 0 0,0 12,14A2,2 0 0,0 14,12A2,2 0 0,0 12,10M10,22C9.75,22 9.54,21.82 9.5,21.58L9.13,18.93C8.5,18.68 7.96,18.34 7.44,17.94L4.95,18.95C4.73,19.03 4.46,18.95 4.34,18.73L2.34,15.27C2.21,15.05 2.27,14.78 2.46,14.63L4.57,12.97L4.5,12L4.57,11L2.46,9.37C2.27,9.22 2.21,8.95 2.34,8.73L4.34,5.27C4.46,5.05 4.73,4.96 4.95,5.05L7.44,6.05C7.96,5.66 8.5,5.32 9.13,5.07L9.5,2.42C9.54,2.18 9.75,2 10,2H14C14.25,2 14.46,2.18 14.5,2.42L14.87,5.07C15.5,5.32 16.04,5.66 16.56,6.05L19.05,5.05C19.27,4.96 19.54,5.05 19.66,5.27L21.66,8.73C21.79,8.95 21.73,9.22 21.54,9.37L19.43,11L19.5,12L19.43,13L21.54,14.63C21.73,14.78 21.79,15.05 21.66,15.27L19.66,18.73C19.54,18.95 19.27,19.04 19.05,18.95L16.56,17.95C16.04,18.34 15.5,18.68 14.87,18.93L14.5,21.58C14.46,21.82 14.25,22 14,22H10M11.25,4L10.88,6.61C9.68,6.86 8.62,7.5 7.85,8.39L5.44,7.35L4.69,8.65L6.8,10.2C6.4,11.37 6.4,12.64 6.8,13.8L4.68,15.36L5.43,16.66L7.86,15.62C8.63,16.5 9.68,17.14 10.87,17.38L11.24,20H12.76L13.13,17.39C14.32,17.14 15.37,16.5 16.14,15.62L18.57,16.66L19.32,15.36L17.2,13.81C17.6,12.64 17.6,11.37 17.2,10.2L19.31,8.65L18.56,7.35L16.15,8.39C15.38,7.5 14.32,6.86 13.12,6.62L12.75,4H11.25Z"/>
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="absolute top-8 -left-8 animate-ping">
                                <div class="bg-green-400 text-white p-1 rounded-full shadow-lg opacity-75">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M11,15H13V17H11V15M11,7H13V13H11V7M12,2C6.47,2 2,6.5 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,20A8,8 0 0,1 4,12A8,8 0 0,1 12,4A8,8 0 0,1 20,12A8,8 0 0,1 12,20Z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Background Pattern -->
                    <div class="absolute inset-0 opacity-10">
                        <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="security-pattern" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                                    <circle cx="10" cy="10" r="1" fill="currentColor" class="text-green-600"/>
                                    <path d="M5,5 L15,15 M15,5 L5,15" stroke="currentColor" stroke-width="0.5" class="text-green-500"/>
                                </pattern>
                            </defs>
                            <rect width="100" height="100" fill="url(#security-pattern)"/>
                        </svg>
                    </div>
                    
                    <!-- Security Badge -->
                    <div class="absolute bottom-4 right-4">
                        <div class="bg-green-600 text-white px-3 py-2 rounded-full text-xs font-semibold shadow-lg flex items-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23,12L20.56,9.22L20.9,5.54L17.29,4.72L15.4,1.54L12,3L8.6,1.54L6.71,4.72L3.1,5.53L3.44,9.21L1,12L3.44,14.78L3.1,18.47L6.71,19.29L8.6,22.47L12,21L15.4,22.46L17.29,19.28L20.9,18.46L20.56,14.78L23,12M10,17L6,13L7.41,11.59L10,14.17L16.59,7.58L18,9L10,17Z"/>
                            </svg>
                            <span>Seguro</span>
                        </div>
                    </div>
                    
                    <!-- Decorative border -->
                    <div class="absolute inset-0 rounded-t-lg lg:rounded-t-none lg:rounded-r-lg border-2 border-green-200 dark:border-green-700"></div>
                </div>
            </main>
        </div>

        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
    </body>
</html>

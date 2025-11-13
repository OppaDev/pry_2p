<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <!-- Header del formulario -->
    <div class="text-center mb-8">
        <h2 class="text-3xl font-black mb-2" style="background: linear-gradient(135deg, #ffffff 0%, #dc2626 50%, #ffffff 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
            BIENVENIDO DE NUEVO
        </h2>
        <p class="text-gray-400 text-sm">Ingresa tus credenciales para acceder a Inferno Club</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="inferno-label block mb-2">
                <i class="fas fa-envelope mr-2"></i>Correo Electrónico
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-user text-red-600"></i>
                </div>
                <input id="email" 
                       class="inferno-input block w-full pl-12 pr-4 py-3 rounded-lg" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}"
                       required 
                       autofocus 
                       autocomplete="username"
                       placeholder="tu@ejemplo.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="inferno-label block mb-2">
                <i class="fas fa-lock mr-2"></i>Contraseña
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-key text-red-600"></i>
                </div>
                <input id="password" 
                       class="inferno-input block w-full pl-12 pr-12 py-3 rounded-lg"
                       type="password"
                       name="password"
                       required 
                       autocomplete="current-password"
                       placeholder="••••••••" />
                <button type="button" 
                        onclick="togglePassword()" 
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-red-600 transition-colors duration-200">
                    <i id="password-icon" class="fas fa-eye"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" 
                       type="checkbox" 
                       class="rounded border-red-900 shadow-sm focus:ring-red-600 focus:ring-2 transition-all duration-200" 
                       name="remember">
                <span class="ml-3 text-sm text-gray-300 group-hover:text-white transition-colors duration-200">
                    {{ __('Recuérdame') }}
                </span>
            </label>

            @if (Route::has('password.request'))
                <a class="inferno-link text-sm font-medium" 
                   href="{{ route('password.request') }}">
                    {{ __('¿Olvidaste tu contraseña?') }}
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="pt-4">
            <button type="submit" class="inferno-button w-full py-3 rounded-lg flex items-center justify-center gap-2">
                <i class="fas fa-fire"></i>
                {{ __('Iniciar sesión') }}
            </button>
        </div>
        
        <!-- Register Link -->
        @if (Route::has('register'))
        <div class="text-center pt-4 border-t border-red-900/30">
            <p class="text-gray-400 text-sm">
                ¿No tienes cuenta? 
                <a href="{{ route('register') }}" class="inferno-link font-semibold">
                    Regístrate aquí
                </a>
            </p>
        </div>
        @endif
    </form>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const passwordIcon = document.getElementById('password-icon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        }

        // Auto-hide flash messages
        document.addEventListener('DOMContentLoaded', function() {
            const statusElement = document.querySelector('[data-status]');
            if (statusElement) {
                setTimeout(() => {
                    statusElement.style.transition = 'opacity 0.3s ease';
                    statusElement.style.opacity = '0';
                    setTimeout(() => statusElement.remove(), 300);
                }, 5000);
            }
        });
    </script>
</x-guest-layout>


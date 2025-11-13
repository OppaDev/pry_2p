<x-guest-layout>
    <!-- Header del formulario -->
    <div class="text-center mb-8">
        <h2 class="text-3xl font-black mb-2" style="background: linear-gradient(135deg, #ffffff 0%, #dc2626 50%, #ffffff 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
            ÚNETE AL CLUB
        </h2>
        <p class="text-gray-400 text-sm">Crea tu cuenta en Inferno Club</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="inferno-label block mb-2">
                <i class="fas fa-user mr-2"></i>Nombre Completo
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-id-card text-red-600"></i>
                </div>
                <input id="name" 
                       class="inferno-input block w-full pl-12 pr-4 py-3 rounded-lg" 
                       type="text" 
                       name="name" 
                       value="{{ old('name') }}"
                       required 
                       autofocus 
                       autocomplete="name"
                       placeholder="Juan Pérez" />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="inferno-label block mb-2">
                <i class="fas fa-envelope mr-2"></i>Correo Electrónico
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-at text-red-600"></i>
                </div>
                <input id="email" 
                       class="inferno-input block w-full pl-12 pr-4 py-3 rounded-lg" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}"
                       required 
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
                       autocomplete="new-password"
                       placeholder="Mínimo 8 caracteres" />
                <button type="button" 
                        onclick="togglePasswordVisibility('password', 'password-icon')" 
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-red-600 transition-colors duration-200">
                    <i id="password-icon" class="fas fa-eye"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="inferno-label block mb-2">
                <i class="fas fa-shield-alt mr-2"></i>Confirmar Contraseña
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-check-double text-red-600"></i>
                </div>
                <input id="password_confirmation" 
                       class="inferno-input block w-full pl-12 pr-12 py-3 rounded-lg"
                       type="password"
                       name="password_confirmation" 
                       required 
                       autocomplete="new-password"
                       placeholder="Repite tu contraseña" />
                <button type="button" 
                        onclick="togglePasswordVisibility('password_confirmation', 'password-confirm-icon')" 
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-red-600 transition-colors duration-200">
                    <i id="password-confirm-icon" class="fas fa-eye"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <div class="pt-4">
            <button type="submit" class="inferno-button w-full py-3 rounded-lg flex items-center justify-center gap-2">
                <i class="fas fa-user-plus"></i>
                Crear Cuenta
            </button>
        </div>
        
        <!-- Login Link -->
        <div class="text-center pt-4 border-t border-red-900/30">
            <p class="text-gray-400 text-sm">
                ¿Ya tienes cuenta? 
                <a href="{{ route('login') }}" class="inferno-link font-semibold">
                    Inicia sesión aquí
                </a>
            </p>
        </div>
    </form>

    <script>
        function togglePasswordVisibility(fieldId, iconId) {
            const passwordField = document.getElementById(fieldId);
            const passwordIcon = document.getElementById(iconId);
            
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
    </script>
</x-guest-layout>

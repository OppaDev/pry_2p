<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Inferno Club</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,600,700,900" rel="stylesheet" />
        
        <!-- FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            /* Tema Inferno Club - Negro y Rojo */
            body {
                font-family: 'Inter', sans-serif;
                background: linear-gradient(135deg, #000000 0%, #1a0000 50%, #000000 100%);
                position: relative;
                overflow-x: hidden;
            }
            
            /* Efecto de partículas de fondo */
            body::before {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-image: 
                    radial-gradient(circle at 20% 50%, rgba(220, 38, 38, 0.15) 0%, transparent 50%),
                    radial-gradient(circle at 80% 80%, rgba(239, 68, 68, 0.15) 0%, transparent 50%);
                pointer-events: none;
                z-index: 0;
            }
            
            /* Llamas decorativas */
            .flame {
                position: fixed;
                bottom: -50px;
                width: 30px;
                height: 30px;
                background: radial-gradient(circle, #dc2626 0%, transparent 70%);
                border-radius: 50%;
                opacity: 0.2;
                animation: rise 12s infinite ease-in;
                z-index: 0;
            }
            
            @keyframes rise {
                0% {
                    transform: translateY(0) scale(1);
                    opacity: 0;
                }
                50% {
                    opacity: 0.2;
                }
                100% {
                    transform: translateY(-100vh) scale(0.5);
                    opacity: 0;
                }
            }
            
            .flame:nth-child(1) { left: 5%; animation-delay: 0s; }
            .flame:nth-child(2) { left: 25%; animation-delay: 3s; }
            .flame:nth-child(3) { left: 45%; animation-delay: 6s; }
            .flame:nth-child(4) { left: 65%; animation-delay: 9s; }
            .flame:nth-child(5) { left: 85%; animation-delay: 2s; }
            
            /* Card del formulario */
            .auth-card {
                background: linear-gradient(135deg, #1a0000 0%, #0a0000 100%);
                border: 2px solid #dc2626;
                box-shadow: 
                    0 0 60px rgba(220, 38, 38, 0.3),
                    0 25px 50px -12px rgba(0, 0, 0, 0.8);
                position: relative;
                overflow: hidden;
            }
            
            .auth-card::before {
                content: '';
                position: absolute;
                top: -2px;
                left: -2px;
                right: -2px;
                bottom: -2px;
                background: linear-gradient(45deg, #dc2626, #ef4444, #dc2626);
                border-radius: inherit;
                opacity: 0;
                transition: opacity 0.3s ease;
                z-index: -1;
            }
            
            .auth-card:hover::before {
                opacity: 0.1;
            }
            
            /* Logo container */
            .logo-container {
                position: relative;
                animation: float 3s ease-in-out infinite;
            }
            
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-10px); }
            }
            
            .logo-glow {
                position: absolute;
                inset: -20px;
                background: radial-gradient(circle, rgba(220, 38, 38, 0.3) 0%, transparent 70%);
                border-radius: 50%;
                animation: pulse 2s ease-in-out infinite;
            }
            
            @keyframes pulse {
                0%, 100% { opacity: 0.5; transform: scale(1); }
                50% { opacity: 1; transform: scale(1.1); }
            }
            
            /* Inputs personalizados */
            .inferno-input {
                background: rgba(0, 0, 0, 0.5) !important;
                border: 2px solid #4a0000 !important;
                color: #ffffff !important;
                transition: all 0.3s ease !important;
            }
            
            .inferno-input:focus {
                border-color: #dc2626 !important;
                box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.2) !important;
                background: rgba(0, 0, 0, 0.7) !important;
            }
            
            .inferno-input::placeholder {
                color: #6b7280 !important;
            }
            
            /* Labels */
            .inferno-label {
                color: #ef4444 !important;
                font-weight: 600 !important;
                text-transform: uppercase !important;
                font-size: 0.75rem !important;
                letter-spacing: 0.05em !important;
            }
            
            /* Button */
            .inferno-button {
                background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
                border: 2px solid #dc2626 !important;
                color: white !important;
                font-weight: 700 !important;
                text-transform: uppercase !important;
                letter-spacing: 0.05em !important;
                transition: all 0.3s ease !important;
                position: relative !important;
                overflow: hidden !important;
            }
            
            .inferno-button::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
                transition: left 0.5s ease;
            }
            
            .inferno-button:hover::before {
                left: 100%;
            }
            
            .inferno-button:hover {
                background: linear-gradient(135deg, #b91c1c 0%, #991b1b 100%) !important;
                box-shadow: 0 0 30px rgba(220, 38, 38, 0.5) !important;
                transform: translateY(-2px) !important;
            }
            
            /* Links */
            .inferno-link {
                color: #ef4444 !important;
                transition: all 0.3s ease !important;
            }
            
            .inferno-link:hover {
                color: #dc2626 !important;
                text-shadow: 0 0 10px rgba(220, 38, 38, 0.5);
            }
            
            /* Checkbox */
            input[type="checkbox"] {
                accent-color: #dc2626 !important;
            }
            
            /* Error messages */
            .text-red-600 {
                color: #fca5a5 !important;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <!-- Llamas decorativas -->
        <div class="flame"></div>
        <div class="flame"></div>
        <div class="flame"></div>
        <div class="flame"></div>
        <div class="flame"></div>
        
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative z-10 px-4">
            {{-- <div class="mb-8 logo-container">
                <div class="logo-glow"></div>
                <a href="/" class="block transition-transform duration-200 hover:scale-105 relative z-10">
                    <img src="{{ asset('assets/img/logo_inferno.jpg') }}" 
                         alt="Inferno Club" 
                         class="w-32 h-32 rounded-2xl border-4 border-red-600 shadow-2xl"
                         style="box-shadow: 0 0 40px rgba(220, 38, 38, 0.5);">
                </a>
            </div> --}}

            <div class="w-full sm:max-w-md auth-card overflow-hidden rounded-2xl">
                <div class="px-8 py-10">
                    {{ $slot }}
                </div>
                
                <!-- Link al inicio -->
                <div class="px-8 pb-8 text-center">
                    <a href="/" class="inferno-link text-sm font-medium inline-flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        Volver al inicio
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>

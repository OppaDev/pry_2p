<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Inferno Club - Licorería Premium</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,600,700,900" rel="stylesheet" />

        <!-- Styles -->
        <style>
            /* Estilos personalizados para Inferno Club - Tema Negro y Rojo */
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                
                body {
                    font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    background: linear-gradient(135deg, #000000 0%, #1a0000 50%, #000000 100%);
                    color: #ffffff;
                    min-height: 100vh;
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
                        radial-gradient(circle at 20% 50%, rgba(220, 38, 38, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 80% 80%, rgba(239, 68, 68, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 40% 20%, rgba(220, 38, 38, 0.05) 0%, transparent 40%);
                    pointer-events: none;
                    z-index: 0;
                }
                
                .container {
                    position: relative;
                    z-index: 1;
                    max-width: 1400px;
                    margin: 0 auto;
                    padding: 2rem;
                }
                
                /* Header Navigation */
                header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 1.5rem 0;
                    margin-bottom: 2rem;
                }
                
                .nav-links {
                    display: flex;
                    gap: 1rem;
                    align-items: center;
                }
                
                .nav-links a {
                    padding: 0.75rem 1.5rem;
                    border: 2px solid #dc2626;
                    background: transparent;
                    color: #ffffff;
                    text-decoration: none;
                    border-radius: 8px;
                    font-weight: 600;
                    transition: all 0.3s ease;
                    font-size: 0.9rem;
                }
                
                .nav-links a:hover {
                    background: #dc2626;
                    box-shadow: 0 0 20px rgba(220, 38, 38, 0.5);
                    transform: translateY(-2px);
                }
                
                .nav-links a.primary {
                    background: #dc2626;
                    border-color: #dc2626;
                }
                
                .nav-links a.primary:hover {
                    background: #b91c1c;
                    border-color: #b91c1c;
                }
                
                /* Main Content */
                .main-content {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 4rem;
                    align-items: center;
                    margin-top: 3rem;
                }
                
                .text-content {
                    padding: 2rem;
                }
                
                .logo-section {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    position: relative;
                }
                
                .logo-container {
                    position: relative;
                    width: 100%;
                    max-width: 500px;
                    animation: float 6s ease-in-out infinite;
                }
                
                .logo-container img {
                    width: 100%;
                    height: auto;
                    border-radius: 20px;
                    box-shadow: 
                        0 0 60px rgba(220, 38, 38, 0.4),
                        0 0 100px rgba(220, 38, 38, 0.2);
                    border: 3px solid #dc2626;
                }
                
                @keyframes float {
                    0%, 100% { transform: translateY(0px); }
                    50% { transform: translateY(-20px); }
                }
                
                /* Glow effect around logo */
                .logo-container::before {
                    content: '';
                    position: absolute;
                    top: -20px;
                    left: -20px;
                    right: -20px;
                    bottom: -20px;
                    background: radial-gradient(circle, rgba(220, 38, 38, 0.3) 0%, transparent 70%);
                    border-radius: 30px;
                    z-index: -1;
                    animation: pulse 3s ease-in-out infinite;
                }
                
                @keyframes pulse {
                    0%, 100% { opacity: 0.5; transform: scale(1); }
                    50% { opacity: 1; transform: scale(1.05); }
                }
                
                h1 {
                    font-size: 3.5rem;
                    font-weight: 900;
                    margin-bottom: 1rem;
                    background: linear-gradient(135deg, #ffffff 0%, #dc2626 50%, #ffffff 100%);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    background-clip: text;
                    line-height: 1.2;
                    text-transform: uppercase;
                    letter-spacing: 2px;
                }
                
                .tagline {
                    font-size: 1.5rem;
                    color: #dc2626;
                    font-weight: 700;
                    margin-bottom: 1.5rem;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                }
                
                .description {
                    font-size: 1.1rem;
                    line-height: 1.8;
                    color: #d1d5db;
                    margin-bottom: 2rem;
                }
                
                /* Features Grid */
                .features {
                    display: grid;
                    grid-template-columns: repeat(2, 1fr);
                    gap: 1.5rem;
                    margin-top: 2rem;
                }
                
                .feature-card {
                    background: linear-gradient(135deg, #1a0000 0%, #000000 100%);
                    border: 2px solid #dc2626;
                    border-radius: 12px;
                    padding: 1.5rem;
                    transition: all 0.3s ease;
                    position: relative;
                    overflow: hidden;
                }
                
                .feature-card::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: -100%;
                    width: 100%;
                    height: 100%;
                    background: linear-gradient(90deg, transparent, rgba(220, 38, 38, 0.2), transparent);
                    transition: left 0.5s ease;
                }
                
                .feature-card:hover::before {
                    left: 100%;
                }
                
                .feature-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 10px 30px rgba(220, 38, 38, 0.3);
                    border-color: #ef4444;
                }
                
                .feature-icon {
                    font-size: 2rem;
                    margin-bottom: 0.5rem;
                }
                
                .feature-card h3 {
                    font-size: 1.1rem;
                    margin-bottom: 0.5rem;
                    color: #ffffff;
                    font-weight: 700;
                }
                
                .feature-card p {
                    color: #9ca3af;
                    font-size: 0.9rem;
                    line-height: 1.5;
                }
                
                /* CTA Button */
                .cta-button {
                    display: inline-block;
                    padding: 1rem 3rem;
                    background: #dc2626;
                    color: white;
                    text-decoration: none;
                    border-radius: 50px;
                    font-weight: 700;
                    font-size: 1.1rem;
                    margin-top: 2rem;
                    transition: all 0.3s ease;
                    border: 2px solid #dc2626;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                }
                
                .cta-button:hover {
                    background: #b91c1c;
                    transform: translateY(-3px) scale(1.05);
                    box-shadow: 0 10px 40px rgba(220, 38, 38, 0.5);
                }
                
                /* Responsive */
                @media (max-width: 1024px) {
                    .main-content {
                        grid-template-columns: 1fr;
                        gap: 2rem;
                    }
                    
                    h1 {
                        font-size: 2.5rem;
                    }
                    
                    .tagline {
                        font-size: 1.2rem;
                    }
                    
                    .features {
                        grid-template-columns: 1fr;
                    }
                }
                
                /* Decorative flames */
                .flame {
                    position: fixed;
                    bottom: -50px;
                    width: 40px;
                    height: 40px;
                    background: radial-gradient(circle, #dc2626 0%, transparent 70%);
                    border-radius: 50%;
                    opacity: 0.3;
                    animation: rise 10s infinite ease-in;
                    z-index: 0;
                }
                
                @keyframes rise {
                    0% {
                        transform: translateY(0) scale(1);
                        opacity: 0;
                    }
                    50% {
                        opacity: 0.3;
                    }
                    100% {
                        transform: translateY(-100vh) scale(0.5);
                        opacity: 0;
                    }
                }
                
                .flame:nth-child(1) { left: 10%; animation-delay: 0s; }
                .flame:nth-child(2) { left: 30%; animation-delay: 2s; }
                .flame:nth-child(3) { left: 50%; animation-delay: 4s; }
                .flame:nth-child(4) { left: 70%; animation-delay: 6s; }
                .flame:nth-child(5) { left: 90%; animation-delay: 8s; }
        </style>
    </head>
    <body>
        <!-- Efectos de llamas decorativas -->
        <div class="flame"></div>
        <div class="flame"></div>
        <div class="flame"></div>
        <div class="flame"></div>
        <div class="flame"></div>

        <div class="container">
            {{-- @if (Route::has('login'))
                <header>
                    <div></div>
                    <nav class="nav-links">
                        @auth
                            <a href="{{ url('/dashboard') }}">Panel de Control</a>
                        @else
                            <a href="{{ route('login') }}">Iniciar Sesión</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="primary">Registrarse</a>
                            @endif
                        @endauth
                    </nav>
                </header>
            @endif --}}

            <div class="main-content">
                <div class="text-content">
                    <h1>Inferno Club</h1>
                    <p class="tagline">Licorería Premium</p>
                    <p class="description">
                        Descubre las mejores marcas de licores, vinos y cervezas artesanales. 
                        En Inferno Club encontrarás una selección exclusiva para los paladares más exigentes. 
                        Calidad, variedad y experiencia en cada botella.
                    </p>

                    <div class="features">
                        <div class="feature-card">
                            <div class="feature-icon">🍷</div>
                            <h3>Vinos Selectos</h3>
                            <p>Colección premium de vinos nacionales e importados</p>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon">🥃</div>
                            <h3>Licores Exclusivos</h3>
                            <p>Whisky, Ron, Vodka y más de las mejores marcas</p>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon">🍺</div>
                            <h3>Cervezas Artesanales</h3>
                            <p>Selección de cervezas craft locales e internacionales</p>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon">🎁</div>
                            <h3>Packs & Promociones</h3>
                            <p>Combos especiales y ofertas exclusivas</p>
                        </div>
                    </div>

                    @auth
                        <a href="{{ url('/dashboard') }}" class="cta-button">Explorar Catálogo</a>
                    @else
                        <a href="{{ route('login') }}" class="cta-button">Comenzar Ahora</a>
                    @endauth
                </div>

                <div class="logo-section">
                    <div class="logo-container">
                        <img src="{{ asset('assets/img/logo_inferno.jpg') }}" alt="Inferno Club Logo">
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

@props(['compact' => false])

@if($compact)
    <!-- Versión compacta para sidebar -->
    <div class="relative inline-flex items-center justify-center">
        <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 p-2 rounded-xl shadow-lg transition-all duration-300 hover:scale-105"
             style="box-shadow: 0 8px 25px -8px rgba(59, 130, 246, 0.4);">
            <svg xmlns="http://www.w3.org/2000/svg" 
                 {{ $attributes->merge(['class' => 'w-5 h-5 text-white']) }}
                 viewBox="0 0 24 24" 
                 fill="currentColor">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M11.998 2l.118 .007l.059 .008l.061 .013l.111 .034a.993 .993 0 0 1 .217 .112l.104 .082l.255 .218a11 11 0 0 0 7.189 2.537l.342 -.01a1 1 0 0 1 1.005 .717a13 13 0 0 1 -9.208 16.25a1 1 0 0 1 -.502 0a13 13 0 0 1 -9.209 -16.25a1 1 0 0 1 1.005 -.717a11 11 0 0 0 7.531 -2.527l.263 -.225l.096 -.075a.993 .993 0 0 1 .217 -.112l.112 -.034a.97 .97 0 0 1 .119 -.021l.115 -.007z" 
                      fill="rgba(255,255,255,0.95)"/>
                <path d="M12 9a2 2 0 0 0 -1.995 1.85l-.005 .15l.005 .15a2 2 0 0 0 .995 1.581v1.769l.007 .117a1 1 0 0 0 1.993 -.117l.001 -1.768a2 2 0 0 0 -1.001 -3.732z" 
                      fill="rgba(255,255,255,0.9)"/>
            </svg>
        </div>
    </div>
@else
    <!-- Versión completa para login -->
    <div class="relative inline-block">
        <!-- Fondo con gradiente y sombra -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-700 rounded-2xl shadow-lg transform rotate-3"></div>
        <div class="relative bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 p-4 rounded-2xl shadow-xl transform transition-all duration-300 hover:scale-105 hover:shadow-2xl"
             style="box-shadow: 0 20px 40px -12px rgba(59, 130, 246, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.1);">
            
            <!-- Logo principal -->
            <svg xmlns="http://www.w3.org/2000/svg" 
                 {{ $attributes->merge(['class' => 'w-8 h-8 text-white drop-shadow-sm']) }}
                 viewBox="0 0 24 24" 
                 fill="currentColor">
                
                <!-- Definición de gradientes -->
                <defs>
                    <linearGradient id="shieldGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#ffffff;stop-opacity:1" />
                        <stop offset="50%" style="stop-color:#f1f5f9;stop-opacity:0.9" />
                        <stop offset="100%" style="stop-color:#e2e8f0;stop-opacity:0.8" />
                    </linearGradient>
                    <linearGradient id="lockGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#ffffff;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#cbd5e1;stop-opacity:0.9" />
                    </linearGradient>
                    
                    <!-- Filtros para efectos -->
                    <filter id="softGlow" x="-50%" y="-50%" width="200%" height="200%">
                        <feGaussianBlur stdDeviation="2" result="coloredBlur"/>
                        <feMerge> 
                            <feMergeNode in="coloredBlur"/>
                            <feMergeNode in="SourceGraphic"/>
                        </feMerge>
                    </filter>
                </defs>
                
                <!-- Escudo principal -->
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M11.998 2l.118 .007l.059 .008l.061 .013l.111 .034a.993 .993 0 0 1 .217 .112l.104 .082l.255 .218a11 11 0 0 0 7.189 2.537l.342 -.01a1 1 0 0 1 1.005 .717a13 13 0 0 1 -9.208 16.25a1 1 0 0 1 -.502 0a13 13 0 0 1 -9.209 -16.25a1 1 0 0 1 1.005 -.717a11 11 0 0 0 7.531 -2.527l.263 -.225l.096 -.075a.993 .993 0 0 1 .217 -.112l.112 -.034a.97 .97 0 0 1 .119 -.021l.115 -.007z" 
                      fill="url(#shieldGradient)" 
                      filter="url(#softGlow)"/>
                
                <!-- Candado -->
                <path d="M12 9a2 2 0 0 0 -1.995 1.85l-.005 .15l.005 .15a2 2 0 0 0 .995 1.581v1.769l.007 .117a1 1 0 0 0 1.993 -.117l.001 -1.768a2 2 0 0 0 -1.001 -3.732z" 
                      fill="url(#lockGradient)"
                      filter="url(#softGlow)"/>
                
                <!-- Highlight superior para efecto 3D -->
                <path d="M11.998 2l.118 .007l.059 .008l.061 .013l.111 .034a.993 .993 0 0 1 .217 .112l.104 .082l.255 .218a11 11 0 0 0 7.189 2.537l.342 -.01a1 1 0 0 1 1.005 .717a13 13 0 0 1 -2 3.5c-2 2-4 3-6 3s-4-1-6-3a13 13 0 0 1 -2-3.5a1 1 0 0 1 1.005 -.717a11 11 0 0 0 7.531 -2.527l.263 -.225l.096 -.075a.993 .993 0 0 1 .217 -.112l.112 -.034a.97 .97 0 0 1 .119 -.021l.115 -.007z" 
                      fill="rgba(255,255,255,0.3)"/>
            </svg>
            
            <!-- Brillo decorativo -->
            <div class="absolute top-1 right-1 w-2 h-2 bg-white/30 rounded-full"></div>
        </div>
        
    </div>
@endif
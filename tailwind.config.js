import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './resources/js/**/*.vue',
    ],
    
    // Sin prefijo para mantener compatibilidad total con Soft UI
    prefix: '',
    
    // Configuración ULTRA optimizada para reducir uso de memoria
    corePlugins: {
        preflight: false, // Deshabilitar preflight para preservar estilos base de Soft UI
        container: false,
        float: false,
        clear: false,
        skew: false,
        caretColor: false,
        sepia: false,
        filter: false,
        backdropFilter: false,
        brightness: false,
        contrast: false,
        grayscale: false,
        hueRotate: false,
        invert: false,
        saturate: false,
        blur: false,
        dropShadow: false,
        backdropBrightness: false,
        backdropContrast: false,
        backdropGrayscale: false,
        backdropHueRotate: false,
        backdropInvert: false,
        backdropOpacity: false,
        backdropSaturate: false,
        backdropSepia: false,
        backdropBlur: false,
        divideColor: false,
        divideOpacity: false,
        divideStyle: false,
        divideWidth: false,
        ringColor: false,
        ringOffsetColor: false,
        ringOffsetWidth: false,
        ringOpacity: false,
        ringWidth: false,
        textDecorationColor: false,
        textDecorationStyle: false,
        textDecorationThickness: false,
        textUnderlineOffset: false,
        fontVariantNumeric: false,
        listStyleType: false,
        listStylePosition: false,
        placeholderColor: false,
        placeholderOpacity: false,
        resize: false,
        scrollBehavior: false,
        scrollMargin: false,
        scrollPadding: false,
        scrollSnapAlign: false,
        scrollSnapStop: false,
        scrollSnapType: false,
        touchAction: false,
        userSelect: false,
        willChange: false,
        fill: false,
        stroke: false,
        strokeWidth: false,
    },
    
    // Lista de seguridad MINIMALISTA - solo clases críticas para evitar errores de memoria
    safelist: [
        // Clases críticas de Soft UI que DEBEN estar incluidas
        'px-2.5', 'py-1.4', 'py-2.7', 'pl-8.75', 
        'rounded-1.8', 'w-4.5', 'h-0.5', 'h-9', 
        'xl:ml-68.5', 'min-w-44', 'h-sidenav',
        'text-xxs', 'leading-pro', 'z-990', 'z-sticky',
        'opacity-65', 'ease-soft-in-out', 'duration-250',
        'hover:scale-102', 'rounded-circle', 'h-5.75', 'w-5.75',
        'shadow-soft-xs', 'transform-dropdown', 
        'pointer-events-none', 'pointer-events-auto',
        'from-blue-600', 'to-cyan-400', 'bg-gradient-to-tl',
        'xl:bg-transparent', 'xl:translate-x-0',
        'hover:bg-gray-200', 'xl:left-0', 
        '-translate-x-full', 'translate-x-0', 'transition-transform',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Open Sans', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            // Solo espaciados críticos de Soft UI
            spacing: {
                '1.4': '0.35rem',
                '2.5': '0.625rem',
                '2.7': '0.675rem',
                '4.5': '1.125rem',
                '5.75': '1.4375rem',
                '8.75': '2.1875rem',
                '44': '11rem',
                '68.5': '17.125rem',
                'sidenav': 'calc(100vh - 8rem)',
            },
            // Solo bordes críticos
            borderRadius: {
                '1.8': '0.45rem',
                'circle': '50%',
            },
            // Solo tamaños de fuente críticos
            fontSize: {
                'xxs': ['0.625rem', '1rem'],
            },
            // Solo z-index críticos
            zIndex: {
                '990': '990',
                'sticky': '1020',
            },
            // Solo opacidades críticas
            opacity: {
                '65': '0.65',
            },
            // Solo line heights críticos
            lineHeight: {
                'pro': '1.7',
            },
            // Solo timing functions críticos
            transitionTimingFunction: {
                'soft-in-out': 'cubic-bezier(0.4, 0, 0.2, 1)',
            },
            // Solo duraciones críticas
            transitionDuration: {
                '250': '250ms',
            },
            // Solo sombras críticas
            boxShadow: {
                'soft-xs': '0 3px 6px -4px rgba(0, 0, 0, 0.12), 0 6px 16px 0 rgba(0, 0, 0, 0.08), 0 9px 28px 8px rgba(0, 0, 0, 0.05)',
            },
            // Solo escalas críticas  
            scale: {
                '102': '1.02',
            },
            // Solo dimensiones críticas
            width: {
                '4.5': '1.125rem',
                '5.75': '1.4375rem',
                '44': '11rem',
                '68.5': '17.125rem',
            },
            height: {
                '0.5': '0.125rem',
                '5.75': '1.4375rem',
                '9': '2.25rem',
                'sidenav': 'calc(100vh - 8rem)',
            },
            minWidth: {
                '44': '11rem',
            },
        },
    },

    plugins: [
        forms({
            strategy: 'class', // Usar estrategia de clase para evitar conflictos
        }),
    ],
};

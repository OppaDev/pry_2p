// resources/js/app.js

// 1. Importar librerías de terceros
import './bootstrap';
import Alpine from 'alpinejs';
import PerfectScrollbar from 'perfect-scrollbar';
import Chart from 'chart.js/auto';

// 2. Importar nuestros componentes personalizados
import './modal-manager';


// 3. Hacer las librerías globales
window.Alpine = Alpine;
window.PerfectScrollbar = PerfectScrollbar;
window.Chart = Chart;

// 4. Agregar estilos CSS para mejorar la carga
const appStyles = document.createElement('style');
appStyles.textContent = `
    /* Estilos para mejor carga de la aplicación */
    body {
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }
    
    body.app-loaded {
        opacity: 1;
    }
    
    /* Prevenir FOUC en elementos críticos */
    .sidenav, .navbar, .modal {
        transition: all 0.2s ease-in-out;
    }
    
    /* Loading state para formularios */
    .loading {
        pointer-events: none;
        opacity: 0.7;
    }
    
    .loading * {
        cursor: wait !important;
    }
`;
document.head.appendChild(appStyles);

// 4. Definir variables globales dummy para compatibilidad
window.page = window.page || window.location.pathname.split("/").pop().split(".")[0] || 'dashboard';
var page = window.page;
window.aux = window.location.pathname.split("/");
window.to_build = (window.aux.includes('pages') ? '../' : './');
window.root = window.location.pathname.split("/");

// 5. Importar scripts de la plantilla (excepto el del burger)
import './soft-ui/nav-pills.js';
import './soft-ui/dropdown.js';
// Comentados para evitar conflictos con Soft UI legacy
//import './soft-ui/navbar-collapse.js';
//import './soft-ui/fixed-plugin.js';
//import './soft-ui/navbar-sticky.js';
import './soft-ui/tooltips.js';
import './soft-ui/chart-1.js';
import './soft-ui/chart-2.js';

// 6. Lógica del Sidenav integrada y robusta con mejor manejo de errores
function initializeSidenavBurger() {
    try {
        const sidenav = document.querySelector("aside");
        const sidenav_trigger = document.querySelector("[sidenav-trigger]");
        const sidenav_close_button = document.querySelector("[sidenav-close]");
        
        if (!sidenav_trigger || !sidenav) {
            console.warn('⚠️ Elementos del sidenav no encontrados');
            return; // Salida segura
        }

        // Estado inicial: oculto en móvil
        if (window.innerWidth < 1280) {
            sidenav.classList.remove('translate-x-0');
            sidenav.classList.add('-translate-x-full');
        }

        sidenav_trigger.addEventListener("click", function () {
            sidenav.classList.toggle('translate-x-0');
            sidenav.classList.toggle('-translate-x-full');
            // Mostrar/ocultar botón de cerrar solo en móvil
            if (sidenav_close_button) {
                sidenav_close_button.classList.toggle('hidden');
            }
        });

        if (sidenav_close_button) {
            sidenav_close_button.addEventListener("click", function () {
                sidenav.classList.remove('translate-x-0');
                sidenav.classList.add('-translate-x-full');
                sidenav_close_button.classList.add('hidden');
            });
        }

        // Cerrar sidenav al hacer click fuera (solo móvil)
        window.addEventListener("click", function (e) {
            if (window.innerWidth < 1280 && !sidenav.contains(e.target) && !sidenav_trigger.contains(e.target)) {
                if (sidenav.classList.contains("translate-x-0")) {
                    sidenav.classList.remove('translate-x-0');
                    sidenav.classList.add('-translate-x-full');
                    if (sidenav_close_button) sidenav_close_button.classList.add('hidden');
                }
            }
        });

        // Ajustar al cambiar tamaño de pantalla
        window.addEventListener('resize', function () {
            if (window.innerWidth >= 1280) {
                sidenav.classList.add('translate-x-0');
                sidenav.classList.remove('-translate-x-full');
                if (sidenav_close_button) sidenav_close_button.classList.add('hidden');
            } else {
                sidenav.classList.remove('translate-x-0');
                sidenav.classList.add('-translate-x-full');
            }
        });
        
        console.log('✅ Sidenav inicializado correctamente');
        
    } catch (error) {
        console.error('❌ Error al inicializar sidenav:', error);
    }
}

// 7. Inicialización centralizada con mejor manejo de errores
document.addEventListener('DOMContentLoaded', function() {
    try {
        // Inicializar Alpine.js
        Alpine.start();
        
        // Inicializar otros componentes
        initializeSidenavBurger();
        
        // Marcar la página como completamente cargada
        document.body.classList.add('app-loaded');
        
        console.log('✅ App.js y Soft UI inicializados correctamente.');
        
    } catch (error) {
        console.error('❌ Error al inicializar la aplicación:', error);
        
        // Intentar inicialización básica en caso de error
        try {
            Alpine.start();
            console.warn('⚠️ Inicialización básica aplicada tras error.');
        } catch (fallbackError) {
            console.error('❌ Error crítico en inicialización:', fallbackError);
        }
    }
});
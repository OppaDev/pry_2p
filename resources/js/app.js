// resources/js/app.js

// 1. Importar librerías de terceros
import './bootstrap';
import Alpine from 'alpinejs';
import PerfectScrollbar from 'perfect-scrollbar';
import Chart from 'chart.js/auto';

// 2. Hacer las librerías globales
window.Alpine = Alpine;
window.PerfectScrollbar = PerfectScrollbar;
window.Chart = Chart;

// 3. Definir variables globales dummy para compatibilidad
window.page = window.page || window.location.pathname.split("/").pop().split(".")[0] || 'dashboard';
var page = window.page;
window.aux = window.location.pathname.split("/");
window.to_build = (window.aux.includes('pages') ? '../' : './');
window.root = window.location.pathname.split("/");

// 4. Importar scripts de la plantilla (excepto el del burger)
import './soft-ui/nav-pills.js';
import './soft-ui/dropdown.js';
// Comentados para evitar conflictos con Soft UI legacy
// import './soft-ui/navbar-collapse.js';
// import './soft-ui/fixed-plugin.js';
// import './soft-ui/navbar-sticky.js';
import './soft-ui/tooltips.js';
import './soft-ui/chart-1.js';
import './soft-ui/chart-2.js';

// 5. Lógica del Sidenav integrada y robusta
function initializeSidenavBurger() {
    const sidenav = document.querySelector("aside");
    const sidenav_trigger = document.querySelector("[sidenav-trigger]");
    const sidenav_close_button = document.querySelector("[sidenav-close]");
    if (!sidenav_trigger || !sidenav) return; // Salida segura

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
}

// 6. Inicialización centralizada
document.addEventListener("DOMContentLoaded", function(event) {
    initializeSidenavBurger();
    Alpine.start();
    console.log('✅ App.js y Soft UI inicializados correctamente.');
});
import './bootstrap';
import Alpine from 'alpinejs';

// Configurar Alpine
window.Alpine = Alpine;
Alpine.start();

// 🔧 SOFT UI + TAILWIND COMPATIBILITY HANDLER
document.addEventListener('DOMContentLoaded', function() {
    // Esperar a que Soft UI se cargue completamente
    setTimeout(() => {
        initializeSoftUICompatibility();
    }, 100);
});

function initializeSoftUICompatibility() {
    // 🎯 DROPDOWNS MEJORADOS
    initializeDropdowns();
    
    // 🧭 SIDENAV MEJORADO
    initializeSidenav();
    
    // 🎨 FIXED PLUGIN COMPATIBILITY
    initializeFixedPlugin();
    
    // 📱 RESPONSIVE HANDLERS
    initializeResponsiveHandlers();
    
    console.log('✅ Soft UI + Tailwind compatibility initialized');
}

function initializeDropdowns() {
    const dropdownTriggers = document.querySelectorAll('[dropdown-trigger]');
    
    dropdownTriggers.forEach(trigger => {
        const dropdown = trigger.parentElement.querySelector('[dropdown-menu]');
        
        if (dropdown) {
            // Remover listeners existentes para evitar duplicados
            trigger.removeEventListener('click', handleDropdownClick);
            
            // Agregar nuevo listener
            trigger.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Cerrar otros dropdowns
                document.querySelectorAll('[dropdown-menu]').forEach(menu => {
                    if (menu !== dropdown) {
                        menu.classList.add('opacity-0', 'pointer-events-none');
                        menu.classList.remove('transform-dropdown-show');
                        menu.classList.add('transform-dropdown');
                    }
                });
                
                // Toggle dropdown actual
                const isOpen = !dropdown.classList.contains('opacity-0');
                
                if (isOpen) {
                    dropdown.classList.add('opacity-0', 'pointer-events-none');
                    dropdown.classList.remove('transform-dropdown-show');
                    dropdown.classList.add('transform-dropdown');
                    trigger.setAttribute('aria-expanded', 'false');
                } else {
                    dropdown.classList.remove('opacity-0', 'pointer-events-none');
                    dropdown.classList.add('transform-dropdown-show');
                    dropdown.classList.remove('transform-dropdown');
                    trigger.setAttribute('aria-expanded', 'true');
                }
            });
        }
    });
    
    // Cerrar dropdowns al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (!e.target.closest('[dropdown-trigger]') && !e.target.closest('[dropdown-menu]')) {
            document.querySelectorAll('[dropdown-menu]').forEach(menu => {
                menu.classList.add('opacity-0', 'pointer-events-none');
                menu.classList.remove('transform-dropdown-show');
                menu.classList.add('transform-dropdown');
            });
            
            document.querySelectorAll('[dropdown-trigger]').forEach(trigger => {
                trigger.setAttribute('aria-expanded', 'false');
            });
        }
    });
}

function initializeSidenav() {
    const sidenavTrigger = document.querySelector('[sidenav-trigger]');
    const sidenav = document.querySelector('aside');
    const sidenavClose = document.querySelector('[sidenav-close]');
    
    if (sidenavTrigger && sidenav) {
        sidenavTrigger.addEventListener('click', function(e) {
            e.preventDefault();
            toggleSidenav();
        });
    }
    
    if (sidenavClose) {
        sidenavClose.addEventListener('click', function(e) {
            e.preventDefault();
            closeSidenav();
        });
    }
    
    // Cerrar sidenav al hacer clic fuera en móvil
    document.addEventListener('click', function(e) {
        if (window.innerWidth < 1280 && sidenav && sidenavTrigger) {
            if (!sidenav.contains(e.target) && !sidenavTrigger.contains(e.target)) {
                if (!sidenav.classList.contains('-translate-x-full')) {
                    closeSidenav();
                }
            }
        }
    });
}

function toggleSidenav() {
    const sidenav = document.querySelector('aside');
    const sidenavClose = document.querySelector('[sidenav-close]');
    
    if (sidenav) {
        sidenav.classList.toggle('-translate-x-full');
        sidenav.classList.toggle('translate-x-0');
        sidenav.classList.toggle('shadow-soft-xl');
        
        if (sidenavClose) {
            sidenavClose.classList.toggle('hidden');
        }
    }
}

function closeSidenav() {
    const sidenav = document.querySelector('aside');
    const sidenavClose = document.querySelector('[sidenav-close]');
    
    if (sidenav) {
        sidenav.classList.add('-translate-x-full');
        sidenav.classList.remove('translate-x-0');
        sidenav.classList.remove('shadow-soft-xl');
        
        if (sidenavClose) {
            sidenavClose.classList.add('hidden');
        }
    }
}

function initializeFixedPlugin() {
    const fixedPluginButton = document.querySelector('[fixed-plugin-button]');
    const fixedPluginCard = document.querySelector('[fixed-plugin-card]');
    const fixedPluginClose = document.querySelector('[fixed-plugin-close-button]');
    
    if (fixedPluginButton && fixedPluginCard) {
        fixedPluginButton.addEventListener('click', function(e) {
            e.preventDefault();
            fixedPluginCard.classList.toggle('-right-90');
            fixedPluginCard.classList.toggle('right-0');
        });
    }
    
    if (fixedPluginClose && fixedPluginCard) {
        fixedPluginClose.addEventListener('click', function(e) {
            e.preventDefault();
            fixedPluginCard.classList.add('-right-90');
            fixedPluginCard.classList.remove('right-0');
        });
    }
}

function initializeResponsiveHandlers() {
    // Manejar cambios de tamaño de ventana
    window.addEventListener('resize', function() {
        const sidenav = document.querySelector('aside');
        
        // En pantallas grandes, siempre mostrar sidenav
        if (window.innerWidth >= 1280 && sidenav) {
            sidenav.classList.add('xl:translate-x-0');
            sidenav.classList.remove('-translate-x-full');
        }
        
        // En pantallas pequeñas, ocultar sidenav por defecto
        if (window.innerWidth < 1280 && sidenav) {
            sidenav.classList.add('-translate-x-full');
            sidenav.classList.remove('translate-x-0');
        }
    });
}

// Función auxiliar para manejar clicks de dropdown
function handleDropdownClick(e) {
    // Esta función se usa como referencia para removeEventListener
    e.preventDefault();
}

// Exportar funciones si es necesario
window.SoftUICompatibility = {
    initializeDropdowns,
    initializeSidenav,
    toggleSidenav,
    closeSidenav
};

import './soft-ui/perfect-scrollbar.js';
import './soft-ui/chart-1.js'; // Asumiendo que necesita Chart.js, que se debe cargar antes
import './soft-ui/chart-2.js';
import './soft-ui/dropdown.js';
import './soft-ui/fixed-plugin.js';
import './soft-ui/nav-pills.js';
import './soft-ui/navbar-collapse.js';
import './soft-ui/navbar-sticky.js';
import './soft-ui/sidenav-burger.js';
import './soft-ui/tooltips.js';
import './soft-ui/soft-ui-dashboard-tailwind.js';
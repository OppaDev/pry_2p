// Soft UI Dashboard - Modal Enhancement
document.addEventListener('DOMContentLoaded', function() {
    // Mejorar la apariencia de los botones con efectos de ripple
    function createRippleEffect(button, event) {
        const ripple = document.createElement('span');
        const rect = button.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = event.clientX - rect.left - size / 2;
        const y = event.clientY - rect.top - size / 2;
        
        ripple.style.cssText = `
            position: absolute;
            width: ${size}px;
            height: ${size}px;
            left: ${x}px;
            top: ${y}px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: scale(0);
            animation: ripple 0.6s linear;
            pointer-events: none;
        `;
        
        button.style.position = 'relative';
        button.style.overflow = 'hidden';
        button.appendChild(ripple);
        
        setTimeout(() => {
            ripple.remove();
        }, 600);
    }
    
    // Agregar efecto ripple a botones de eliminar
    document.addEventListener('click', function(event) {
        if (event.target.matches('.modal-button-delete') || 
            event.target.closest('.modal-button-delete')) {
            const button = event.target.matches('.modal-button-delete') ? 
                         event.target : event.target.closest('.modal-button-delete');
            createRippleEffect(button, event);
        }
    });
    
    // Animación de carga para botones
    function showLoadingState(button) {
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Eliminando...';
        button.disabled = true;
        
        return function() {
            button.innerHTML = originalText;
            button.disabled = false;
        };
    }
    
    // Manejar submit de formularios de eliminación
    document.addEventListener('submit', function(event) {
        if (event.target.querySelector('.modal-button-delete')) {
            const button = event.target.querySelector('.modal-button-delete');
            const resetButton = showLoadingState(button);
            
            // Si hay error, restaurar el botón después de 3 segundos
            setTimeout(() => {
                if (button.disabled) {
                    resetButton();
                }
            }, 3000);
        }
    });
    
    // Animación suave para las filas de tabla al eliminar
    function animateRowRemoval(row) {
        row.style.transition = 'all 0.3s ease-out';
        row.style.opacity = '0';
        row.style.transform = 'translateX(-100%)';
        row.style.backgroundColor = '#fee2e2';
        
        setTimeout(() => {
            row.style.height = '0';
            row.style.padding = '0';
            row.style.margin = '0';
        }, 300);
    }
    
    // Mejorar feedback visual en estados hover de botones
    const actionButtons = document.querySelectorAll('.btn-soft-transition');
    actionButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 8px 25px -8px rgba(0, 0, 0, 0.3)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '';
        });
    });
    
    // Efecto de focus mejorado para accesibilidad
    document.addEventListener('focusin', function(event) {
        if (event.target.matches('button, a, input, select, textarea')) {
            event.target.style.outline = '2px solid rgba(59, 130, 246, 0.5)';
            event.target.style.outlineOffset = '2px';
        }
    });
    
    document.addEventListener('focusout', function(event) {
        if (event.target.matches('button, a, input, select, textarea')) {
            event.target.style.outline = '';
            event.target.style.outlineOffset = '';
        }
    });
});

// Agregar estilos CSS para la animación ripple
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    .table-row-hover {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .table-row-hover:hover {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transform: translateY(-1px);
    }
    
    .action-buttons {
        opacity: 0.8;
        transition: opacity 0.2s ease-in-out;
    }
    
    .table-row-hover:hover .action-buttons {
        opacity: 1;
    }
    
    .btn-soft-transition {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .modal-backdrop {
        backdrop-filter: blur(8px);
        background: rgba(0, 0, 0, 0.4);
    }
    
    .modal-content {
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 
                    0 0 0 1px rgba(255, 255, 255, 0.1),
                    0 0 80px rgba(0, 0, 0, 0.1);
    }
`;
document.head.appendChild(style);

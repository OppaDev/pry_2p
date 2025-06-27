// Modal Manager - Sistema centralizado para manejo de modales
class ModalManager {
    static openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        modal.classList.add('modal-backdrop');
        
        this.animateIn(modal);
    }
    
    static closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;
        
        const modalContent = modal.querySelector('.modal-content');
        if (modalContent) {
            modalContent.style.transition = 'all 0.2s ease-in-out';
            modalContent.style.opacity = '0';
            modalContent.style.transform = 'translateY(-10px) scale(0.95)';
        }
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('modal-backdrop');
            document.body.style.overflow = 'auto';
            this.resetStyles(modalContent);
        }, 200);
    }
    
    static animateIn(modal) {
        requestAnimationFrame(() => {
            const modalContent = modal.querySelector('.modal-content');
            if (modalContent) {
                modalContent.style.opacity = '0';
                modalContent.style.transform = 'translateY(-20px) scale(0.95)';
                
                requestAnimationFrame(() => {
                    modalContent.style.transition = 'all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1)';
                    modalContent.style.opacity = '1';
                    modalContent.style.transform = 'translateY(0) scale(1)';
                });
            }
        });
    }
    
    static resetStyles(modalContent) {
        if (modalContent) {
            modalContent.style.opacity = '';
            modalContent.style.transform = '';
            modalContent.style.transition = '';
        }
    }
    
    static init() {
        // Event listeners globales
        document.addEventListener('click', this.handleBackdropClick.bind(this));
        document.addEventListener('keydown', this.handleEscapeKey.bind(this));
        document.addEventListener('click', this.preventModalClose.bind(this));
        document.addEventListener('submit', this.handleFormSubmit.bind(this));
    }
    
    static handleBackdropClick(event) {
        if (event.target.classList.contains('modal-backdrop')) {
            const modalId = event.target.id;
            if (modalId) this.closeModal(modalId);
        }
    }
    
    static handleEscapeKey(event) {
        if (event.key === 'Escape') {
            const modals = document.querySelectorAll('[id$="-modal"]:not(.hidden)');
            modals.forEach(modal => this.closeModal(modal.id));
        }
    }
    
    static preventModalClose(event) {
        if (event.target.closest('.modal-content')) {
            event.stopPropagation();
        }
    }
    
    static handleFormSubmit(event) {
        // Manejar formularios de eliminación con loading state
        if (event.target.classList.contains('delete-form')) {
            const submitButton = event.target.querySelector('button[type="submit"]');
            if (submitButton) {
                this.showLoadingState(submitButton);
            }
        }
    }
    
    static showLoadingState(button) {
        const originalContent = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Eliminando...';
        button.disabled = true;
        button.classList.add('opacity-75', 'cursor-not-allowed');
        
        // Restaurar estado original si hay error (opcional)
        setTimeout(() => {
            if (button.disabled) {
                button.innerHTML = originalContent;
                button.disabled = false;
                button.classList.remove('opacity-75', 'cursor-not-allowed');
            }
        }, 10000);
    }
    
    static createRippleEffect(button, event) {
        const ripple = document.createElement('span');
        const rect = button.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = event.clientX - rect.left - size / 2;
        const y = event.clientY - rect.top - size / 2;
        
        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        ripple.classList.add('ripple');
        
        button.appendChild(ripple);
        
        setTimeout(() => {
            ripple.remove();
        }, 600);
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    ModalManager.init();
    
    // Agregar efecto ripple a botones de acción
    document.addEventListener('click', function(event) {
        if (event.target.closest('.btn-soft-transition')) {
            const button = event.target.closest('.btn-soft-transition');
            ModalManager.createRippleEffect(button, event);
        }
    });
});

// Exportar funciones globales para compatibilidad con templates
window.openModal = (modalId) => ModalManager.openModal(modalId);
window.closeModal = (modalId) => ModalManager.closeModal(modalId);

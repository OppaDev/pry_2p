// Modal Manager - Sistema centralizado para manejo de modales
class ModalManager {
    static openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
        
        this.animateIn(modal);
        this.clearFields(modalId);
        this.focusFirstField(modalId);
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
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
            this.resetStyles(modalContent);
            this.clearFields(modalId);
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

    static clearFields(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        // Limpiar textareas y inputs
        const textareas = modal.querySelectorAll('textarea');
        const inputs = modal.querySelectorAll('input[type="text"], input[type="password"]');
        
        textareas.forEach(textarea => {
            textarea.value = '';
            textarea.classList.remove('border-red-500', 'ring-red-200');
        });
        
        inputs.forEach(input => {
            if (input.type !== 'hidden') {
                input.value = '';
                input.classList.remove('border-red-500', 'ring-red-200');
            }
        });
    }

    static focusFirstField(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        setTimeout(() => {
            const firstField = modal.querySelector('textarea, input[type="text"], input[type="password"]');
            if (firstField && firstField.type !== 'hidden') {
                firstField.focus();
            }
        }, 100);
    }

    static validateRequiredField(fieldId, minLength = 1) {
        const field = document.getElementById(fieldId);
        if (!field) return false;

        const value = field.value.trim();
        let isValid = value.length >= minLength;

        if (!isValid) {
            field.focus();
            field.classList.add('border-red-500', 'ring-red-200');
            setTimeout(() => {
                field.classList.remove('border-red-500', 'ring-red-200');
            }, 3000);
        }

        return isValid;
    }

    static transferFieldToHidden(sourceId, targetId) {
        const source = document.getElementById(sourceId);
        const target = document.getElementById(targetId);
        
        if (source && target) {
            target.value = source.value;
        }
    }

    // Funciones específicas para cada tipo de modal
    static submitDeleteForm(modalId) {
        const motivoValid = this.validateRequiredField(`motivo-${modalId}`);
        const passwordValid = this.validateRequiredField(`password-${modalId}`);
        
        if (!motivoValid || !passwordValid) return false;

        this.transferFieldToHidden(`motivo-${modalId}`, `motivo-input-${modalId}`);
        this.transferFieldToHidden(`password-${modalId}`, `password-input-${modalId}`);
        document.getElementById(`delete-form-${modalId}`).submit();
    }

    static submitForceDeleteForm(modalId) {
        const motivoValid = this.validateRequiredField(`motivo-${modalId}`, 10);
        const passwordValid = this.validateRequiredField(`password-${modalId}`);
        
        if (!motivoValid || !passwordValid) return false;

        this.transferFieldToHidden(`motivo-${modalId}`, `motivo-input-${modalId}`);
        this.transferFieldToHidden(`password-${modalId}`, `password-input-${modalId}`);
        document.getElementById(`force-delete-form-${modalId}`).submit();
    }

    static submitRestoreForm(modalId) {
        const motivoValid = this.validateRequiredField(`motivo-${modalId}`);
        const passwordValid = this.validateRequiredField(`password-${modalId}`);
        
        if (!motivoValid || !passwordValid) return false;

        this.transferFieldToHidden(`motivo-${modalId}`, `motivo-input-${modalId}`);
        this.transferFieldToHidden(`password-${modalId}`, `password-input-${modalId}`);
        document.getElementById(`restore-form-${modalId}`).submit();
    }
    
    static init() {
        // Evitar múltiples inicializaciones
        if (window.modalManagerInitialized) return;
        window.modalManagerInitialized = true;

        // Event listeners globales
        document.addEventListener('click', this.handleBackdropClick.bind(this));
        document.addEventListener('keydown', this.handleEscapeKey.bind(this));
        document.addEventListener('click', this.preventModalClose.bind(this));
        document.addEventListener('submit', this.handleFormSubmit.bind(this));
    }
    
    static handleBackdropClick(event) {
        // Solo cerrar si se hace clic exactamente en el backdrop
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
        // Manejar formularios con loading state
        if (event.target.classList.contains('delete-form') || 
            event.target.classList.contains('force-delete-form')) {
            const submitButton = event.target.querySelector('button[type="submit"], button[onclick*="submit"]');
            if (submitButton) {
                this.showLoadingState(submitButton);
            }
        }
    }
    
    static showLoadingState(button) {
        const originalContent = button.innerHTML;
        
        if (button.onclick && button.onclick.toString().includes('ForceDelete')) {
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>ELIMINANDO PERMANENTEMENTE...';
        } else if (button.onclick && button.onclick.toString().includes('Restore')) {
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Restaurando...';
        } else {
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Eliminando...';
        }
        
        button.disabled = true;
        button.classList.add('opacity-75', 'cursor-not-allowed');
        
        // Restaurar estado original si hay error (timeout de seguridad)
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

// Funciones específicas para cada tipo de modal
window.submitDeleteForm = (modalId) => ModalManager.submitDeleteForm(modalId);
window.submitForceDeleteForm = (modalId) => ModalManager.submitForceDeleteForm(modalId);
window.submitRestoreForm = (modalId) => ModalManager.submitRestoreForm(modalId);

// Funciones de compatibilidad para los modales existentes
window.openDeleteModal = (modalId) => ModalManager.openModal(modalId);
window.closeDeleteModal = (modalId) => ModalManager.closeModal(modalId);
window.openForceDeleteModal = (modalId) => ModalManager.openModal(modalId);
window.closeForceDeleteModal = (modalId) => ModalManager.closeModal(modalId);
window.openRestoreModal = (modalId) => ModalManager.openModal(modalId);
window.closeRestoreModal = (modalId) => ModalManager.closeModal(modalId);

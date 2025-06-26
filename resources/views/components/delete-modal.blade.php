<!-- Modal de Confirmación de Eliminación -->
<div id="{{ $modalId }}" class="fixed inset-0 z-50 hidden overflow-y-auto modal-backdrop">
    <div class="flex items-center justify-center min-h-screen px-4 py-8 modal-container">
        <div class="relative w-full max-w-md mx-auto">
            <!-- Modal Container -->
            <div class="relative bg-white rounded-2xl shadow-2xl transform transition-all duration-300 ease-soft-spring modal-content" 
                 style="box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(255, 255, 255, 0.1);">
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-6 pb-4">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center justify-center w-14 h-14 rounded-2xl modal-header-icon">
                            <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-800 leading-tight">{{ $title }}</h3>
                            <p class="text-sm text-slate-500 mt-1">Esta acción no se puede deshacer</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeModal('{{ $modalId }}')" 
                        class="p-2.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-all duration-200 btn-soft-transition">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
                
                <!-- Modal Body -->
                <div class="px-6 pb-2">
                    <div class="modal-info-box rounded-xl p-5 mb-4">
                        <p class="text-slate-700 text-base leading-relaxed font-medium">
                            {{ $message }}
                        </p>
                        @if(isset($itemName))
                            <div class="mt-4 p-4 modal-item-details rounded-lg">
                                <p class="font-bold text-slate-800 text-lg">{{ $itemName }}</p>
                                @if(isset($itemDetails))
                                    <p class="text-slate-600 text-sm mt-2">{{ $itemDetails }}</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="flex items-center justify-end space-x-3 p-6 pt-4 bg-gradient-to-r from-slate-50 to-slate-100 rounded-b-2xl border-t border-slate-200/50">
                    <button type="button" onclick="closeModal('{{ $modalId }}')"
                        class="px-6 py-3 text-base font-semibold text-slate-700 modal-button-cancel rounded-xl focus:outline-none focus:ring-2 focus:ring-slate-200 focus:border-slate-400 btn-soft-transition">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </button>
                    <form method="POST" action="{{ $deleteRoute }}" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-6 py-3 text-base font-bold text-white modal-button-delete rounded-xl focus:outline-none focus:ring-2 focus:ring-red-200 focus:ring-offset-2 btn-soft-transition">
                            <i class="fas fa-trash mr-2"></i>
                            {{ $confirmText ?? 'Eliminar' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Añadir clase para backdrop
        modal.classList.add('modal-backdrop');
        
        // Animación de entrada más suave
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
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
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
            
            // Reset styles
            if (modalContent) {
                modalContent.style.opacity = '';
                modalContent.style.transform = '';
                modalContent.style.transition = '';
            }
        }, 200);
    }
}

// Cerrar modal al hacer clic fuera de él
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('modal-backdrop')) {
        const modalId = event.target.id;
        if (modalId) {
            closeModal(modalId);
        }
    }
});

// Cerrar modal con tecla ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modals = document.querySelectorAll('[id$="-modal"]:not(.hidden)');
        modals.forEach(modal => {
            closeModal(modal.id);
        });
    }
});

// Prevenir que los clics dentro del modal lo cierren
document.addEventListener('click', function(event) {
    if (event.target.closest('.modal-content')) {
        event.stopPropagation();
    }
});
</script>

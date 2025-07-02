<!-- Modal de Confirmación de Eliminación -->
<div id="{{ $modalId }}" class="fixed inset-0 z-50 hidden overflow-y-auto modal-backdrop">
    <div class="flex items-center justify-center min-h-screen px-4 py-8 modal-container">
        <div class="relative w-full max-w-md mx-auto">
            <!-- Modal Container -->
            <div class="relative bg-white rounded-2xl shadow-2xl transform transition-all duration-300 ease-soft-spring modal-content" 
                 onclick="event.stopPropagation()"
                 style="box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(255, 255, 255, 0.1);">
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-6 pb-4">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center justify-center w-14 h-14 rounded-2xl modal-header-icon">
                            <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-800 leading-tight">{{ $title }}</h3>
                            <p class="text-sm text-slate-500 mt-1">Se moverá a la papelera y podrá ser restaurado</p>
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
                    <!-- Campo de motivo -->
                    <div class="mb-4">
                        <label for="motivo-{{ $modalId }}" class="block text-sm font-bold text-slate-700 mb-2">
                            <i class="fas fa-comment-alt mr-1 text-slate-500"></i>
                            Motivo de la eliminación <span class="text-red-500">*</span>
                        </label>
                        <textarea id="motivo-{{ $modalId }}" name="motivo" 
                                  class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-red-200 focus:border-red-400 resize-none transition-all duration-200"
                                  rows="3" 
                                  placeholder="Describe brevemente el motivo de esta eliminación..."
                                  required></textarea>
                        <p class="text-xs text-slate-500 mt-1">Este motivo quedará registrado en el historial de auditoría</p>
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="flex items-center justify-end space-x-3 p-6 pt-4 bg-gradient-to-r from-slate-50 to-slate-100 rounded-b-2xl border-t border-slate-200/50">
                    <button type="button" onclick="closeModal('{{ $modalId }}')"
                        class="px-6 py-3 text-base font-semibold text-slate-700 modal-button-cancel rounded-xl focus:outline-none focus:ring-2 focus:ring-slate-200 focus:border-slate-400 btn-soft-transition">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </button>
                    <form method="POST" action="{{ $deleteRoute }}" class="inline-block delete-form" id="delete-form-{{ $modalId }}">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="motivo" id="motivo-input-{{ $modalId }}">
                        <button type="button" onclick="submitDeleteForm('{{ $modalId }}')"
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
    openDeleteModal(modalId);
}

function openDeleteModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
    document.getElementById(modalId).classList.add('flex');
    // Limpiar el textarea al abrir
    const motivoField = document.getElementById('motivo-' + modalId);
    if (motivoField) {
        motivoField.value = '';
        setTimeout(() => {
            motivoField.focus();
        }, 100);
    }
}

function closeModal(modalId) {
    closeDeleteModal(modalId);
}

function closeDeleteModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.getElementById(modalId).classList.remove('flex');
    // Limpiar el textarea al cerrar
    const motivoField = document.getElementById('motivo-' + modalId);
    if (motivoField) {
        motivoField.value = '';
        motivoField.classList.remove('border-red-500', 'ring-red-200');
    }
}

function submitDeleteForm(modalId) {
    const motivoTextarea = document.getElementById('motivo-' + modalId);
    const motivoInput = document.getElementById('motivo-input-' + modalId);
    const form = document.getElementById('delete-form-' + modalId);
    
    if (!motivoTextarea.value.trim()) {
        motivoTextarea.focus();
        motivoTextarea.classList.add('border-red-500', 'ring-red-200');
        setTimeout(() => {
            motivoTextarea.classList.remove('border-red-500', 'ring-red-200');
        }, 3000);
        return false;
    }
    
    motivoInput.value = motivoTextarea.value;
    form.submit();
}

// Solo registrar event listeners una vez para modales de eliminación
if (!window.deleteModalEventListenersRegistered) {
    window.deleteModalEventListenersRegistered = true;
    
    // Cerrar modal al hacer clic fuera (pero no en el contenido del modal)
    document.addEventListener('click', function(event) {
        const modals = document.querySelectorAll('[id*="delete"][id$="-modal"]:not([id*="force-delete"])');
        modals.forEach(modal => {
            if (!modal.classList.contains('hidden')) {
                // Solo cerrar si se hace clic exactamente en el overlay (el elemento con clase modal-backdrop)
                if (event.target === modal) {
                    closeDeleteModal(modal.id);
                }
            }
        });
    });
    
    // Cerrar modal con Escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const deleteModals = document.querySelectorAll('[id*="delete"][id$="-modal"]:not([id*="force-delete"])');
            deleteModals.forEach(modal => {
                if (!modal.classList.contains('hidden')) {
                    closeDeleteModal(modal.id);
                }
            });
        }
    });
}
</script>

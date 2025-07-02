<!-- Modal de Confirmación de Eliminación Permanente -->
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
                        <div class="flex items-center justify-center w-14 h-14 rounded-2xl" 
                             style="background: linear-gradient(135deg, #fecaca 0%, #fca5a5 100%); box-shadow: 0 4px 14px 0 rgba(239, 68, 68, 0.2);">
                            <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-800 leading-tight">{{ $title }}</h3>
                            <p class="text-sm text-slate-500 mt-1">Esta acción NO se puede deshacer</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeModal('{{ $modalId }}')" 
                        class="p-2.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-all duration-200 btn-soft-transition">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
                
                <!-- Modal Body -->
                <div class="px-6 pb-2">
                    <div class="rounded-xl p-5 mb-4" 
                         style="background: linear-gradient(135deg, #fecaca 0%, #fca5a5 100%); border: 1px solid #f87171;">
                        <p class="text-slate-700 text-base leading-relaxed font-medium">
                            {{ $message }}
                        </p>
                        @if(isset($itemName))
                            <div class="mt-4 p-4 rounded-lg" 
                                 style="background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%); border: 1px solid #f87171; box-shadow: inset 0 1px 0 0 rgba(255, 255, 255, 0.1);">
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
                            <i class="fas fa-comment-alt mr-1 text-red-500"></i>
                            Motivo obligatorio (explica por qué eliminas permanentemente) <span class="text-red-500">*</span>
                        </label>
                        <textarea id="motivo-{{ $modalId }}" name="motivo" 
                                  class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-red-200 focus:border-red-400 resize-none transition-all duration-200"
                                  rows="3" 
                                  maxlength="255"
                                  placeholder="Ej: Datos duplicados, registro erróneo, solicitud del cliente..."
                                  required></textarea>
                        <p class="text-xs text-slate-500 mt-1">Mínimo 10 caracteres, máximo 255. Este motivo quedará registrado en el historial de auditoría</p>
                    </div>
                    
                    <!-- Campo de contraseña -->
                    <div class="mb-4">
                        <label for="password-{{ $modalId }}" class="block text-sm font-bold text-slate-700 mb-2">
                            <i class="fas fa-lock mr-1 text-red-500"></i>
                            Confirma tu contraseña para eliminar permanentemente <span class="text-red-500">*</span>
                        </label>
                        <input type="password" id="password-{{ $modalId }}" name="password" 
                               class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-red-200 focus:border-red-400 transition-all duration-200"
                               placeholder="Ingresa tu contraseña actual"
                               required>
                        <p class="text-xs text-slate-500 mt-1">Se requiere tu contraseña para confirmar la eliminación permanente</p>
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="flex items-center justify-end space-x-3 p-6 pt-4 bg-gradient-to-r from-red-50 to-red-100 rounded-b-2xl border-t border-red-200/50">
                    <button type="button" onclick="closeModal('{{ $modalId }}')"
                        class="px-6 py-3 text-base font-semibold text-slate-700 bg-gradient-to-r from-slate-200 to-slate-300 hover:from-slate-300 hover:to-slate-400 rounded-xl focus:outline-none focus:ring-2 focus:ring-slate-200 focus:border-slate-400 btn-soft-transition">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </button>
                    <form method="POST" action="{{ $deleteRoute }}" class="inline-block force-delete-form" id="force-delete-form-{{ $modalId }}">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="motivo" id="motivo-input-{{ $modalId }}">
                        <input type="hidden" name="password" id="password-input-{{ $modalId }}">
                        <button type="button" onclick="submitForceDeleteForm('{{ $modalId }}')"
                            class="px-6 py-3 text-base font-bold text-white bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-200 focus:ring-offset-2 btn-soft-transition">
                            <i class="fas fa-trash-alt mr-2"></i>
                            ELIMINAR PERMANENTEMENTE
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openModal(modalId) {
    openForceDeleteModal(modalId);
}

function closeModal(modalId) {
    closeForceDeleteModal(modalId);
}

function openForceDeleteModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
    document.getElementById(modalId).classList.add('flex');
    // Limpiar campos al abrir
    document.getElementById('motivo-' + modalId).value = '';
    document.getElementById('password-' + modalId).value = '';
    // Enfocar primer campo
    setTimeout(() => {
        document.getElementById('motivo-' + modalId).focus();
    }, 100);
}

function closeForceDeleteModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.getElementById(modalId).classList.remove('flex');
    // Limpiar campos al cerrar
    document.getElementById('motivo-' + modalId).value = '';
    document.getElementById('password-' + modalId).value = '';
}


function submitForceDeleteForm(modalId) {
    const motivoTextarea = document.getElementById('motivo-' + modalId);
    const passwordField = document.getElementById('password-' + modalId);
    const motivoInput = document.getElementById('motivo-input-' + modalId);
    const passwordInput = document.getElementById('password-input-' + modalId);
    const form = document.getElementById('force-delete-form-' + modalId);
    
    let hasErrors = false;
    
    // Validar motivo
    if (!motivoTextarea.value.trim()) {
        motivoTextarea.focus();
        motivoTextarea.classList.add('border-red-500', 'ring-red-200');
        setTimeout(() => {
            motivoTextarea.classList.remove('border-red-500', 'ring-red-200');
        }, 3000);
        hasErrors = true;
    } else if (motivoTextarea.value.trim().length < 10) {
        motivoTextarea.focus();
        motivoTextarea.classList.add('border-red-500', 'ring-red-200');
        setTimeout(() => {
            motivoTextarea.classList.remove('border-red-500', 'ring-red-200');
        }, 3000);
        hasErrors = true;
    }
    
    // Validar contraseña
    if (!passwordField.value.trim()) {
        if (!hasErrors) {
            passwordField.focus();
        }
        passwordField.classList.add('border-red-500', 'ring-red-200');
        setTimeout(() => {
            passwordField.classList.remove('border-red-500', 'ring-red-200');
        }, 3000);
        hasErrors = true;
    }
    
    if (hasErrors) {
        return false;
    }
    
    motivoInput.value = motivoTextarea.value;
    passwordInput.value = passwordField.value;
    form.submit();
}

// Solo registrar event listeners una vez
if (!window.forceDeleteEventListenersRegistered) {
    window.forceDeleteEventListenersRegistered = true;
    
    // Cerrar modal al hacer clic fuera (pero no en el contenido del modal)
    document.addEventListener('click', function(event) {
        const modals = document.querySelectorAll('[id*="force-delete"][id$="-modal"]');
        modals.forEach(modal => {
            if (!modal.classList.contains('hidden')) {
                // Solo cerrar si se hace clic exactamente en el overlay
                if (event.target === modal) {
                    closeForceDeleteModal(modal.id);
                }
            }
        });
    });
    
    // Cerrar modal con Escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const forceDeleteModals = document.querySelectorAll('[id*="force-delete"][id$="-modal"]');
            forceDeleteModals.forEach(modal => {
                if (!modal.classList.contains('hidden')) {
                    closeForceDeleteModal(modal.id);
                }
            });
        }
    });
}
</script>

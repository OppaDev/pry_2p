<!-- Modal de Confirmación de Eliminación con Motivo -->
<div id="{{ $modalId }}" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 p-4">
    <div class="relative w-full max-w-md mx-auto bg-white rounded-2xl shadow-2xl modal-content">
        <!-- Header del modal -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-2xl text-red-500"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
                </div>
            </div>
            <button type="button" onclick="closeModal('{{ $modalId }}')" 
                    class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Contenido del modal -->
        <div class="p-6">
            <div class="mb-6">
                <p class="text-gray-700 mb-3">{{ $message }}</p>
                
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-red-600 mt-1 mr-2"></i>
                        <div>
                            <p class="font-semibold text-red-800">{{ $itemName }}</p>
                            <p class="text-sm text-red-600 mt-1">{{ $itemDetails }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario con motivo -->
            <form id="delete-form-{{ $modalId }}" action="{{ $deleteRoute }}" method="POST" class="space-y-4">
                @csrf
                @method('DELETE')
                
                <!-- Campo de motivo -->
                <div class="mb-4">
                    <label for="motivo-{{ $modalId }}" class="block text-sm font-bold text-slate-700 mb-2">
                        <i class="fas fa-clipboard-list mr-1 text-slate-500"></i>
                        Motivo de la eliminación <span class="text-red-500">*</span>
                    </label>
                    <textarea id="motivo-{{ $modalId }}" name="motivo" rows="3"
                              class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-red-200 focus:border-red-400 transition-all duration-200"
                              placeholder="Describa el motivo de la eliminación..."
                              required minlength="10"></textarea>
                    <p class="text-xs text-slate-500 mt-1">Este motivo quedará registrado en el historial de auditoría (mínimo 10 caracteres)</p>
                </div>

                <!-- Campos ocultos para transferir datos -->
                <input type="hidden" id="motivo-input-{{ $modalId }}" name="motivo_hidden" value="">
            </form>
        </div>

        <!-- Footer con botones -->
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
            <button type="button" onclick="closeModal('{{ $modalId }}')"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 transition-all duration-200">
                <i class="fas fa-times mr-2"></i>
                Cancelar
            </button>
            <button type="button" onclick="submitDeleteFormWithMotivo('{{ $modalId }}')"
                    class="px-6 py-2 text-sm font-bold text-white bg-red-600 border border-transparent rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200">
                <i class="fas fa-trash mr-2"></i>
                {{ $confirmText }}
            </button>
        </div>
    </div>
</div>

<script>
    function submitDeleteFormWithMotivo(modalId) {
        const motivoField = document.getElementById(`motivo-${modalId}`);
        const motivo = motivoField.value.trim();
        
        // Validar que el motivo tenga al menos 10 caracteres
        if (motivo.length < 10) {
            motivoField.focus();
            motivoField.classList.add('border-red-500', 'ring-red-200');
            
            // Crear o actualizar mensaje de error
            let errorMsg = document.getElementById(`error-${modalId}`);
            if (!errorMsg) {
                errorMsg = document.createElement('p');
                errorMsg.id = `error-${modalId}`;
                errorMsg.className = 'text-xs text-red-500 mt-1';
                motivoField.parentNode.appendChild(errorMsg);
            }
            errorMsg.textContent = 'El motivo debe tener al menos 10 caracteres.';
            
            setTimeout(() => {
                motivoField.classList.remove('border-red-500', 'ring-red-200');
                if (errorMsg) errorMsg.remove();
            }, 3000);
            
            return false;
        }
        
        // Transferir el motivo al campo oculto y enviar el formulario
        document.getElementById(`motivo-input-${modalId}`).value = motivo;
        document.getElementById(`delete-form-${modalId}`).submit();
    }
</script>

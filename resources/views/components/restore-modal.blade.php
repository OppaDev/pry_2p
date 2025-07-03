<!-- Modal de Confirmación de Restauración -->
<div id="{{ $modalId }}" class="fixed inset-0 z-[9999] hidden overflow-y-auto modal-backdrop flex items-center justify-center">
    <div class="w-full max-w-md mx-auto p-4">
        <!-- Modal Container -->
        <div class="relative bg-white rounded-2xl shadow-2xl transform transition-all duration-300 ease-soft-spring modal-content" 
             onclick="event.stopPropagation()"
             style="box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(255, 255, 255, 0.1);">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 pb-4">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center justify-center w-14 h-14 rounded-2xl" 
                         style="background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); box-shadow: 0 4px 14px 0 rgba(34, 197, 94, 0.2);">
                        <i class="fas fa-undo text-green-500 text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-800 leading-tight">{{ $title }}</h3>
                        <p class="text-sm text-slate-500 mt-1">El elemento volverá a estar disponible</p>
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
                     style="background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); border: 1px solid #86efac;">
                    <p class="text-slate-700 text-base leading-relaxed font-medium">
                        {{ $message }}
                    </p>
                    @if(isset($itemName))
                        <div class="mt-4 p-4 rounded-lg" 
                             style="background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%); border: 1px solid #86efac; box-shadow: inset 0 1px 0 0 rgba(255, 255, 255, 0.1);">
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
                        <i class="fas fa-comment-alt mr-1 text-green-500"></i>
                        Motivo de la restauración <span class="text-red-500">*</span>
                    </label>
                    <textarea id="motivo-{{ $modalId }}" name="motivo" 
                              class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-green-200 focus:border-green-400 resize-none transition-all duration-200"
                              rows="3" 
                              placeholder="Describe brevemente el motivo de esta restauración..."
                              required></textarea>
                    <p class="text-xs text-slate-500 mt-1">Este motivo quedará registrado en el historial de auditoría</p>
                </div>
                
                <!-- Campo de contraseña -->
                <div class="mb-4">
                    <label for="password-{{ $modalId }}" class="block text-sm font-bold text-slate-700 mb-2">
                        <i class="fas fa-lock mr-1 text-green-500"></i>
                        Confirma tu contraseña <span class="text-red-500">*</span>
                    </label>
                    <input type="password" id="password-{{ $modalId }}" name="password" 
                           class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-green-200 focus:border-green-400 transition-all duration-200"
                           placeholder="Ingresa tu contraseña actual..."
                           required autocomplete="current-password">
                    <p class="text-xs text-slate-500 mt-1">Requerido por seguridad para confirmar la restauración</p>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="flex items-center justify-end space-x-3 p-6 pt-4 bg-gradient-to-r from-slate-50 to-slate-100 rounded-b-2xl border-t border-slate-200/50">
                <button type="button" onclick="closeModal('{{ $modalId }}')"
                    class="px-6 py-3 text-base font-semibold text-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-slate-200 focus:border-slate-400 btn-soft-transition"
                    style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border: 1px solid #e2e8f0; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);">
                    <i class="fas fa-times mr-2"></i>
                    Cancelar
                </button>
                <form method="POST" action="{{ $restoreRoute }}" class="inline-block" id="restore-form-{{ $modalId }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="motivo" id="motivo-input-{{ $modalId }}">
                    <input type="hidden" name="password" id="password-input-{{ $modalId }}">
                    <button type="button" onclick="submitRestoreForm('{{ $modalId }}')"
                        class="px-6 py-3 text-base font-bold text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-green-200 focus:ring-offset-2 btn-soft-transition"
                        style="background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); box-shadow: 0 4px 14px 0 rgba(34, 197, 94, 0.39), 0 2px 4px 0 rgba(34, 197, 94, 0.1);">
                        <i class="fas fa-undo mr-2"></i>
                        {{ $confirmText ?? 'Restaurar' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

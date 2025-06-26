<!-- Modal de Confirmación de Eliminación Permanente -->
<div id="{{ $modalId }}" class="fixed inset-0 z-50 hidden overflow-y-auto modal-backdrop">
    <div class="flex items-center justify-center min-h-screen px-4 py-8 modal-container">
        <div class="relative w-full max-w-md mx-auto">
            <!-- Modal Container -->
            <div class="relative bg-white rounded-2xl shadow-2xl transform transition-all duration-300 ease-soft-spring modal-content" 
                 style="box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(255, 255, 255, 0.1);">
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-6 pb-4">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center justify-center w-14 h-14 rounded-2xl" 
                             style="background: linear-gradient(135deg, #fef2f2 0%, #fecaca 100%); box-shadow: 0 4px 14px 0 rgba(239, 68, 68, 0.3);">
                            <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-800 leading-tight">{{ $title }}</h3>
                            <p class="text-sm text-red-600 mt-1 font-semibold">¡Esta acción es IRREVERSIBLE!</p>
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
                         style="background: linear-gradient(135deg, #fef2f2 0%, #fecaca 100%); border: 1px solid #fca5a5;">
                        <div class="flex items-start space-x-3 mb-4">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-skull-crossbones text-red-600 text-sm"></i>
                                </div>
                            </div>
                            <div>
                                <p class="text-red-800 text-base leading-relaxed font-bold">
                                    ¡ELIMINACIÓN PERMANENTE!
                                </p>
                                <p class="text-red-700 text-sm leading-relaxed mt-1">
                                    {{ $message }}
                                </p>
                            </div>
                        </div>
                        
                        @if(isset($itemName))
                            <div class="mt-4 p-4 rounded-lg border-2 border-red-300" 
                                 style="background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%); box-shadow: inset 0 1px 0 0 rgba(255, 255, 255, 0.1);">
                                <p class="font-bold text-slate-800 text-lg flex items-center">
                                    <i class="fas fa-times-circle text-red-500 mr-2"></i>
                                    {{ $itemName }}
                                </p>
                                @if(isset($itemDetails))
                                    <p class="text-slate-600 text-sm mt-2">{{ $itemDetails }}</p>
                                @endif
                            </div>
                        @endif
                        
                        <div class="mt-4 p-3 bg-red-100 border border-red-300 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-info-circle text-red-600 mr-2"></i>
                                <p class="text-red-800 text-sm font-medium">
                                    Una vez eliminado permanentemente, NO podrás recuperar este elemento.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="flex items-center justify-end space-x-3 p-6 pt-4 bg-gradient-to-r from-slate-50 to-slate-100 rounded-b-2xl border-t border-slate-200/50">
                    <button type="button" onclick="closeModal('{{ $modalId }}')"
                        class="px-6 py-3 text-base font-semibold text-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-slate-200 focus:border-slate-400 btn-soft-transition"
                        style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border: 1px solid #e2e8f0; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);">
                        <i class="fas fa-shield-alt mr-2"></i>
                        Cancelar (Recomendado)
                    </button>
                    <form method="POST" action="{{ $deleteRoute }}" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-6 py-3 text-base font-bold text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-200 focus:ring-offset-2 btn-soft-transition"
                            style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); box-shadow: 0 4px 14px 0 rgba(220, 38, 38, 0.5), 0 2px 4px 0 rgba(220, 38, 38, 0.2);"
                            onclick="return confirm('¿ESTÁS COMPLETAMENTE SEGURO? Esta acción NO se puede deshacer.')">
                            <i class="fas fa-skull-crossbones mr-2"></i>
                            {{ $confirmText ?? 'Eliminar Permanentemente' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

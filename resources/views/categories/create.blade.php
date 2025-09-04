@extends('layouts.app')

@section('title', 'Nova Categoria')

@section('header')
<div class="md:d-flex md:align-items-center md:justify-content-between">
    <div class="min-w-0 d-flex-grow-1">
        <div class="d-flex align-items-center space-x-3">
            <a href="{{ route('categories.index') }}" class="text-muted hover:text-muted">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="fs-2 fw-bold leading-7 text-dark sm:truncate sm:fs-1 sm:tracking-tight">
                Nova Categoria
            </h2>
        </div>
        <p class="mt-1 fs-6 text-muted">
            Crie uma nova categoria para organizar tickets
        </p>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <form action="{{ route('categories.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Informações da Categoria -->
        <div class="bg-white shadow-sm rounded">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="fs-4 fw-medium text-dark mb-4">Informações da Categoria</h3>
                
                <div class="space-y-6">
                    <!-- Nome -->
                    <div>
                        <label for="name" class="block fs-6 fw-medium text-dark">Nome da Categoria *</label>
                        <div class="mt-1">
                            <input type="text" name="name" id="name" required
                                class="block w-full rounded border-light shadow-sm-sm focus:border-brand-500 focus:ring-brand-500 sm:fs-6 @error('name') border-red-500 @enderror"
                                value="{{ old('name') }}"
                                placeholder="Ex: Suporte Técnico, Infraestrutura, Software...">
                        </div>
                        @error('name')
                            <p class="mt-2 fs-6 text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Descrição -->
                    <div>
                        <label for="description" class="block fs-6 fw-medium text-dark">Descrição</label>
                        <div class="mt-1">
                            <textarea name="description" id="description" rows="3"
                                class="block w-full rounded border-light shadow-sm-sm focus:border-brand-500 focus:ring-brand-500 sm:fs-6 @error('description') border-red-500 @enderror"
                                placeholder="Descreva brevemente o que esta categoria representa...">{{ old('description') }}</textarea>
                        </div>
                        @error('description')
                            <p class="mt-2 fs-6 text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cor da Categoria -->
                    <div>
                        <label class="block fs-6 fw-medium text-dark mb-3">Cor da Categoria *</label>
                        
                        <!-- Cores Predefinidas -->
                        <div class="grid grid-cols-6 gap-3 mb-4">
                            <button type="button" class="color-option w-12 h-12 rounded border-2 border-light hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2" 
                                data-color="#3B82F6" style="background-color: #3B82F6;" title="Azul"></button>
                            <button type="button" class="color-option w-12 h-12 rounded border-2 border-light hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2" 
                                data-color="#10B981" style="background-color: #10B981;" title="Verde"></button>
                            <button type="button" class="color-option w-12 h-12 rounded border-2 border-light hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2" 
                                data-color="#F59E0B" style="background-color: #F59E0B;" title="Amarelo"></button>
                            <button type="button" class="color-option w-12 h-12 rounded border-2 border-light hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2" 
                                data-color="#EF4444" style="background-color: #EF4444;" title="Vermelho"></button>
                            <button type="button" class="color-option w-12 h-12 rounded border-2 border-light hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2" 
                                data-color="#8B5CF6" style="background-color: #8B5CF6;" title="Roxo"></button>
                            <button type="button" class="color-option w-12 h-12 rounded border-2 border-light hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2" 
                                data-color="#F97316" style="background-color: #F97316;" title="Laranja"></button>
                        </div>

                        <!-- Cores Adicionais -->
                        <div class="grid grid-cols-6 gap-3 mb-4">
                            <button type="button" class="color-option w-12 h-12 rounded border-2 border-light hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2" 
                                data-color="#06B6D4" style="background-color: #06B6D4;" title="Ciano"></button>
                            <button type="button" class="color-option w-12 h-12 rounded border-2 border-light hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2" 
                                data-color="#84CC16" style="background-color: #84CC16;" title="Lima"></button>
                            <button type="button" class="color-option w-12 h-12 rounded border-2 border-light hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2" 
                                data-color="#EC4899" style="background-color: #EC4899;" title="Rosa"></button>
                            <button type="button" class="color-option w-12 h-12 rounded border-2 border-light hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2" 
                                data-color="#6B7280" style="background-color: #6B7280;" title="Cinza"></button>
                            <button type="button" class="color-option w-12 h-12 rounded border-2 border-light hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2" 
                                data-color="#059669" style="background-color: #059669;" title="Esmeralda"></button>
                            <button type="button" class="color-option w-12 h-12 rounded border-2 border-light hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2" 
                                data-color="#DC2626" style="background-color: #DC2626;" title="Vermelho Escuro"></button>
                        </div>

                        <!-- Seletor de Cor Personalizada -->
                        <div class="d-flex align-items-center space-x-3">
                            <label for="custom-color" class="fs-6 fw-medium text-dark">Cor personalizada:</label>
                            <input type="color" id="custom-color" name="custom_color" 
                                class="h-10 w-20 rounded border border-light cursor-pointer"
                                value="{{ old('color', '#3B82F6') }}">
                            <input type="text" id="color" name="color" required
                                class="block w-32 rounded border-light shadow-sm-sm sm:fs-6 @error('color') border-red-500 @enderror"
                                value="{{ old('color', '#3B82F6') }}"
                                placeholder="#000000">
                        </div>
                        
                        @error('color')
                            <p class="mt-2 fs-6 text-red-600">{{ $message }}</p>
                        @enderror

                        <!-- Preview da Cor Selecionada -->
                        <div class="mt-4 p-4 rounded border border-light">
                            <div class="d-flex align-items-center space-x-3">
                                <div id="color-preview" class="w-8 h-8 rounded border border-light" style="background-color: {{ old('color', '#3B82F6') }};"></div>
                                <div>
                                    <p class="fs-6 fw-medium text-dark">Preview da Cor</p>
                                    <p class="fs-6 text-muted">Esta cor será usada para identificar visualmente a categoria</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <div class="d-flex align-items-center">
                            <input type="checkbox" id="is_active" name="is_active" value="1" 
                                class="h-4 w-4 text-primary focus:ring-brand-500 border-light rounded @error('is_active') border-red-500 @enderror"
                                {{ old('is_active', true) ? 'checked' : '' }}>
                            <label for="is_active" class="ml-2 block fs-6 text-dark">
                                Categoria ativa
                            </label>
                        </div>
                        @error('is_active')
                            <p class="mt-2 fs-6 text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Botões de Ação -->
        <div class="d-flex justify-content-end space-x-3">
            <a href="{{ route('categories.index') }}" 
                class="inline-d-flex align-items-center px-4 py-2 border border-light shadow-sm-sm fs-6 fw-medium rounded text-dark bg-white hover:bg-light focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Cancelar
            </a>
            
            <button type="submit" 
                class="inline-d-flex align-items-center px-4 py-2 border border-transparent shadow-sm-sm fs-6 fw-medium rounded text-white bg-primary hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Criar Categoria
            </button>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
    let selectedColor = '{{ old('color', '#3B82F6') }}';

    // Seleção de cores predefinidas
    document.querySelectorAll('.color-option').forEach(button => {
        button.addEventListener('click', function() {
            const color = this.dataset.color;
            selectColor(color);
        });
    });

    // Seletor de cor personalizada
    document.getElementById('custom-color').addEventListener('change', function() {
        const color = this.value;
        selectColor(color);
    });
    
    // Sincronizar campo de texto com seletor de cor
    document.getElementById('color').addEventListener('input', function() {
        const color = this.value;
        if (color.match(/^#[0-9A-F]{6}$/i)) {
            selectColor(color);
            updateColorPreview();
        }
    });

    function selectColor(color) {
        console.log('Selecionando cor:', color); // Debug
        selectedColor = color;
        
        // Atualizar input de cor (campo de texto) - AGORA FUNCIONA!
        document.getElementById('color').value = color;
        
        // Atualizar seletor de cor personalizada
        document.getElementById('custom-color').value = color;
        
        // Remover seleção anterior de todos os botões
        document.querySelectorAll('.color-option').forEach(btn => {
            btn.classList.remove('border-brand-500', 'ring-2', 'ring-brand-500');
            btn.classList.add('border-gray-300');
        });
        
        // Marcar cor selecionada (se for uma das predefinidas)
        const selectedButton = document.querySelector(`[data-color="${color}"]`);
        if (selectedButton) {
            selectedButton.classList.remove('border-gray-300');
            selectedButton.classList.add('border-brand-500', 'ring-2', 'ring-brand-500');
        }
        
        // Atualizar preview imediatamente
        updateColorPreview();
        
        // Debug: verificar se foi atualizado
        console.log('Campo color atualizado para:', document.getElementById('color').value);
        console.log('Preview atualizado para:', selectedColor);
    }

    function updateColorPreview() {
        console.log('Atualizando preview para:', selectedColor); // Debug
        document.getElementById('color-preview').style.backgroundColor = selectedColor;
        
        // Verificar se foi aplicado
        const preview = document.getElementById('color-preview');
        console.log('Preview atualizado:', preview.style.backgroundColor);
    }

    // Validação do formulário
    document.querySelector('form').addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        const color = document.getElementById('color').value.trim();
        
        if (!name || !color) {
            e.preventDefault();
            alert('Por favor, preencha todos os campos obrigatórios.');
            return;
        }
        
        // Validar formato da cor
        const colorRegex = /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/;
        if (!colorRegex.test(color)) {
            e.preventDefault();
            alert('Por favor, selecione uma cor válida.');
            return;
        }
    });

    // Inicializar com cor padrão selecionada
    document.addEventListener('DOMContentLoaded', function() {
        // Selecionar cor padrão
        selectColor(selectedColor);
        
        // Marcar botão da cor padrão como selecionado
        const defaultButton = document.querySelector(`[data-color="${selectedColor}"]`);
        if (defaultButton) {
            defaultButton.classList.remove('border-gray-300');
            defaultButton.classList.add('border-brand-500', 'ring-2', 'ring-brand-500');
        }
    });
</script>
@endpush

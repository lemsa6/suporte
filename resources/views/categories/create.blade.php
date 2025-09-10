@extends('layouts.app')

@section('title', 'Nova Categoria')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <div class="flex items-center mb-2">
            <x-button variant="outline" size="sm" tag="a" href="{{ route('categories.index') }}" class="mr-3">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Voltar
            </x-button>
            <h1 class="page-title mb-0">Nova Categoria</h1>
        </div>
        <p class="text-cinza mt-2">Crie uma nova categoria para organizar tickets</p>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <form action="{{ route('categories.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Informações da Categoria -->
        <h2 class="section-title mb-4">Informações da Categoria</h2>
        <x-card>
            <div class="space-y-4">
                    <!-- Nome -->
                    <x-input
                        name="name"
                        label="Nome da Categoria"
                        placeholder="Ex: Suporte Técnico, Infraestrutura, Software..."
                        required
                        :value="old('name')"
                    />

                    <!-- Descrição -->
                    <x-textarea
                        name="description"
                        label="Descrição"
                        placeholder="Descreva brevemente o que esta categoria representa..."
                        rows="3"
                        :value="old('description')"
                    />

                    <!-- Cor da Categoria -->
                    <div>
                        <label class="block text-sm font-medium text-cinza mb-3">Cor da Categoria *</label>
                        
                        <!-- Cores Predefinidas -->
                        <div class="grid grid-cols-6 gap-2 mb-3">
                            <button type="button" class="color-option w-full h-12 rounded border-2 border-cinza-claro-2 hover:border-cinza-claro focus:outline-none focus:ring-2 focus:ring-roxo focus:ring-offset-2" 
                                data-color="#3B82F6" style="background-color: #3B82F6;" title="Azul"></button>
                            <button type="button" class="color-option w-full h-12 rounded border-2 border-cinza-claro-2 hover:border-cinza-claro focus:outline-none focus:ring-2 focus:ring-roxo focus:ring-offset-2" 
                                data-color="#10B981" style="background-color: #10B981;" title="Verde"></button>
                            <button type="button" class="color-option w-full h-12 rounded border-2 border-cinza-claro-2 hover:border-cinza-claro focus:outline-none focus:ring-2 focus:ring-roxo focus:ring-offset-2" 
                                data-color="#F59E0B" style="background-color: #F59E0B;" title="Amarelo"></button>
                            <button type="button" class="color-option w-full h-12 rounded border-2 border-cinza-claro-2 hover:border-cinza-claro focus:outline-none focus:ring-2 focus:ring-roxo focus:ring-offset-2" 
                                data-color="#EF4444" style="background-color: #EF4444;" title="Vermelho"></button>
                            <button type="button" class="color-option w-full h-12 rounded border-2 border-cinza-claro-2 hover:border-cinza-claro focus:outline-none focus:ring-2 focus:ring-roxo focus:ring-offset-2" 
                                data-color="#8B5CF6" style="background-color: #8B5CF6;" title="Roxo"></button>
                            <button type="button" class="color-option w-full h-12 rounded border-2 border-cinza-claro-2 hover:border-cinza-claro focus:outline-none focus:ring-2 focus:ring-roxo focus:ring-offset-2" 
                                data-color="#F97316" style="background-color: #F97316;" title="Laranja"></button>
                        </div>

                        <!-- Cores Adicionais -->
                        <div class="grid grid-cols-6 gap-2 mb-3">
                            <button type="button" class="color-option w-full h-12 rounded border-2 border-cinza-claro-2 hover:border-cinza-claro focus:outline-none focus:ring-2 focus:ring-roxo focus:ring-offset-2" 
                                data-color="#06B6D4" style="background-color: #06B6D4;" title="Ciano"></button>
                            <button type="button" class="color-option w-full h-12 rounded border-2 border-cinza-claro-2 hover:border-cinza-claro focus:outline-none focus:ring-2 focus:ring-roxo focus:ring-offset-2" 
                                data-color="#84CC16" style="background-color: #84CC16;" title="Lima"></button>
                            <button type="button" class="color-option w-full h-12 rounded border-2 border-cinza-claro-2 hover:border-cinza-claro focus:outline-none focus:ring-2 focus:ring-roxo focus:ring-offset-2" 
                                data-color="#EC4899" style="background-color: #EC4899;" title="Rosa"></button>
                            <button type="button" class="color-option w-full h-12 rounded border-2 border-cinza-claro-2 hover:border-cinza-claro focus:outline-none focus:ring-2 focus:ring-roxo focus:ring-offset-2" 
                                data-color="#6B7280" style="background-color: #6B7280;" title="Cinza"></button>
                            <button type="button" class="color-option w-full h-12 rounded border-2 border-cinza-claro-2 hover:border-cinza-claro focus:outline-none focus:ring-2 focus:ring-roxo focus:ring-offset-2" 
                                data-color="#059669" style="background-color: #059669;" title="Esmeralda"></button>
                            <button type="button" class="color-option w-full h-12 rounded border-2 border-cinza-claro-2 hover:border-cinza-claro focus:outline-none focus:ring-2 focus:ring-roxo focus:ring-offset-2" 
                                data-color="#DC2626" style="background-color: #DC2626;" title="Vermelho Escuro"></button>
                        </div>

                        <!-- Seletor de Cor Personalizada -->
                        <div class="flex items-center gap-3">
                            <label for="custom-color" class="block text-sm font-medium text-cinza mb-0">Cor personalizada:</label>
                            <input type="color" id="custom-color" name="custom_color" 
                                class="w-12 h-12 border border-cinza-claro-2 rounded-md cursor-pointer"
                                value="{{ old('color', '#3B82F6') }}">
                            <input type="text" id="color" name="color" required
                                class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent @error('color') border-vermelho @enderror"
                                value="{{ old('color', '#3B82F6') }}"
                                placeholder="#000000">
                        </div>
                        
                        @error('color')
                            <div class="text-vermelho text-sm mt-1">{{ $message }}</div>
                        @enderror

                        <!-- Preview da Cor Selecionada -->
                        <div class="mt-3 p-3 rounded border bg-cinza-claro-2">
                            <div class="flex items-center gap-3">
                                <div id="color-preview" class="w-8 h-8 rounded border category-color-badge" style="--category-color: {{ old('color', '#3B82F6') }};"></div>
                                <div>
                                    <p class="mb-0 font-medium text-cinza">Preview da Cor</p>
                                    <p class="mb-0 text-sm text-cinza-claro">Esta cor será usada para identificar visualmente a categoria</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <div class="form-check">
                            <input type="checkbox" id="is_active" name="is_active" value="1" 
                                class="form-check-input @error('is_active') is-invalid @enderror"
                                {{ old('is_active', true) ? 'checked' : '' }}>
                            <label for="is_active" class="font-medium text-cinza">
                                Categoria ativa
                            </label>
                        </div>
                        @error('is_active')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
            </div>
        </x-card>

        <!-- Botões de Ação -->
        <div class="flex justify-end gap-3">
            <x-button variant="outline" tag="a" href="{{ route('categories.index') }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Cancelar
            </x-button>
            
            <x-button variant="primary" type="submit">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Criar Categoria
            </x-button>
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
        selectedColor = color;
        
        // Atualizar input de cor (campo de texto)
        document.getElementById('color').value = color;
        
        // Atualizar seletor de cor personalizada
        document.getElementById('custom-color').value = color;
        
        // Remover seleção anterior de todos os botões
        document.querySelectorAll('.color-option').forEach(btn => {
            btn.classList.remove('border-roxo', 'ring-2', 'ring-roxo');
            btn.classList.add('border-cinza-claro-2');
        });
        
        // Marcar cor selecionada (se for uma das predefinidas)
        const selectedButton = document.querySelector(`[data-color="${color}"]`);
        if (selectedButton) {
            selectedButton.classList.remove('border-cinza-claro-2');
            selectedButton.classList.add('border-primary', 'ring-2', 'ring-primary');
        }
        
        // Atualizar preview
        updateColorPreview();
    }

    function updateColorPreview() {
        document.getElementById('color-preview').style.backgroundColor = selectedColor;
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
            defaultButton.classList.remove('border-cinza-claro-2');
            defaultButton.classList.add('border-primary', 'ring-2', 'ring-primary');
        }
    });
</script>
@endpush
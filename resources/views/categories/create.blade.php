@extends('layouts.app')

@section('title', 'Nova Categoria')

@section('header')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
    <div class="flex-grow-1">
        <div class="d-flex align-items-center mb-2">
            <x-button variant="outline" size="sm" tag="a" href="{{ route('categories.index') }}" class="me-3">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Voltar
            </x-button>
            <h2 class="fs-2 fw-bold text-dark mb-0">
                Nova Categoria
            </h2>
        </div>
        <p class="text-muted">
            Crie uma nova categoria para organizar tickets
        </p>
    </div>
</div>
@endsection

@section('content')
<div class="d-flex flex-column gap-4">
    <form action="{{ route('categories.store') }}" method="POST" class="d-flex flex-column gap-4">
        @csrf
        
        <!-- Informações da Categoria -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0 fw-semibold">Informações da Categoria</h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-column gap-4">
                    <!-- Nome -->
                    <div>
                        <label for="name" class="form-label fw-medium text-dark">Nome da Categoria *</label>
                        <input type="text" name="name" id="name" required
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}"
                            placeholder="Ex: Suporte Técnico, Infraestrutura, Software...">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Descrição -->
                    <div>
                        <label for="description" class="form-label fw-medium text-dark">Descrição</label>
                        <textarea name="description" id="description" rows="3"
                            class="form-control @error('description') is-invalid @enderror"
                            placeholder="Descreva brevemente o que esta categoria representa...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Cor da Categoria -->
                    <div>
                        <label class="form-label fw-medium text-dark mb-3">Cor da Categoria *</label>
                        
                        <!-- Cores Predefinidas -->
                        <div class="row g-2 mb-3">
                            <div class="col-2 col-sm-1">
                                <button type="button" class="color-option w-100 h-12 rounded border-2 border-light hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2" 
                                    data-color="#3B82F6" class="color-blue" title="Azul"></button>
                            </div>
                            <div class="col-2 col-sm-1">
                                <button type="button" class="color-option w-100 h-12 rounded border-2 border-light hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2" 
                                    data-color="#10B981" class="color-green" title="Verde"></button>
                            </div>
                            <div class="col-2 col-sm-1">
                                <button type="button" class="color-option w-100 h-12 rounded border-2 border-light hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2" 
                                    data-color="#F59E0B" class="color-yellow" title="Amarelo"></button>
                            </div>
                            <div class="col-2 col-sm-1">
                                <button type="button" class="color-option w-100 h-12 rounded border-2 border-light hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2" 
                                    data-color="#EF4444" class="color-red" title="Vermelho"></button>
                            </div>
                            <div class="col-2 col-sm-1">
                                <button type="button" class="color-option w-100 h-12 rounded border-2 border-light hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2" 
                                    data-color="#8B5CF6" class="color-purple" title="Roxo"></button>
                            </div>
                            <div class="col-2 col-sm-1">
                                <button type="button" class="color-option w-100 h-12 rounded border-2 border-light hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2" 
                                    data-color="#F97316" class="color-orange" title="Laranja"></button>
                            </div>
                        </div>

                        <!-- Cores Adicionais -->
                        <div class="row g-2 mb-3">
                            <div class="col-2 col-sm-1">
                                <button type="button" class="color-option w-100 h-12 rounded border-2 border-light hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2" 
                                    data-color="#06B6D4" class="color-cyan" title="Ciano"></button>
                            </div>
                            <div class="col-2 col-sm-1">
                                <button type="button" class="color-option w-100 h-12 rounded border-2 border-light hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2" 
                                    data-color="#84CC16" class="color-lime" title="Lima"></button>
                            </div>
                            <div class="col-2 col-sm-1">
                                <button type="button" class="color-option w-100 h-12 rounded border-2 border-light hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2" 
                                    data-color="#EC4899" class="color-pink" title="Rosa"></button>
                            </div>
                            <div class="col-2 col-sm-1">
                                <button type="button" class="color-option w-100 h-12 rounded border-2 border-light hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2" 
                                    data-color="#6B7280" class="color-gray" title="Cinza"></button>
                            </div>
                            <div class="col-2 col-sm-1">
                                <button type="button" class="color-option w-100 h-12 rounded border-2 border-light hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2" 
                                    data-color="#059669" class="color-green" title="Esmeralda"></button>
                            </div>
                            <div class="col-2 col-sm-1">
                                <button type="button" class="color-option w-100 h-12 rounded border-2 border-light hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2" 
                                    data-color="#DC2626" class="color-red" title="Vermelho Escuro"></button>
                            </div>
                        </div>

                        <!-- Seletor de Cor Personalizada -->
                        <div class="d-flex align-items-center gap-3">
                            <label for="custom-color" class="form-label fw-medium text-dark mb-0">Cor personalizada:</label>
                            <input type="color" id="custom-color" name="custom_color" 
                                class="form-control form-control-color"
                                value="{{ old('color', '#3B82F6') }}">
                            <input type="text" id="color" name="color" required
                                class="form-control @error('color') is-invalid @enderror"
                                value="{{ old('color', '#3B82F6') }}"
                                placeholder="#000000">
                        </div>
                        
                        @error('color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <!-- Preview da Cor Selecionada -->
                        <div class="mt-3 p-3 rounded border bg-light">
                            <div class="d-flex align-items-center gap-3">
                                <div id="color-preview" class="w-8 h-8 rounded border category-color-badge" style="--category-color: {{ old('color', '#3B82F6') }};"></div>
                                <div>
                                    <p class="mb-0 fw-medium text-dark">Preview da Cor</p>
                                    <p class="mb-0 small text-muted">Esta cor será usada para identificar visualmente a categoria</p>
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
                            <label for="is_active" class="form-check-label fw-medium text-dark">
                                Categoria ativa
                            </label>
                        </div>
                        @error('is_active')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Botões de Ação -->
        <div class="d-flex justify-content-end gap-2">
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
            btn.classList.remove('border-primary', 'ring-2', 'ring-primary');
            btn.classList.add('border-light');
        });
        
        // Marcar cor selecionada (se for uma das predefinidas)
        const selectedButton = document.querySelector(`[data-color="${color}"]`);
        if (selectedButton) {
            selectedButton.classList.remove('border-light');
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
            defaultButton.classList.remove('border-light');
            defaultButton.classList.add('border-primary', 'ring-2', 'ring-primary');
        }
    });
</script>
@endpush
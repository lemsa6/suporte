@extends('layouts.app')

@section('title', 'Editar Categoria')

@section('header')
<div class="d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center">
        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary btn-sm me-3">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Voltar
        </a>
        <div>
            <h2 class="h4 mb-0 fw-bold text-dark">
                Editar Categoria
            </h2>
            <p class="text-muted mb-0">
                Edite as informações da categoria "{{ $category->name }}"
            </p>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <form action="{{ route('categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Informações da Categoria -->
            <div class="card">
                <div class="card-header">
                    <h3 class="h5 mb-0 fw-medium text-dark">Informações da Categoria</h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Nome -->
                        <div class="col-12">
                            <label for="name" class="form-label fw-medium">Nome da Categoria *</label>
                            <input type="text" name="name" id="name" required
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $category->name) }}"
                                placeholder="Ex: Suporte Técnico, Infraestrutura, Software...">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Descrição -->
                        <div class="col-12">
                            <label for="description" class="form-label fw-medium">Descrição</label>
                            <textarea name="description" id="description" rows="3"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Descreva brevemente o que esta categoria representa...">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Cor da Categoria -->
                        <div class="col-12">
                            <label class="form-label fw-medium">Cor da Categoria *</label>
                            
                            <!-- Cores Predefinidas -->
                            <div class="row g-2 mb-3">
                                <div class="col-auto">
                                    <button type="button" class="color-picker-btn" 
                                        data-color="#3B82F6" style="background-color: #3B82F6;" title="Azul"></button>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="color-picker-btn" 
                                        data-color="#10B981" style="background-color: #10B981;" title="Verde"></button>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="color-picker-btn" 
                                        data-color="#F59E0B" style="background-color: #F59E0B;" title="Amarelo"></button>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="color-picker-btn" 
                                        data-color="#EF4444" style="background-color: #EF4444;" title="Vermelho"></button>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="color-picker-btn" 
                                        data-color="#8B5CF6" style="background-color: #8B5CF6;" title="Roxo"></button>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="color-picker-btn" 
                                        data-color="#F97316" style="background-color: #F97316;" title="Laranja"></button>
                                </div>
                            </div>

                            <!-- Cores Adicionais -->
                            <div class="row g-2 mb-3">
                                <div class="col-auto">
                                    <button type="button" class="color-picker-btn" 
                                        data-color="#06B6D4" style="background-color: #06B6D4;" title="Ciano"></button>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="color-picker-btn" 
                                        data-color="#84CC16" style="background-color: #84CC16;" title="Lima"></button>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="color-picker-btn" 
                                        data-color="#EC4899" style="background-color: #EC4899;" title="Rosa"></button>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="color-picker-btn" 
                                        data-color="#6B7280" style="background-color: #6B7280;" title="Cinza"></button>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="color-picker-btn" 
                                        data-color="#059669" style="background-color: #059669;" title="Esmeralda"></button>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="color-picker-btn" 
                                        data-color="#DC2626" style="background-color: #DC2626;" title="Vermelho Escuro"></button>
                                </div>
                            </div>

                            <!-- Seletor de Cor Personalizada -->
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <label for="custom-color" class="form-label mb-0 fw-medium">Cor personalizada:</label>
                                <input type="color" id="custom-color" name="custom_color" 
                                    class="form-control form-control-color"
                                    value="{{ old('color', $category->color) }}">
                                <input type="text" id="color" name="color" required
                                    class="form-control @error('color') is-invalid @enderror"
                                    value="{{ old('color', $category->color) }}"
                                    placeholder="#000000">
                            </div>
                            
                            @error('color')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                            <!-- Preview da Cor Selecionada -->
                            <div class="p-3 border rounded">
                                <div class="d-flex align-items-center gap-3">
                                    <div id="color-preview" class="color-preview" style="background-color: {{ old('color', $category->color) }};"></div>
                                    <div>
                                        <p class="mb-1 fw-medium text-dark">Preview da Cor</p>
                                        <p class="mb-0 text-muted small">Esta cor será usada para identificar visualmente a categoria</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" id="is_active" name="is_active" value="1" 
                                    class="form-check-input @error('is_active') is-invalid @enderror"
                                    {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                <label for="is_active" class="form-check-label fw-medium">
                                    Categoria ativa
                                </label>
                            </div>
                            @error('is_active')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botões de Ação -->
            <div class="d-flex justify-content-end gap-3 mt-4">
                <a href="{{ route('categories.index') }}" 
                    class="btn btn-outline-secondary">
                    <svg class="me-2" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancelar
                </a>
                
                <button type="submit" class="btn btn-primary">
                    <svg class="me-2" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Atualizar Categoria
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let selectedColor = '{{ old('color', $category->color) }}';

    // Seleção de cores predefinidas
    document.querySelectorAll('.color-picker-btn').forEach(button => {
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
        document.querySelectorAll('.color-picker-btn').forEach(btn => {
            btn.classList.remove('border-primary', 'border-3');
            btn.classList.add('border-secondary');
        });
        
        // Marcar cor selecionada (se for uma das predefinidas)
        const selectedButton = document.querySelector(`[data-color="${color}"]`);
        if (selectedButton) {
            selectedButton.classList.remove('border-secondary');
            selectedButton.classList.add('border-primary', 'border-3');
        }
        
        // Atualizar preview imediatamente
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

    // Inicializar com cor atual selecionada
    document.addEventListener('DOMContentLoaded', function() {
        // Selecionar cor atual
        selectColor(selectedColor);
        
        // Marcar botão da cor atual como selecionado
        const currentButton = document.querySelector(`[data-color="${selectedColor}"]`);
        if (currentButton) {
            currentButton.classList.remove('border-secondary');
            currentButton.classList.add('border-primary', 'border-3');
        }
    });
</script>
@endpush

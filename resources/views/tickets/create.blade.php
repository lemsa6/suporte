@extends('layouts.app')

@section('title', 'Novo Ticket')

@section('header')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
    <div class="flex-grow-1">
        <h2 class="fs-2 fw-bold text-dark mb-1">
            Novo Ticket
        </h2>
        <p class="text-muted">
            Crie um novo ticket de suporte
        </p>
    </div>
    <div class="mt-3 mt-md-0">
        <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center">
            <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Voltar aos Tickets
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="d-flex flex-column gap-4">
    <div class="row">
        <div class="col-12 col-lg-8">
            <!-- Formulário Principal -->
            <div class="card border-0">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-semibold">Informações do Ticket</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('tickets.store') }}" class="d-flex flex-column gap-4">
                        @csrf

                        <div class="row g-3">
                            <!-- Título -->
                            <div class="col-12">
                                <label for="title" class="form-label fw-medium text-dark">Título do Ticket <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}" 
                                    class="form-control @error('title') is-invalid @enderror" 
                                    placeholder="Descreva brevemente o problema" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Descrição -->
                            <div class="col-12">
                                <label for="description" class="form-label fw-medium text-dark">Descrição Detalhada <span class="text-danger">*</span></label>
                                <textarea name="description" id="description" rows="6" 
                                    class="form-control @error('description') is-invalid @enderror" 
                                    placeholder="Descreva detalhadamente o problema, incluindo passos para reproduzir, se aplicável" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Seja o mais específico possível para ajudar nossa equipe a resolver o problema rapidamente.
                                </div>
                            </div>

                            <!-- Categoria -->
                            <div class="col-12 col-sm-6">
                                <label for="category_id" class="form-label fw-medium text-dark">Categoria <span class="text-danger">*</span></label>
                                <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="">Selecione uma categoria</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Prioridade -->
                            <div class="col-12 col-sm-6">
                                <label for="priority" class="form-label fw-medium text-dark">Prioridade <span class="text-danger">*</span></label>
                                <select name="priority" id="priority" class="form-select @error('priority') is-invalid @enderror" required>
                                    <option value="">Selecione a prioridade</option>
                                    <option value="baixa" {{ old('priority') == 'baixa' ? 'selected' : '' }}>Baixa</option>
                                    <option value="média" {{ old('priority') == 'média' ? 'selected' : '' }}>Média</option>
                                    <option value="alta" {{ old('priority') == 'alta' ? 'selected' : '' }}>Alta</option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Cliente -->
                            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'tecnico')
                            <div class="col-12 col-sm-6">
                                <label for="client_id" class="form-label fw-medium text-dark">Cliente</label>
                                <select name="client_id" id="client_id" class="form-select @error('client_id') is-invalid @enderror">
                                    <option value="">Selecione um cliente</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                            {{ $client->name }}
                                            @if($client->company_name) - {{ $client->company_name }} @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('client_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            @endif

                            <!-- Técnico Responsável -->
                            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'tecnico')
                            <div class="col-12 col-sm-6">
                                <label for="assigned_to" class="form-label fw-medium text-dark">Técnico Responsável</label>
                                <select name="assigned_to" id="assigned_to" class="form-select @error('assigned_to') is-invalid @enderror">
                                    <option value="">Não atribuído</option>
                                    @foreach($technicians as $technician)
                                        <option value="{{ $technician->id }}" {{ old('assigned_to') == $technician->id ? 'selected' : '' }}>
                                            {{ $technician->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('assigned_to')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            @endif

                            <!-- Anexos -->
                            <div class="col-12">
                                <label for="attachments" class="form-label fw-medium text-dark">Anexos</label>
                                <input type="file" name="attachments[]" id="attachments" 
                                    class="form-control @error('attachments') is-invalid @enderror" 
                                    multiple accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.gif">
                                @error('attachments')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Formatos aceitos: PDF, DOC, DOCX, TXT, JPG, JPEG, PNG, GIF. Máximo 5 arquivos, 10MB cada.
                                </div>
                            </div>

                            <!-- Tags -->
                            <div class="col-12">
                                <label for="tags" class="form-label fw-medium text-dark">Tags</label>
                                <input type="text" name="tags" id="tags" value="{{ old('tags') }}" 
                                    class="form-control @error('tags') is-invalid @enderror" 
                                    placeholder="Digite as tags separadas por vírgula">
                                @error('tags')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Use tags para facilitar a busca e categorização do ticket.
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary d-inline-flex align-items-center">
                                <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Criar Ticket
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <!-- Sidebar com Informações -->
            <div class="d-flex flex-column gap-4">
                <!-- Dicas para um Bom Ticket -->
                <div class="card border-0">
                    <div class="card-header bg-info bg-opacity-10 border-0">
                        <h6 class="mb-0 fw-semibold text-info">
                            <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Dicas para um Bom Ticket
                        </h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2 d-flex align-items-start">
                                <svg class="me-2 mt-1 text-success" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="small">Seja específico no título</span>
                            </li>
                            <li class="mb-2 d-flex align-items-start">
                                <svg class="me-2 mt-1 text-success" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="small">Descreva o problema detalhadamente</span>
                            </li>
                            <li class="mb-2 d-flex align-items-start">
                                <svg class="me-2 mt-1 text-success" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="small">Inclua passos para reproduzir</span>
                            </li>
                            <li class="mb-2 d-flex align-items-start">
                                <svg class="me-2 mt-1 text-success" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="small">Anexe screenshots se relevante</span>
                            </li>
                            <li class="d-flex align-items-start">
                                <svg class="me-2 mt-1 text-success" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="small">Escolha a categoria correta</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Informações do Sistema -->
                <div class="card border-0">
                    <div class="card-header bg-light border-0">
                        <h6 class="mb-0 fw-semibold">Informações do Sistema</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted small">Ticket #</span>
                                <span class="fw-medium small">{{ $nextTicketNumber }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted small">Criado por</span>
                                <span class="fw-medium small">{{ auth()->user()->name }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted small">Data</span>
                                <span class="fw-medium small">{{ now()->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted small">Status</span>
                                <span class="badge bg-primary">Aberto</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SLA Estimado -->
                <div class="card border-0">
                    <div class="card-header bg-warning bg-opacity-10 border-0">
                        <h6 class="mb-0 fw-semibold text-warning">
                            <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            SLA Estimado
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted small">Baixa</span>
                                <span class="fw-medium small">72h</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted small">Média</span>
                                <span class="fw-medium small">48h</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted small">Alta</span>
                                <span class="fw-medium small">24h</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted small">Urgente</span>
                                <span class="fw-medium small">4h</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Preview de arquivos
document.getElementById('attachments').addEventListener('change', function(e) {
    const files = e.target.files;
    const maxFiles = 5;
    const maxSize = 10 * 1024 * 1024; // 10MB
    
    if (files.length > maxFiles) {
        alert(`Máximo de ${maxFiles} arquivos permitidos.`);
        this.value = '';
        return;
    }
    
    for (let i = 0; i < files.length; i++) {
        if (files[i].size > maxSize) {
            alert(`Arquivo "${files[i].name}" excede o tamanho máximo de 10MB.`);
            this.value = '';
            return;
        }
    }
});

// Validação do formulário
document.querySelector('form').addEventListener('submit', function(e) {
    const title = document.getElementById('title').value.trim();
    const description = document.getElementById('description').value.trim();
    const category = document.getElementById('category_id').value;
    const priority = document.getElementById('priority').value;
    
    if (!title || !description || !category || !priority) {
        e.preventDefault();
        alert('Por favor, preencha todos os campos obrigatórios.');
        return;
    }
    
    if (title.length < 10) {
        e.preventDefault();
        alert('O título deve ter pelo menos 10 caracteres.');
        return;
    }
    
    if (description.length < 20) {
        e.preventDefault();
        alert('A descrição deve ter pelo menos 20 caracteres.');
        return;
    }
});

// Auto-resize da textarea
document.getElementById('description').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = this.scrollHeight + 'px';
});
</script>
@endpush
@endsection

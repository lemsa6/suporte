@extends('layouts.app')

@section('title', 'Editar Ticket #' . $ticket->ticket_number)

@section('header')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
    <div class="flex-grow-1">
        <div class="d-flex align-items-center">
            <a href="{{ route('tickets.show', $ticket->ticket_number) }}" class="btn btn-outline-secondary me-3">
                <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Voltar
            </a>
            <div>
                <h2 class="fs-2 fw-bold text-dark mb-1">
                    Editar Ticket #{{ $ticket->ticket_number }}
                </h2>
                <p class="text-muted mb-0">
                    {{ $ticket->title }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="d-flex flex-column gap-4">
    <form action="{{ route('tickets.update', ['ticketNumber' => $ticket->ticket_number]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <!-- Informações Básicas -->
        <div class="card border-0">
            <div class="card-header">
                <h5 class="mb-0 fw-semibold">Informações do Ticket</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- Título -->
                    <div class="col-12">
                        <label for="title" class="form-label fw-medium text-dark">Título do Ticket *</label>
                        <input type="text" name="title" id="title" required
                            class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title', $ticket->title) }}"
                            placeholder="Descreva brevemente o problema">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Cliente -->
                    <div class="col-12 col-md-6">
                        <label for="client_id" class="form-label fw-medium text-dark">Cliente *</label>
                        <select name="client_id" id="client_id" required
                            class="form-select @error('client_id') is-invalid @enderror">
                            <option value="">Selecione o cliente</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id', $ticket->client_id) == $client->id ? 'selected' : '' }}>
                                    {{ $client->company_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Contato -->
                    <div class="col-12 col-md-6">
                        <label for="contact_id" class="form-label fw-medium text-dark">Contato *</label>
                        <select name="contact_id" id="contact_id" required
                            class="form-select @error('contact_id') is-invalid @enderror">
                            <option value="">Selecione o contato</option>
                            @foreach($ticket->client->contacts as $contact)
                                <option value="{{ $contact->id }}" {{ old('contact_id', $ticket->contact_id) == $contact->id ? 'selected' : '' }}>
                                    {{ $contact->name }}
                                    @if($contact->is_primary) (Principal) @endif
                                </option>
                            @endforeach
                        </select>
                        @error('contact_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Categoria -->
                    <div class="col-12 col-md-6">
                        <label for="category_id" class="form-label fw-medium text-dark">Categoria *</label>
                        <select name="category_id" id="category_id" required
                            class="form-select @error('category_id') is-invalid @enderror">
                            <option value="">Selecione a categoria</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $ticket->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Prioridade -->
                    <div class="col-12 col-md-6">
                        <label for="priority" class="form-label fw-medium text-dark">Prioridade *</label>
                        <select name="priority" id="priority" required
                            class="form-select @error('priority') is-invalid @enderror">
                            <option value="">Selecione a prioridade</option>
                            <option value="baixa" {{ old('priority', $ticket->priority) == 'baixa' ? 'selected' : '' }}>Baixa</option>
                            <option value="média" {{ old('priority', $ticket->priority) == 'média' ? 'selected' : '' }}>Média</option>
                            <option value="alta" {{ old('priority', $ticket->priority) == 'alta' ? 'selected' : '' }}>Alta</option>
                        </select>
                        @error('priority')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-12 col-md-6">
                        <label for="status" class="form-label fw-medium text-dark">Status *</label>
                        <select name="status" id="status" required
                            class="form-select @error('status') is-invalid @enderror">
                            <option value="aberto" {{ old('status', $ticket->status) == 'aberto' ? 'selected' : '' }}>Aberto</option>
                            <option value="em_andamento" {{ old('status', $ticket->status) == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                            <option value="resolvido" {{ old('status', $ticket->status) == 'resolvido' ? 'selected' : '' }}>Resolvido</option>
                            <option value="fechado" {{ old('status', $ticket->status) == 'fechado' ? 'selected' : '' }}>Fechado</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Urgente -->
                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox" id="is_urgent" name="is_urgent" value="1" 
                                class="form-check-input @error('is_urgent') is-invalid @enderror"
                                {{ old('is_urgent', $ticket->is_urgent) ? 'checked' : '' }}>
                            <label for="is_urgent" class="form-check-label text-dark">
                                Marcar como urgente
                            </label>
                            @error('is_urgent')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Descrição -->
        <div class="card border-0">
            <div class="card-header">
                <h5 class="mb-0 fw-semibold">Descrição do Problema</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="description" class="form-label fw-medium text-dark">Descrição Detalhada *</label>
                    <textarea name="description" id="description" rows="6" required
                        class="form-control @error('description') is-invalid @enderror"
                        placeholder="Descreva detalhadamente o problema, incluindo passos para reproduzir, mensagens de erro, etc.">{{ old('description', $ticket->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Atribuição -->
        @if(auth()->user()->can('assign-tickets'))
        <div class="card border-0">
            <div class="card-header">
                <h5 class="mb-0 fw-semibold">Atribuição</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="assigned_to" class="form-label fw-medium text-dark">Atribuir para</label>
                    <select name="assigned_to" id="assigned_to"
                        class="form-select @error('assigned_to') is-invalid @enderror">
                        <option value="">Não atribuir (deixar em fila)</option>
                        @foreach($technicians as $technician)
                            <option value="{{ $technician->id }}" {{ old('assigned_to', $ticket->assigned_to) == $technician->id ? 'selected' : '' }}>
                                {{ $technician->name }} ({{ ucfirst($technician->role) }})
                            </option>
                        @endforeach
                    </select>
                    @error('assigned_to')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        @endif

        <!-- Notas de Resolução -->
        @if($ticket->status === 'resolvido' || $ticket->status === 'fechado')
        <div class="card border-0">
            <div class="card-header">
                <h5 class="mb-0 fw-semibold">Notas de Resolução</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="resolution_notes" class="form-label fw-medium text-dark">Como foi resolvido</label>
                    <textarea name="resolution_notes" id="resolution_notes" rows="4"
                        class="form-control @error('resolution_notes') is-invalid @enderror"
                        placeholder="Descreva como o problema foi resolvido...">{{ old('resolution_notes', $ticket->resolution_notes) }}</textarea>
                    @error('resolution_notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        @endif

        <!-- Botões de Ação -->
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('tickets.show', $ticket->ticket_number) }}" class="btn btn-outline-secondary">
                <svg class="me-2" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Cancelar
            </a>
            
            <button type="submit" class="btn btn-primary">
                <svg class="me-2" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Atualizar Ticket
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Carregar contatos baseado no cliente selecionado
    document.getElementById('client_id').addEventListener('change', function() {
        const clientId = this.value;
        const contactSelect = document.getElementById('contact_id');
        
        // Limpar contatos
        contactSelect.innerHTML = '<option value="">Selecione o contato</option>';
        
        if (clientId) {
            // Fazer requisição AJAX para buscar contatos do cliente
            fetch(`/api/clients/${clientId}/contacts`)
                .then(response => response.json())
                .then(contacts => {
                    contacts.forEach(contact => {
                        const option = document.createElement('option');
                        option.value = contact.id;
                        option.textContent = contact.name;
                        if (contact.is_primary) {
                            option.textContent += ' (Principal)';
                        }
                        contactSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Erro ao carregar contatos:', error);
                });
        }
    });

    // Validação do formulário
    document.querySelector('form').addEventListener('submit', function(e) {
        const title = document.getElementById('title').value.trim();
        const description = document.getElementById('description').value.trim();
        
        if (!title || !description) {
            e.preventDefault();
            alert('Por favor, preencha todos os campos obrigatórios.');
            return;
        }
    });
</script>
@endpush
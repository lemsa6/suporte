@extends('layouts.app')

@section('title', 'Editar Ticket #' . $ticket->ticket_number)

@section('header')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
    <div class="flex-grow-1">
        <div class="d-flex align-items-center">
            <x-button variant="outline" tag="a" href="{{ route('tickets.show', $ticket->ticket_number) }}" class="me-3">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Voltar
            </x-button>
            <div>
                <h1 class="page-title mb-1">
                    Editar Ticket #{{ $ticket->ticket_number }}
                </h1>
                <p class="text-muted mb-0">
                    {{ $ticket->title }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <form action="{{ route('tickets.update', ['ticketNumber' => $ticket->ticket_number]) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Informações Básicas -->
        <x-card>
            <h2 class="section-title mb-6">Informações do Ticket</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Título -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Título do Ticket *</label>
                    <x-input id="title" name="title" required 
                             value="{{ old('title', $ticket->title) }}"
                             placeholder="Descreva brevemente o problema" />
                    @error('title')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Cliente -->
                <div>
                    <label for="client_id" class="block text-sm font-medium text-gray-700 mb-2">Cliente *</label>
                    <x-select id="client_id" name="client_id" required>
                        <option value="">Selecione o cliente</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id', $ticket->client_id) == $client->id ? 'selected' : '' }}>
                                {{ $client->company_name }}
                            </option>
                        @endforeach
                    </x-select>
                    @error('client_id')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Contato -->
                <div>
                    <label for="contact_id" class="block text-sm font-medium text-gray-700 mb-2">Contato *</label>
                    <x-select id="contact_id" name="contact_id" required>
                        <option value="">Selecione o contato</option>
                        @foreach($ticket->client->contacts as $contact)
                            <option value="{{ $contact->id }}" {{ old('contact_id', $ticket->contact_id) == $contact->id ? 'selected' : '' }}>
                                {{ $contact->name }}
                                @if($contact->is_primary) (Principal) @endif
                            </option>
                        @endforeach
                    </x-select>
                    @error('contact_id')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Categoria -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Categoria *</label>
                    <x-select id="category_id" name="category_id" required>
                        <option value="">Selecione a categoria</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $ticket->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </x-select>
                    @error('category_id')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Prioridade -->
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Prioridade *</label>
                    <x-select id="priority" name="priority" required>
                        <option value="">Selecione a prioridade</option>
                        <option value="baixa" {{ old('priority', $ticket->priority) == 'baixa' ? 'selected' : '' }}>Baixa</option>
                        <option value="média" {{ old('priority', $ticket->priority) == 'média' ? 'selected' : '' }}>Média</option>
                        <option value="alta" {{ old('priority', $ticket->priority) == 'alta' ? 'selected' : '' }}>Alta</option>
                    </x-select>
                    @error('priority')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <x-select id="status" name="status" required>
                        <option value="aberto" {{ old('status', $ticket->status) == 'aberto' ? 'selected' : '' }}>Aberto</option>
                        <option value="em_andamento" {{ old('status', $ticket->status) == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                        <option value="resolvido" {{ old('status', $ticket->status) == 'resolvido' ? 'selected' : '' }}>Resolvido</option>
                        <option value="fechado" {{ old('status', $ticket->status) == 'fechado' ? 'selected' : '' }}>Fechado</option>
                    </x-select>
                    @error('status')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Urgente -->
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <div class="flex items-center h-5">
                            <input type="checkbox" id="is_urgent" name="is_urgent" value="1" 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                   {{ old('is_urgent', $ticket->is_urgent) ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_urgent" class="font-medium text-gray-700">
                                Marcar como urgente
                            </label>
                        </div>
                    </div>
                    @error('is_urgent')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </x-card>

        <!-- Descrição -->
        <x-card>
            <h2 class="section-title mb-6">Descrição do Problema</h2>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Descrição Detalhada *</label>
                <x-textarea id="description" name="description" rows="6" required
                           placeholder="Descreva detalhadamente o problema, incluindo passos para reproduzir, mensagens de erro, etc.">{{ old('description', $ticket->description) }}</x-textarea>
                @error('description')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
        </x-card>

        <!-- Atribuição -->
        @if(auth()->user()->can('assign-tickets'))
        <x-card>
            <h2 class="section-title mb-6">Atribuição</h2>
            <div>
                <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-2">Atribuir para</label>
                <x-select id="assigned_to" name="assigned_to">
                    <option value="">Não atribuir (deixar em fila)</option>
                    @foreach($technicians as $technician)
                        <option value="{{ $technician->id }}" {{ old('assigned_to', $ticket->assigned_to) == $technician->id ? 'selected' : '' }}>
                            {{ $technician->name }} ({{ ucfirst($technician->role) }})
                        </option>
                    @endforeach
                </x-select>
                @error('assigned_to')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
        </x-card>
        @endif

        <!-- Notas de Resolução -->
        @if($ticket->status === 'resolvido' || $ticket->status === 'fechado')
        <x-card>
            <h2 class="section-title mb-6">Notas de Resolução</h2>
            <div>
                <label for="resolution_notes" class="block text-sm font-medium text-gray-700 mb-2">Como foi resolvido</label>
                <x-textarea id="resolution_notes" name="resolution_notes" rows="4"
                           placeholder="Descreva como o problema foi resolvido...">{{ old('resolution_notes', $ticket->resolution_notes) }}</x-textarea>
                @error('resolution_notes')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
        </x-card>
        @endif

        <!-- Botões de Ação -->
        <div class="flex justify-end gap-3">
            <x-button variant="outline" tag="a" href="{{ route('tickets.show', $ticket->ticket_number) }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Cancelar
            </x-button>
            
            <x-button variant="primary" type="submit">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Atualizar Ticket
            </x-button>
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
@extends('layouts.app')

@section('title', $client->company_name)

@section('header')
<div class="d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center">
        <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary btn-sm me-3">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h4 class="mb-0 fw-bold text-dark">{{ $client->company_name }}</h4>
            @if($client->trade_name)
                <p class="text-muted mb-0">({{ $client->trade_name }})</p>
            @endif
            <div class="d-flex align-items-center gap-3 mt-2">
                <span class="badge 
                    @if($client->is_active) bg-success
                    @else bg-danger
                    @endif">
                    {{ $client->is_active ? 'Ativo' : 'Inativo' }}
                </span>
                <span class="text-muted small">{{ $client->formatted_cnpj }}</span>
                <span class="text-muted small">{{ $client->contacts->count() }} contato(s)</span>
                <span class="text-muted small">{{ $client->tickets->count() }} ticket(s)</span>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('clients.edit', $client) }}" class="btn btn-outline-secondary btn-sm">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Editar
        </a>
        <a href="{{ route('tickets.create', ['client_id' => $client->id]) }}" class="btn btn-primary btn-sm">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Novo Ticket
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="d-flex flex-column gap-4">
    <!-- Estatísticas Rápidas -->
    <div class="row g-3">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon-primary me-3">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-muted small">Total de Tickets</div>
                            <div class="h4 mb-0 fw-bold">{{ $stats['total_tickets'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon-warning me-3">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-muted small">Em Aberto</div>
                            <div class="h4 mb-0 fw-bold">{{ $stats['open_tickets'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon-success me-3">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-muted small">Resolvidos</div>
                            <div class="h4 mb-0 fw-bold">{{ $stats['resolved_tickets'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon-danger me-3">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-muted small">Urgentes</div>
                            <div class="h4 mb-0 fw-bold">{{ $stats['urgent_tickets'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informações da Empresa -->
    <div class="card border-0">
        <div class="card-header">
            <h5 class="mb-0 fw-bold">Informações da Empresa</h5>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-12 col-lg-6">
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted">Nome da Empresa</label>
                        <p class="mb-0">{{ $client->company_name }}</p>
                    </div>
                    
                    @if($client->trade_name)
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted">Nome Fantasia</label>
                        <p class="mb-0">{{ $client->trade_name }}</p>
                    </div>
                    @endif
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted">CNPJ</label>
                        <p class="mb-0 font-monospace">{{ $client->formatted_cnpj }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted">Status</label>
                        <div>
                            <span class="badge 
                                @if($client->is_active) bg-success
                                @else bg-danger
                                @endif">
                                {{ $client->is_active ? 'Ativo' : 'Inativo' }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-lg-6">
                    @if($client->email)
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted">Email</label>
                        <p class="mb-0">
                            <a href="mailto:{{ $client->email }}" class="text-decoration-none">
                                {{ $client->email }}
                            </a>
                        </p>
                    </div>
                    @endif
                    
                    @if($client->phone)
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted">Telefone</label>
                        <p class="mb-0">
                            <a href="tel:{{ $client->phone }}" class="text-decoration-none">
                                {{ $client->phone }}
                            </a>
                        </p>
                    </div>
                    @endif
                    
                    @if($client->address)
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted">Endereço</label>
                        <p class="mb-0">{{ $client->address }}</p>
                    </div>
                    @endif
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted">Data de Cadastro</label>
                        <p class="mb-0">{{ $client->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
            
            @if($client->notes)
            <div class="mt-4 pt-3 border-top">
                <label class="form-label fw-medium text-muted">Observações</label>
                <div class="p-3 bg-light rounded">
                    <p class="mb-0">{{ $client->notes }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Contatos -->
    <div class="card border-0">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0 fw-bold">Contatos</h5>
            @if(auth()->user()->can('manage-contacts', $client))
            <button type="button" onclick="showAddContactModal()" class="btn btn-primary btn-sm">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Adicionar Contato
            </button>
            @endif
        </div>
        <div class="card-body">
            @if($client->contacts->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($client->contacts as $contact)
                        <div class="list-group-item px-0">
                            <div class="d-flex align-items-center">
                                <div class="user-avatar-small me-3">
                                    <span class="fw-medium">{{ substr($contact->name, 0, 1) }}</span>
                                </div>
                                
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h6 class="mb-0 fw-medium">{{ $contact->name }}</h6>
                                        @if($contact->is_primary)
                                            <span class="badge bg-primary">Principal</span>
                                        @endif
                                        @if($contact->user_type)
                                            <span class="badge 
                                                @if($contact->user_type === 'cliente_gestor') bg-info
                                                @else bg-secondary
                                                @endif">
                                                @if($contact->user_type === 'cliente_gestor')
                                                    Gestor
                                                @else
                                                    Funcionário
                                                @endif
                                            </span>
                                        @endif
                                    </div>
                                    <div class="d-flex align-items-center gap-3 text-muted small">
                                        <span>{{ $contact->email }}</span>
                                        @if($contact->phone)
                                            <span>•</span>
                                            <span>{{ $contact->phone }}</span>
                                        @endif
                                        @if($contact->position)
                                            <span>•</span>
                                            <span>{{ $contact->position }}</span>
                                        @endif
                                        @if($contact->department)
                                            <span>•</span>
                                            <span>{{ $contact->department }}</span>
                                        @endif
                                    </div>
                                </div>
                                
                                @if(auth()->user()->can('manage-contacts', $client))
                                <div class="d-flex gap-2">
                                    <a href="{{ route('clients.contacts.edit', ['client' => $client, 'contact' => $contact]) }}" 
                                        class="btn btn-outline-primary btn-sm">
                                        Editar
                                    </a>
                                    @if(!$contact->is_primary)
                                    <button type="button" onclick="deleteContact({{ $contact->id }})" 
                                        class="btn btn-outline-danger btn-sm">
                                        Excluir
                                    </button>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h5 class="mt-3 mb-1">Nenhum contato cadastrado</h5>
                    <p class="text-muted mb-0">Comece adicionando o primeiro contato para esta empresa.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Histórico de Tickets -->
    <div class="card border-0">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0 fw-bold">Histórico de Tickets</h5>
            <a href="{{ route('tickets.index', ['client_id' => $client->id]) }}" 
                class="btn btn-outline-primary btn-sm">
                Ver todos →
            </a>
        </div>
        <div class="card-body">
            @if($client->tickets->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($client->tickets->take(5) as $ticket)
                        <div class="list-group-item px-0">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon-primary me-3">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <a href="{{ route('tickets.show', $ticket->ticket_number) }}" class="fw-medium text-decoration-none">
                                            #{{ $ticket->ticket_number }} - {{ $ticket->title }}
                                        </a>
                                        <span class="badge 
                                            @if($ticket->status === 'aberto') bg-warning
                                            @elseif($ticket->status === 'em_andamento') bg-info
                                            @elseif($ticket->status === 'resolvido') bg-success
                                            @else bg-secondary
                                            @endif">
                                            {{ ucfirst($ticket->status) }}
                                        </span>
                                        @if($ticket->is_urgent)
                                            <span class="badge bg-danger">Urgente</span>
                                        @endif
                                    </div>
                                    <div class="d-flex align-items-center gap-3 text-muted small">
                                        <span>{{ $ticket->category->name }}</span>
                                        <span>•</span>
                                        <span>{{ ucfirst($ticket->priority) }}</span>
                                        <span>•</span>
                                        <span>{{ $ticket->opened_at->format('d/m/Y H:i') }}</span>
                                        @if($ticket->assignedTo)
                                            <span>•</span>
                                            <span>Responsável: {{ $ticket->assignedTo->name }}</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div>
                                    <a href="{{ route('tickets.show', $ticket->ticket_number) }}" 
                                        class="btn btn-outline-primary btn-sm">
                                        Ver detalhes
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($client->tickets->count() > 5)
                    <div class="text-center mt-4">
                        <a href="{{ route('tickets.index', ['client_id' => $client->id]) }}" 
                            class="btn btn-primary">
                            Ver todos os {{ $client->tickets->count() }} tickets
                        </a>
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h5 class="mt-3 mb-1">Nenhum ticket encontrado</h5>
                    <p class="text-muted mb-3">Esta empresa ainda não possui tickets de suporte.</p>
                    <a href="{{ route('tickets.create', ['client_id' => $client->id]) }}" 
                        class="btn btn-primary">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Abrir Primeiro Ticket
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal para adicionar/editar contato -->
<div class="modal fade" id="contact-modal" tabindex="-1" aria-labelledby="contact-modal-title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contact-modal-title">Adicionar Contato</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="contact-form">
                    @csrf
                    <input type="hidden" id="contact-id" name="contact_id">
                    
                    <div class="mb-3">
                        <label for="contact-name" class="form-label">Nome *</label>
                        <input type="text" id="contact-name" name="name" required class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label for="contact-email" class="form-label">Email *</label>
                        <input type="email" id="contact-email" name="email" required class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label for="contact-phone" class="form-label">Telefone</label>
                        <input type="tel" id="contact-phone" name="phone" class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label for="contact-position" class="form-label">Cargo</label>
                        <input type="text" id="contact-position" name="position" class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label for="contact-department" class="form-label">Departamento</label>
                        <input type="text" id="contact-department" name="department" class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" id="contact-is-primary" name="is_primary" value="1" class="form-check-input">
                            <label for="contact-is-primary" class="form-check-label">Contato principal</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="contact-user-type" class="form-label">Tipo de Usuário</label>
                        <select id="contact-user-type" name="user_type" class="form-select">
                            <option value="cliente_funcionario">Funcionário da Empresa</option>
                            <option value="cliente_gestor">Gestor da Empresa</option>
                        </select>
                        <div class="form-text">
                            <strong>Gestor:</strong> Pode criar usuários e ver todos os tickets da empresa<br>
                            <strong>Funcionário:</strong> Apenas seus próprios tickets
                        </div>
                    </div>
                </form>

                <div id="reset-password-section" class="mt-4 pt-3 border-top d-none">
                    <h6 class="fw-medium mb-3">Redefinir Senha</h6>
                    
                    <div class="mb-3">
                        <label for="new-password" class="form-label">Nova Senha</label>
                        <input type="password" id="new-password" name="new_password" class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirm-password" class="form-label">Confirmar Senha</label>
                        <input type="password" id="confirm-password" name="confirm_password" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="toggle-password-reset" onclick="togglePasswordReset()" 
                    class="btn btn-outline-info btn-sm">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                    Redefinir Senha
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" onclick="saveContact()" class="btn btn-primary">Salvar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function showAddContactModal() {
        document.getElementById('contact-modal-title').textContent = 'Adicionar Contato';
        document.getElementById('contact-form').reset();
        document.getElementById('contact-id').value = '';
        document.getElementById('contact-user-type').value = 'cliente_funcionario';
        
        const modal = new bootstrap.Modal(document.getElementById('contact-modal'));
        modal.show();
        
        // Mostrar botão de definir senha para novos contatos também
        document.getElementById('reset-password-section').classList.add('d-none');
        document.getElementById('toggle-password-reset').innerHTML = '<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg> Definir Senha';
        document.getElementById('toggle-password-reset').classList.remove('d-none');
        
        // Resetar campos de senha
        document.getElementById('new-password').value = '';
        document.getElementById('confirm-password').value = '';
    }

    function togglePasswordReset() {
        const passwordSection = document.getElementById('reset-password-section');
        const toggleButton = document.getElementById('toggle-password-reset');
        
        if (passwordSection.classList.contains('d-none')) {
            passwordSection.classList.remove('d-none');
            toggleButton.textContent = 'Cancelar Redefinição';
            toggleButton.classList.replace('btn-outline-info', 'btn-outline-danger');
        } else {
            passwordSection.classList.add('d-none');
            toggleButton.innerHTML = '<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg> Redefinir Senha';
            toggleButton.classList.replace('btn-outline-danger', 'btn-outline-info');
            
            // Limpar campos de senha
            document.getElementById('new-password').value = '';
            document.getElementById('confirm-password').value = '';
        }
    }

    function saveContact() {
        const form = document.getElementById('contact-form');
        const formData = new FormData(form);
        const contactId = document.getElementById('contact-id').value;
        
        // Adicionar token CSRF
        formData.append('_token', '{{ csrf_token() }}');
        
        // Corrigir o campo is_primary - sempre enviar, mesmo que seja false
        const isPrimary = document.getElementById('contact-is-primary').checked;
        formData.set('is_primary', isPrimary ? '1' : '0');
        
        // Verificar se está redefinindo senha
        const passwordSection = document.getElementById('reset-password-section');
        if (!passwordSection.classList.contains('d-none')) {
            const newPassword = document.getElementById('new-password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            
            // Validar senhas
            if (newPassword || confirmPassword) {
                if (newPassword !== confirmPassword) {
                    alert('As senhas não conferem!');
                    return;
                }
                
                if (newPassword.length < 8) {
                    alert('A senha deve ter pelo menos 8 caracteres!');
                    return;
                }
                
                // Adicionar senhas ao formData
                formData.append('new_password', newPassword);
                formData.append('new_password_confirmation', confirmPassword);
                formData.append('reset_password', '1');
            }
        }
        
        const url = contactId ? 
            `/clients/{{ $client->id }}/contacts/${contactId}` : 
            `/clients/{{ $client->id }}/contacts`;
        
        const method = contactId ? 'PUT' : 'POST';
        
        // Adicionar _method para PUT
        if (contactId) {
            formData.append('_method', 'PUT');
        }

        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            return response.json().then(data => {
                if (!response.ok) {
                    if (response.status === 422 && data.errors) {
                        throw { validationErrors: data.errors, message: data.message || 'Erro de validação' };
                    }
                    throw new Error(data.message || `HTTP error! status: ${response.status}`);
                }
                return data;
            });
        })
        .then(data => {
            if (data.success) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('contact-modal'));
                modal.hide();
                
                // Mostrar mensagem de sucesso
                if (data.password_reset) {
                    alert(data.message);
                }
                
                // Recarregar a página para mostrar o novo contato
                window.location.reload();
            } else {
                alert('Erro: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erro ao salvar contato:', error);
            
            // Handle validation errors
            if (error.validationErrors) {
                let errorMessage = 'Erros de validação:\n';
                for (const field in error.validationErrors) {
                    errorMessage += `- ${error.validationErrors[field].join('\n- ')}\n`;
                }
                alert(errorMessage);
            } else {
                alert('Erro ao salvar contato: ' + (error.message || 'Erro desconhecido'));
            }
        });
    }

    function deleteContact(contactId) {
        if (confirm('Tem certeza que deseja excluir este contato?')) {
            fetch(`/clients/{{ $client->id }}/contacts/${contactId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Recarregar a página para atualizar a lista
                    window.location.reload();
                } else {
                    alert('Erro: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erro ao excluir contato:', error);
                alert('Erro ao excluir contato');
            });
        }
    }
</script>
@endpush
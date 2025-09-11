@extends('layouts.app')

@section('title', $client->company_name)

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('clients.index') }}" class="text-cinza-claro hover:text-cinza">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <span class="text-sm text-cinza-claro">Clientes</span>
        </div>
        <h1 class="page-title mt-1">{{ $client->company_name }}</h1>
        @if($client->trade_name)
            <p class="text-cinza mt-2">Nome Fantasia: {{ $client->trade_name }}</p>
        @endif
        <div class="flex items-center gap-4 mt-3">
            <x-badge variant="{{ $client->is_active ? 'success' : 'danger' }}">
                {{ $client->is_active ? 'Ativo' : 'Inativo' }}
            </x-badge>
            <span class="text-sm text-cinza-claro">{{ $client->formatted_cnpj }}</span>
            <span class="text-sm text-cinza-claro">{{ $client->contacts->count() }} contato(s)</span>
            <span class="text-sm text-cinza-claro">{{ $client->tickets->count() }} ticket(s)</span>
        </div>
    </div>
    <div class="flex gap-3">
        <x-button variant="outline" tag="a" href="{{ route('clients.edit', $client) }}">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Editar
        </x-button>
        <x-button variant="primary" tag="a" href="{{ route('tickets.create', ['client_id' => $client->id]) }}">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Novo Ticket
        </x-button>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Estatísticas Rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-stat-card 
            title="Total de Tickets"
            :value="$stats['total_tickets']"
            color="primary"
        >
            <svg class="w-5 h-5 text-roxo" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </x-stat-card>

        <x-stat-card 
            title="Em Aberto"
            :value="$stats['open_tickets']"
            color="warning"
        >
            <svg class="w-5 h-5 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </x-stat-card>

        <x-stat-card 
            title="Resolvidos"
            :value="$stats['resolved_tickets']"
            color="success"
        >
            <svg class="w-5 h-5 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </x-stat-card>

        <x-stat-card 
            title="Urgentes"
            :value="$stats['urgent_tickets']"
            color="danger"
        >
            <svg class="w-5 h-5 text-danger-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
        </x-stat-card>
    </div>

    <!-- Informações da Empresa -->
    <h2 class="section-title mb-4">Informações da Empresa</h2>
    <x-card>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="space-y-4">
                <div class="flex items-center justify-between py-2 border-b border-padrao">
                    <span class="text-sm font-medium text-cinza">Nome da Empresa:</span>
                    <span class="font-medium text-cinza">{{ $client->company_name }}</span>
                </div>
                
                @if($client->trade_name)
                <div class="flex items-center justify-between py-2 border-b border-padrao">
                    <span class="text-sm font-medium text-cinza">Nome Fantasia:</span>
                    <span class="font-medium text-cinza">{{ $client->trade_name }}</span>
                </div>
                @endif
                
                <div class="flex items-center justify-between py-2 border-b border-padrao">
                    <span class="text-sm font-medium text-cinza">CNPJ:</span>
                    <span class="font-mono text-sm text-cinza">{{ $client->formatted_cnpj }}</span>
                </div>
                
                <div class="flex items-center justify-between py-2 border-b border-padrao">
                    <span class="text-sm font-medium text-cinza">Status:</span>
                    <x-badge variant="{{ $client->is_active ? 'success' : 'danger' }}">
                        {{ $client->is_active ? 'Ativo' : 'Inativo' }}
                    </x-badge>
                </div>
            </div>
            
            <div class="space-y-4">
                @if($client->email)
                <div class="flex items-center justify-between py-2 border-b border-padrao">
                    <span class="text-sm font-medium text-cinza">Email:</span>
                    <a href="mailto:{{ $client->email }}" class="text-roxo hover:text-roxo-det text-sm">
                        {{ $client->email }}
                    </a>
                </div>
                @endif
                
                @if($client->phone)
                <div class="flex items-center justify-between py-2 border-b border-padrao">
                    <span class="text-sm font-medium text-cinza">Telefone:</span>
                    <a href="tel:{{ $client->phone }}" class="text-roxo hover:text-roxo-det text-sm">
                        {{ $client->phone }}
                    </a>
                </div>
                @endif
                
                @if($client->address)
                <div class="flex items-center justify-between py-2 border-b border-padrao">
                    <span class="text-sm font-medium text-cinza">Endereço:</span>
                    <span class="text-sm text-right max-w-xs text-cinza">{{ $client->address }}</span>
                </div>
                @endif
                
                <div class="flex items-center justify-between py-2 border-b border-padrao">
                    <span class="text-sm font-medium text-cinza">Data de Cadastro:</span>
                    <span class="text-sm text-cinza">{{ $client->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>
        
        @if($client->notes)
        <div class="mt-6 pt-6 border-t border-padrao">
            <h3 class="text-sm font-medium text-cinza mb-3">Observações</h3>
            <div class="bg-creme border border-padrao p-4 rounded-lg">
                <p class="text-sm whitespace-pre-wrap">{{ $client->notes }}</p>
            </div>
        </div>
        @endif
    </x-card>

    <!-- Contatos -->
    <h2 class="section-title mb-4">Contatos</h2>
    <x-card>
        <x-slot name="actions">
            @if(auth()->user()->can('manage-contacts', $client))
            <x-button variant="primary" size="sm" onclick="showAddContactModal()">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Adicionar Contato
            </x-button>
            @endif
        </x-slot>
        
        @if($client->contacts->count() > 0)
            <div class="space-y-4">
                @foreach($client->contacts as $contact)
                    <div class="flex items-center p-4 bg-creme rounded-lg border border-padrao">
                        <div class="w-10 h-10 bg-roxo bg-opacity-10 rounded-full flex items-center justify-center mr-4">
                            <span class="text-sm font-medium text-roxo">{{ substr($contact->name, 0, 1) }}</span>
                        </div>
                        
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <h4 class="font-medium text-cinza">{{ $contact->name }}</h4>
                                @if($contact->is_primary)
                                    <x-badge variant="primary" size="sm">Principal</x-badge>
                                @endif
                                @if($contact->user_type)
                                    <x-badge variant="{{ $contact->user_type === 'cliente_gestor' ? 'info' : 'secondary' }}" size="sm">
                                        {{ $contact->user_type === 'cliente_gestor' ? 'Gestor' : 'Funcionário' }}
                                    </x-badge>
                                @endif
                            </div>
                            <div class="flex items-center gap-4 text-sm text-cinza-claro">
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
                        <div class="flex gap-2">
                            <x-button variant="outline" size="sm" tag="a" href="{{ route('clients.contacts.edit', ['client' => $client, 'contact' => $contact]) }}">
                                Editar
                            </x-button>
                            @if(!$contact->is_primary)
                            <x-button variant="outline" size="sm" type="button" onclick="deleteContact({{ $contact->id }})" class="text-danger">
                                Excluir
                            </x-button>
                            @endif
                        </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <svg width="64" height="64" fill="none" stroke="currentColor" class="text-cinza-claro mb-4 mx-auto" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h6 class="text-cinza-claro mb-2">Nenhum contato cadastrado</h6>
                <p class="text-cinza-claro-2 text-sm mb-4">Comece adicionando o primeiro contato para esta empresa.</p>
            </div>
        @endif
    </x-card>

    <!-- Histórico de Tickets -->
    <h2 class="section-title mb-4">Histórico de Tickets</h2>
    <x-card>
        <x-slot name="actions">
            <x-button variant="outline" size="sm" tag="a" href="{{ route('tickets.index', ['client_id' => $client->id]) }}">
                Ver todos →
            </x-button>
        </x-slot>
        
        @if($client->tickets->count() > 0)
            <div class="space-y-4">
                @foreach($client->tickets->take(5) as $ticket)
                    <div class="flex items-center p-4 bg-creme rounded-lg border border-padrao">
                        <div class="w-10 h-10 bg-roxo bg-opacity-10 rounded-full flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-roxo" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <a href="{{ route('tickets.show', $ticket->ticket_number) }}" class="font-medium text-roxo hover:text-roxo-det">
                                    #{{ $ticket->ticket_number }} - {{ $ticket->title }}
                                </a>
                                <x-badge variant="{{ $ticket->status === 'aberto' ? 'warning' : ($ticket->status === 'em_andamento' ? 'info' : ($ticket->status === 'resolvido' ? 'success' : 'secondary')) }}">
                                    {{ ucfirst($ticket->status) }}
                                </x-badge>
                                @if($ticket->is_urgent)
                                    <x-badge variant="danger">Urgente</x-badge>
                                @endif
                            </div>
                            <div class="flex items-center gap-4 text-sm text-cinza-claro">
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
                            <x-button variant="outline" size="sm" tag="a" href="{{ route('tickets.show', $ticket->ticket_number) }}">
                                Ver detalhes
                            </x-button>
                        </div>
                    </div>
                @endforeach
            </div>
            
            @if($client->tickets->count() > 5)
                <div class="text-center mt-6">
                    <x-button variant="primary" tag="a" href="{{ route('tickets.index', ['client_id' => $client->id]) }}">
                        Ver todos os {{ $client->tickets->count() }} tickets
                    </x-button>
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <svg width="64" height="64" fill="none" stroke="currentColor" class="text-cinza-claro mb-4 mx-auto" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h6 class="text-cinza-claro mb-2">Nenhum ticket encontrado</h6>
                <p class="text-cinza-claro-2 text-sm mb-4">Esta empresa ainda não possui tickets de suporte.</p>
                <x-button variant="primary" tag="a" href="{{ route('tickets.create', ['client_id' => $client->id]) }}">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Abrir Primeiro Ticket
                </x-button>
            </div>
        @endif
    </x-card>
</div>

<!-- Modal para adicionar/editar contato -->
<div class="modal fade" id="contact-modal" tabindex="-1" aria-labelledby="contact-modal-title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contact-modal-title">Adicionar Contato</h5>
                <button type="button" class="absolute top-4 right-4 text-cinza-claro hover:text-cinza" onclick="closeModal()" aria-label="Close">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <form id="contact-form">
                    @csrf
                    <input type="hidden" id="contact-id" name="contact_id">
                    
                    <div class="space-y-4">
                        <x-input 
                            label="Nome *"
                            name="name"
                            id="contact-name"
                            required
                        />
                        
                        <x-input 
                            label="Email *"
                            name="email"
                            id="contact-email"
                            type="email"
                            required
                        />
                        
                        <x-input 
                            label="Telefone"
                            name="phone"
                            id="contact-phone"
                            type="tel"
                        />
                        
                        <x-input 
                            label="Cargo"
                            name="position"
                            id="contact-position"
                        />
                        
                        <x-input 
                            label="Departamento"
                            name="department"
                            id="contact-department"
                        />
                        
                        <div class="flex items-center">
                            <input type="checkbox" id="contact-is-primary" name="is_primary" value="1" class="form-check-input">
                            <label for="contact-is-primary" class="ml-2 text-sm text-cinza">Contato principal</label>
                        </div>
                        
                        <x-select 
                            label="Tipo de Usuário"
                            name="user_type"
                            id="contact-user-type"
                            :options="[
                                'cliente_funcionario' => 'Funcionário da Empresa',
                                'cliente_gestor' => 'Gestor da Empresa'
                            ]"
                        />
                        <div class="text-sm text-cinza-claro">
                            <strong>Gestor:</strong> Pode criar usuários e ver todos os tickets da empresa<br>
                            <strong>Funcionário:</strong> Apenas seus próprios tickets
                        </div>
                    </div>
                </form>

                <div id="reset-password-section" class="mt-6 pt-6 border-t border-padrao hidden">
                    <h6 class="font-medium mb-4">Redefinir Senha</h6>
                    
                    <div class="space-y-4">
                        <x-input 
                            label="Nova Senha"
                            name="new_password"
                            id="new-password"
                            type="password"
                        />
                        
                        <x-input 
                            label="Confirmar Senha"
                            name="confirm_password"
                            id="confirm-password"
                            type="password"
                        />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <x-button variant="outline" size="sm" type="button" id="toggle-password-reset" onclick="togglePasswordReset()">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                    Redefinir Senha
                </x-button>
                <x-button variant="outline" type="button" onclick="closeModal()">Cancelar</x-button>
                <x-button variant="primary" type="button" onclick="saveContact()">Salvar</x-button>
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
        document.getElementById('reset-password-section').classList.add('hidden');
        document.getElementById('toggle-password-reset').innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg> Definir Senha';
        document.getElementById('toggle-password-reset').classList.remove('hidden');
        
        // Resetar campos de senha
        document.getElementById('new-password').value = '';
        document.getElementById('confirm-password').value = '';
    }

    function togglePasswordReset() {
        const passwordSection = document.getElementById('reset-password-section');
        const toggleButton = document.getElementById('toggle-password-reset');
        
        if (passwordSection.classList.contains('hidden')) {
            passwordSection.classList.remove('hidden');
            toggleButton.textContent = 'Cancelar Redefinição';
            toggleButton.classList.replace('btn-outline', 'btn-danger');
        } else {
            passwordSection.classList.add('hidden');
            toggleButton.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg> Redefinir Senha';
            toggleButton.classList.replace('btn-danger', 'btn-outline');
            
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
        if (!passwordSection.classList.contains('hidden')) {
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
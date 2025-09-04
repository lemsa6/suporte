@extends('layouts.app')

@section('title', 'Editar Cliente - ' . $client->company_name)

@section('header')
<div class="d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center">
        <a href="{{ route('clients.show', $client) }}" class="btn btn-outline-secondary btn-sm me-3">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h4 class="mb-0 fw-bold text-dark">Editar Cliente</h4>
            <p class="text-muted mb-0">{{ $client->company_name }}</p>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-10">
        <form action="{{ route('clients.update', $client) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Informações da Empresa -->
            <div class="card border-0 mb-4">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold">Informações da Empresa</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Nome da Empresa -->
                        <div class="col-12">
                            <label for="company_name" class="form-label">Nome da Empresa *</label>
                            <input type="text" name="company_name" id="company_name" required
                                class="form-control @error('company_name') is-invalid @enderror"
                                value="{{ old('company_name', $client->company_name) }}"
                                placeholder="Nome completo da empresa">
                            @error('company_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nome Fantasia -->
                        <div class="col-12 col-md-6">
                            <label for="trade_name" class="form-label">Nome Fantasia</label>
                            <input type="text" name="trade_name" id="trade_name"
                                class="form-control @error('trade_name') is-invalid @enderror"
                                value="{{ old('trade_name', $client->trade_name) }}"
                                placeholder="Nome fantasia (se houver)">
                            @error('trade_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CNPJ -->
                        <div class="col-12 col-md-6">
                            <label for="cnpj" class="form-label">CNPJ *</label>
                            <input type="text" name="cnpj" id="cnpj" required maxlength="18"
                                class="form-control font-monospace @error('cnpj') is-invalid @enderror"
                                value="{{ old('cnpj', $client->cnpj) }}"
                                placeholder="00.000.000/0000-00">
                            @error('cnpj')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="col-12 col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $client->email) }}"
                                placeholder="email@empresa.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Telefone -->
                        <div class="col-12 col-md-6">
                            <label for="phone" class="form-label">Telefone</label>
                            <input type="tel" name="phone" id="phone"
                                class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone', $client->phone) }}"
                                placeholder="(11) 99999-9999">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Endereço -->
                        <div class="col-12">
                            <label for="address" class="form-label">Endereço</label>
                            <textarea name="address" id="address" rows="3"
                                class="form-control @error('address') is-invalid @enderror"
                                placeholder="Endereço completo da empresa">{{ old('address', $client->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Observações -->
                        <div class="col-12">
                            <label for="notes" class="form-label">Observações</label>
                            <textarea name="notes" id="notes" rows="3"
                                class="form-control @error('notes') is-invalid @enderror"
                                placeholder="Informações adicionais sobre a empresa">{{ old('notes', $client->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" id="is_active" name="is_active" value="1" 
                                    class="form-check-input @error('is_active') is-invalid @enderror"
                                    {{ old('is_active', $client->is_active) ? 'checked' : '' }}>
                                <label for="is_active" class="form-check-label">
                                    Cliente ativo
                                </label>
                            </div>
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contatos Existentes -->
            <div class="card border-0 mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 fw-bold">Contatos Existentes</h5>
                    <button type="button" onclick="showAddContactModal()" class="btn btn-primary btn-sm">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Adicionar Contato
                    </button>
                </div>
                <div class="card-body">
                    @if($client->contacts->count() > 0)
                        <div id="contacts-container">
                            @foreach($client->contacts as $index => $contact)
                                <div class="contact-item border rounded p-3 mb-3 {{ $contact->is_primary ? 'bg-light' : 'bg-white' }}">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="mb-0 fw-medium">
                                            @if($contact->is_primary)
                                                Contato Principal
                                            @else
                                                Contato {{ $index + 1 }}
                                            @endif
                                        </h6>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($contact->is_primary)
                                                <span class="badge bg-primary">Principal</span>
                                            @endif
                                            @if(!$contact->is_primary)
                                                <button type="button" onclick="removeContact({{ $contact->id }})" 
                                                    class="btn btn-outline-danger btn-sm">
                                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                    Remover
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="row g-3">
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Nome *</label>
                                            <input type="text" name="contacts[{{ $index }}][name]" required
                                                class="form-control"
                                                value="{{ $contact->name }}"
                                                placeholder="Nome completo do contato">
                                        </div>
                                        
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Email *</label>
                                            <input type="email" name="contacts[{{ $index }}][email]" required
                                                class="form-control"
                                                value="{{ $contact->email }}"
                                                placeholder="email@contato.com">
                                        </div>
                                        
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Telefone</label>
                                            <input type="tel" name="contacts[{{ $index }}][phone]"
                                                class="form-control"
                                                value="{{ $contact->phone }}"
                                                placeholder="(11) 99999-9999">
                                        </div>
                                        
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Cargo</label>
                                            <input type="text" name="contacts[{{ $index }}][position]"
                                                class="form-control"
                                                value="{{ $contact->position }}"
                                                placeholder="Cargo/função">
                                        </div>
                                        
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Departamento</label>
                                            <input type="text" name="contacts[{{ $index }}][department]"
                                                class="form-control"
                                                value="{{ $contact->department }}"
                                                placeholder="Departamento">
                                        </div>
                                        
                                        <div class="col-12 col-md-6">
                                            <div class="form-check mt-4">
                                                <input type="checkbox" name="contacts[{{ $index }}][is_primary]" value="1" 
                                                    class="form-check-input"
                                                    {{ $contact->is_primary ? 'checked' : '' }}
                                                    onchange="updatePrimaryContact(this, {{ $index }})">
                                                <label class="form-check-label">Contato principal</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" name="contacts[{{ $index }}][id]" value="{{ $contact->id }}">
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <h5 class="mt-3 mb-1">Nenhum contato cadastrado</h5>
                            <p class="text-muted mb-0">Adicione pelo menos um contato para esta empresa.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Botões de Ação -->
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('clients.show', $client) }}" class="btn btn-outline-secondary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancelar
                </a>
                
                <button type="submit" class="btn btn-primary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Atualizar Cliente
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para adicionar novo contato -->
<div class="modal fade" id="add-contact-modal" tabindex="-1" aria-labelledby="add-contact-modal-title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add-contact-modal-title">Adicionar Novo Contato</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="new-contact-form">
                    <div class="mb-3">
                        <label for="new-contact-name" class="form-label">Nome *</label>
                        <input type="text" id="new-contact-name" name="name" required class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label for="new-contact-email" class="form-label">Email *</label>
                        <input type="email" id="new-contact-email" name="email" required class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label for="new-contact-phone" class="form-label">Telefone</label>
                        <input type="tel" id="new-contact-phone" name="phone" class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label for="new-contact-position" class="form-label">Cargo</label>
                        <input type="text" id="new-contact-position" name="position" class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label for="new-contact-department" class="form-label">Departamento</label>
                        <input type="text" id="new-contact-department" name="department" class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" id="new-contact-is-primary" name="is_primary" value="1" class="form-check-input">
                            <label for="new-contact-is-primary" class="form-check-label">Contato principal</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" onclick="addNewContact()" class="btn btn-primary">Adicionar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let contactIndex = {{ $client->contacts->count() }};

    function showAddContactModal() {
        const modal = new bootstrap.Modal(document.getElementById('add-contact-modal'));
        modal.show();
    }

    function addNewContact() {
        const form = document.getElementById('new-contact-form');
        const formData = new FormData(form);
        
        // Criar novo contato dinamicamente
        const contactDiv = document.createElement('div');
        contactDiv.className = 'contact-item border rounded p-3 mb-3 bg-white';
        contactDiv.innerHTML = `
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="mb-0 fw-medium">Novo Contato</h6>
                <button type="button" onclick="removeContact(this)" 
                    class="btn btn-outline-danger btn-sm">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Remover
                </button>
            </div>
            
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <label class="form-label">Nome *</label>
                    <input type="text" name="contacts[${contactIndex}][name]" required
                        class="form-control"
                        value="${formData.get('name')}"
                        placeholder="Nome completo do contato">
                </div>
                
                <div class="col-12 col-md-6">
                    <label class="form-label">Email *</label>
                    <input type="email" name="contacts[${contactIndex}][email]" required
                        class="form-control"
                        value="${formData.get('email')}"
                        placeholder="email@contato.com">
                </div>
                
                <div class="col-12 col-md-6">
                    <label class="form-label">Telefone</label>
                    <input type="tel" name="contacts[${contactIndex}][phone]"
                        class="form-control"
                        value="${formData.get('phone')}"
                        placeholder="(11) 99999-9999">
                </div>
                
                <div class="col-12 col-md-6">
                    <label class="form-label">Cargo</label>
                    <input type="text" name="contacts[${contactIndex}][position]"
                        class="form-control"
                        value="${formData.get('position')}"
                        placeholder="Cargo/função">
                </div>
                
                <div class="col-12 col-md-6">
                    <label class="form-label">Departamento</label>
                    <input type="text" name="contacts[${contactIndex}][department]"
                        class="form-control"
                        value="${formData.get('department')}"
                        placeholder="Departamento">
                </div>
                
                <div class="col-12 col-md-6">
                    <div class="form-check mt-4">
                        <input type="checkbox" name="contacts[${contactIndex}][is_primary]" value="1"
                            class="form-check-input"
                            ${formData.get('is_primary') ? 'checked' : ''}
                            onchange="updatePrimaryContact(this, ${contactIndex})">
                        <label class="form-check-label">Contato principal</label>
                    </div>
                </div>
            </div>
        `;
        
        // Adicionar ao container de contatos
        const container = document.getElementById('contacts-container');
        if (container) {
            container.appendChild(contactDiv);
        } else {
            // Se não existir container, criar um
            const contactsSection = document.querySelector('.card-body');
            const newContainer = document.createElement('div');
            newContainer.id = 'contacts-container';
            newContainer.appendChild(contactDiv);
            contactsSection.appendChild(newContainer);
        }
        
        contactIndex++;
        
        // Fechar modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('add-contact-modal'));
        modal.hide();
        
        // Limpar formulário
        form.reset();
    }

    function removeContact(button) {
        if (confirm('Tem certeza que deseja remover este contato?')) {
            button.closest('.contact-item').remove();
        }
    }

    function updatePrimaryContact(checkbox, index) {
        if (checkbox.checked) {
            // Desmarcar todos os outros checkboxes
            document.querySelectorAll('input[name$="[is_primary]"]').forEach(cb => {
                if (cb !== checkbox) {
                    cb.checked = false;
                }
            });
        }
    }

    // Formatação automática do CNPJ
    document.getElementById('cnpj').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length <= 14) {
            value = value.replace(/^(\d{2})(\d)/, '$1.$2');
            value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
            value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
            value = value.replace(/(\d{4})(\d)/, '$1-$2');
            e.target.value = value;
        }
    });

    // Formatação automática do telefone
    document.getElementById('phone').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length <= 11) {
            value = value.replace(/^(\d{2})(\d)/, '($1) $2');
            value = value.replace(/(\d)(\d{4})$/, '$1-$2');
            e.target.value = value;
        }
    });
</script>
@endpush
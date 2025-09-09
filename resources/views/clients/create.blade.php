@extends('layouts.app')

@section('title', 'Novo Cliente')

@section('header')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
    <div class="flex-grow-1">
        <div class="d-flex align-items-center mb-2">
            <x-button variant="outline" size="sm" tag="a" href="{{ route('clients.index') }}" class="me-3">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Voltar
            </x-button>
            <h2 class="fs-2 fw-bold text-dark mb-0">
                Novo Cliente
            </h2>
        </div>
        <p class="text-muted">
            Cadastre uma nova empresa cliente
        </p>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-column gap-4">
        <form action="{{ route('clients.store') }}" method="POST" class="d-flex flex-column gap-4">
            @csrf
            
            <!-- Informações da Empresa -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-semibold">Informações da Empresa</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Nome da Empresa -->
                        <div class="col-12">
                            <label for="company_name" class="form-label fw-medium text-dark">Nome da Empresa *</label>
                            <input type="text" name="company_name" id="company_name" required
                                class="form-control @error('company_name') is-invalid @enderror"
                                value="{{ old('company_name') }}"
                                placeholder="Nome completo da empresa">
                            @error('company_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nome Fantasia e CNPJ -->
                        <div class="col-12 col-md-6">
                            <label for="trade_name" class="form-label fw-medium text-dark">Nome Fantasia</label>
                            <input type="text" name="trade_name" id="trade_name"
                                class="form-control @error('trade_name') is-invalid @enderror"
                                value="{{ old('trade_name') }}"
                                placeholder="Nome fantasia (se houver)">
                            @error('trade_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="cnpj" class="form-label fw-medium text-dark">CNPJ *</label>
                            <input type="text" name="cnpj" id="cnpj" required maxlength="18"
                                class="form-control @error('cnpj') is-invalid @enderror"
                                value="{{ old('cnpj') }}"
                                placeholder="00.000.000/0000-00">
                            @error('cnpj')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email e Telefone -->
                        <div class="col-12 col-md-6">
                            <label for="email" class="form-label fw-medium text-dark">Email</label>
                            <input type="email" name="email" id="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
                                placeholder="email@empresa.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="phone" class="form-label fw-medium text-dark">Telefone</label>
                            <input type="tel" name="phone" id="phone"
                                class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone') }}"
                                placeholder="(11) 99999-9999">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Endereço -->
                        <div class="col-12">
                            <label for="address" class="form-label fw-medium text-dark">Endereço</label>
                            <textarea name="address" id="address" rows="3"
                                class="form-control @error('address') is-invalid @enderror"
                                placeholder="Endereço completo da empresa">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Observações -->
                        <div class="col-12">
                            <label for="notes" class="form-label fw-medium text-dark">Observações</label>
                            <textarea name="notes" id="notes" rows="3"
                                class="form-control @error('notes') is-invalid @enderror"
                                placeholder="Informações adicionais sobre a empresa">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" id="is_active" name="is_active" value="1" 
                                    class="form-check-input @error('is_active') is-invalid @enderror"
                                    {{ old('is_active', true) ? 'checked' : '' }}>
                                <label for="is_active" class="form-check-label fw-medium text-dark">
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

            <!-- Contatos -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-semibold">Contatos</h5>
                        <x-button variant="outline" size="sm" type="button" onclick="addContact()">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Adicionar Contato
                        </x-button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="contacts-container" class="d-flex flex-column gap-3">
                        <!-- Contato Principal -->
                        <div class="contact-item border rounded p-3 bg-light">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h6 class="mb-0 fw-medium text-dark">Contato Principal</h6>
                                <span class="badge bg-primary">Principal</span>
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-medium text-dark">Nome *</label>
                                    <input type="text" name="contacts[0][name]" required
                                        class="form-control"
                                        value="{{ old('contacts.0.name') }}"
                                        placeholder="Nome completo do contato">
                                </div>
                                
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-medium text-dark">Email *</label>
                                    <input type="email" name="contacts[0][email]" required
                                        class="form-control"
                                        value="{{ old('contacts.0.email') }}"
                                        placeholder="email@contato.com">
                                </div>
                                
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-medium text-dark">Telefone</label>
                                    <input type="tel" name="contacts[0][phone]"
                                        class="form-control"
                                        value="{{ old('contacts.0.phone') }}"
                                        placeholder="(11) 99999-9999">
                                </div>
                                
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-medium text-dark">Cargo</label>
                                    <input type="text" name="contacts[0][position]"
                                        class="form-control"
                                        value="{{ old('contacts.0.position') }}"
                                        placeholder="Cargo/função">
                                </div>
                                
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-medium text-dark">Departamento</label>
                                    <input type="text" name="contacts[0][department]"
                                        class="form-control"
                                        value="{{ old('contacts.0.department') }}"
                                        placeholder="Departamento">
                                </div>
                                
                                <div class="col-12 col-md-6">
                                    <div class="form-check">
                                        <input type="checkbox" name="contacts[0][is_primary]" value="1" checked
                                            class="form-check-input">
                                        <label class="form-check-label fw-medium text-dark">Contato principal</label>
                                    </div>
                                </div>
                            </div>
                            
                            <input type="hidden" name="contacts[0][is_primary]" value="1">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botões de Ação -->
            <div class="d-flex justify-content-end gap-2">
                <x-button variant="outline" tag="a" href="{{ route('clients.index') }}">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancelar
                </x-button>
                
                <x-button variant="primary" type="submit">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Criar Cliente
                </x-button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let contactIndex = 1;

    function addContact() {
        const container = document.getElementById('contacts-container');
        const contactDiv = document.createElement('div');
        contactDiv.className = 'contact-item border rounded p-3 bg-white';
        contactDiv.innerHTML = `
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="mb-0 fw-medium text-dark">Contato Adicional</h6>
                <button type="button" onclick="removeContact(this)" 
                    variant="outline" size="sm" class="btn-outline-danger">
                    <svg class="me-1" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Remover
                </button>
            </div>
            
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <label class="form-label fw-medium text-dark">Nome *</label>
                    <input type="text" name="contacts[${contactIndex}][name]" required
                        class="form-control"
                        placeholder="Nome completo do contato">
                </div>
                
                <div class="col-12 col-md-6">
                    <label class="form-label fw-medium text-dark">Email *</label>
                    <input type="email" name="contacts[${contactIndex}][email]" required
                        class="form-control"
                        placeholder="email@contato.com">
                </div>
                
                <div class="col-12 col-md-6">
                    <label class="form-label fw-medium text-dark">Telefone</label>
                    <input type="tel" name="contacts[${contactIndex}][phone]"
                        class="form-control"
                        placeholder="(11) 99999-9999">
                </div>
                
                <div class="col-12 col-md-6">
                    <label class="form-label fw-medium text-dark">Cargo</label>
                    <input type="text" name="contacts[${contactIndex}][position]"
                        class="form-control"
                        placeholder="Cargo/função">
                </div>
                
                <div class="col-12 col-md-6">
                    <label class="form-label fw-medium text-dark">Departamento</label>
                    <input type="text" name="contacts[${contactIndex}][department]"
                        class="form-control"
                        placeholder="Departamento">
                </div>
                
                <div class="col-12 col-md-6">
                    <div class="form-check">
                        <input type="checkbox" name="contacts[${contactIndex}][is_primary]" value="1"
                            class="form-check-input">
                        <label class="form-check-label fw-medium text-dark">Contato principal</label>
                    </div>
                </div>
            </div>
        `;
        
        container.appendChild(contactDiv);
        contactIndex++;
    }

    function removeContact(button) {
        button.closest('.contact-item').remove();
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

    // Validação do formulário
    document.querySelector('form').addEventListener('submit', function(e) {
        const companyName = document.getElementById('company_name').value.trim();
        const cnpj = document.getElementById('cnpj').value.trim();
        
        if (!companyName || !cnpj) {
            e.preventDefault();
            alert('Por favor, preencha todos os campos obrigatórios.');
            return;
        }
        
        // Validar CNPJ (formato brasileiro: XX.XXX.XXX/XXXX-XX)
        const cnpjPattern = /^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$/;
        if (!cnpjPattern.test(cnpj)) {
            e.preventDefault();
            alert('Por favor, insira um CNPJ no formato correto: XX.XXX.XXX/XXXX-XX');
            return;
        }
        
        // Validar se tem pelo menos um contato
        const contactItems = document.querySelectorAll('.contact-item');
        let hasValidContact = false;
        
        contactItems.forEach(item => {
            const name = item.querySelector('input[name*="[name]"]').value.trim();
            const email = item.querySelector('input[name*="[email]"]').value.trim();
            
            if (name && email) {
                hasValidContact = true;
            }
        });
        
        if (!hasValidContact) {
            e.preventDefault();
            alert('Por favor, preencha pelo menos um contato com nome e email.');
            return;
        }
    });
</script>
@endpush
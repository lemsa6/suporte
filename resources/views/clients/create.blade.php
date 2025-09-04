@extends('layouts.app')

@section('title', 'Novo Cliente')

@section('header')
<div class="md:d-flex md:align-items-center md:justify-content-between">
    <div class="min-w-0 d-flex-grow-1">
        <div class="d-flex align-items-center space-x-3">
            <a href="{{ route('clients.index') }}" class="text-muted hover:text-muted">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="fs-2 fw-bold leading-7 text-dark sm:truncate sm:fs-1 sm:tracking-tight">
                Novo Cliente
            </h2>
        </div>
        <p class="mt-1 fs-6 text-muted">
            Cadastre uma nova empresa cliente
        </p>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <form action="{{ route('clients.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Informações da Empresa -->
        <div class="bg-white shadow-sm rounded">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="fs-4 fw-medium text-dark mb-4">Informações da Empresa</h3>
                
                <div class="grid row-cols-1 gap-6 sm:row-cols-2">
                    <!-- Nome da Empresa -->
                    <div class="sm:col-span-2">
                        <label for="company_name" class="block fs-6 fw-medium text-dark">Nome da Empresa *</label>
                        <div class="mt-1">
                            <input type="text" name="company_name" id="company_name" required
                                class="block w-full rounded border-light shadow-sm-sm focus:border-brand-500 focus:ring-brand-500 sm:fs-6 @error('company_name') border-red-500 @enderror"
                                value="{{ old('company_name') }}"
                                placeholder="Nome completo da empresa">
                        </div>
                        @error('company_name')
                            <p class="mt-2 fs-6 text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nome Fantasia -->
                    <div>
                        <label for="trade_name" class="block fs-6 fw-medium text-dark">Nome Fantasia</label>
                        <div class="mt-1">
                            <input type="text" name="trade_name" id="trade_name"
                                class="block w-full rounded border-light shadow-sm-sm focus:border-brand-500 focus:ring-brand-500 sm:fs-6 @error('trade_name') border-red-500 @enderror"
                                value="{{ old('trade_name') }}"
                                placeholder="Nome fantasia (se houver)">
                        </div>
                        @error('trade_name')
                            <p class="mt-2 fs-6 text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- CNPJ -->
                    <div>
                        <label for="cnpj" class="block fs-6 fw-medium text-dark">CNPJ *</label>
                        <div class="mt-1">
                            <input type="text" name="cnpj" id="cnpj" required maxlength="18"
                                class="block w-full rounded border-light shadow-sm-sm focus:border-brand-500 focus:ring-brand-500 sm:fs-6 @error('cnpj') border-red-500 @enderror"
                                value="{{ old('cnpj') }}"
                                placeholder="00.000.000/0000-00">
                        </div>
                        @error('cnpj')
                            <p class="mt-2 fs-6 text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block fs-6 fw-medium text-dark">Email</label>
                        <div class="mt-1">
                            <input type="email" name="email" id="email"
                                class="block w-full rounded border-light shadow-sm-sm focus:border-brand-500 focus:ring-brand-500 sm:fs-6 @error('email') border-red-500 @enderror"
                                value="{{ old('email') }}"
                                placeholder="email@empresa.com">
                        </div>
                        @error('email')
                            <p class="mt-2 fs-6 text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Telefone -->
                    <div>
                        <label for="phone" class="block fs-6 fw-medium text-dark">Telefone</label>
                        <div class="mt-1">
                            <input type="tel" name="phone" id="phone"
                                class="block w-full rounded border-light shadow-sm-sm focus:border-brand-500 focus:ring-brand-500 sm:fs-6 @error('phone') border-red-500 @enderror"
                                value="{{ old('phone') }}"
                                placeholder="(11) 99999-9999">
                        </div>
                        @error('phone')
                            <p class="mt-2 fs-6 text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Endereço -->
                    <div class="sm:col-span-2">
                        <label for="address" class="block fs-6 fw-medium text-dark">Endereço</label>
                        <div class="mt-1">
                            <textarea name="address" id="address" rows="3"
                                class="block w-full rounded border-light shadow-sm-sm focus:border-brand-500 focus:ring-brand-500 sm:fs-6 @error('address') border-red-500 @enderror"
                                placeholder="Endereço completo da empresa">{{ old('address') }}</textarea>
                        </div>
                        @error('address')
                            <p class="mt-2 fs-6 text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Observações -->
                    <div class="sm:col-span-2">
                        <label for="notes" class="block fs-6 fw-medium text-dark">Observações</label>
                        <div class="mt-1">
                            <textarea name="notes" id="notes" rows="3"
                                class="block w-full rounded border-light shadow-sm-sm focus:border-brand-500 focus:ring-brand-500 sm:fs-6 @error('notes') border-red-500 @enderror"
                                placeholder="Informações adicionais sobre a empresa">{{ old('notes') }}</textarea>
                        </div>
                        @error('notes')
                            <p class="mt-2 fs-6 text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="sm:col-span-2">
                        <div class="d-flex align-items-center">
                            <input type="checkbox" id="is_active" name="is_active" value="1" 
                                class="h-4 w-4 text-primary focus:ring-brand-500 border-light rounded @error('is_active') border-red-500 @enderror"
                                {{ old('is_active', true) ? 'checked' : '' }}>
                            <label for="is_active" class="ml-2 block fs-6 text-dark">
                                Cliente ativo
                            </label>
                        </div>
                        @error('is_active')
                            <p class="mt-2 fs-6 text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Contatos -->
        <div class="bg-white shadow-sm rounded">
            <div class="px-4 py-5 sm:p-6">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h3 class="fs-4 fw-medium text-dark">Contatos</h3>
                    <button type="button" onclick="addContact()" 
                        class="inline-d-flex align-items-center px-3 py-2 border border-transparent fs-6 fw-medium rounded text-brand-700 bg-brand-100 hover:bg-brand-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Adicionar Contato
                    </button>
                </div>
                
                <div id="contacts-container" class="space-y-4">
                    <!-- Contato Principal -->
                    <div class="contact-item border border-light rounded p-4 bg-light">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h4 class="fs-6 fw-medium text-dark">Contato Principal</h4>
                            <span class="inline-d-flex align-items-center px-2.5 py-0.5 rounded-circle fs-6 fw-medium bg-brand-100 text-brand-800">
                                Principal
                            </span>
                        </div>
                        
                        <div class="grid row-cols-1 gap-4 sm:row-cols-2">
                            <div>
                                <label class="block fs-6 fw-medium text-dark">Nome *</label>
                                <input type="text" name="contacts[0][name]" required
                                    class="mt-1 block w-full rounded border-light shadow-sm-sm focus:border-brand-500 focus:ring-brand-500 sm:fs-6"
                                    value="{{ old('contacts.0.name') }}"
                                    placeholder="Nome completo do contato">
                            </div>
                            
                            <div>
                                <label class="block fs-6 fw-medium text-dark">Email *</label>
                                <input type="email" name="contacts[0][email]" required
                                    class="mt-1 block w-full rounded border-light shadow-sm-sm focus:border-brand-500 focus:ring-brand-500 sm:fs-6"
                                    value="{{ old('contacts.0.email') }}"
                                    placeholder="email@contato.com">
                            </div>
                            
                            <div>
                                <label class="block fs-6 fw-medium text-dark">Telefone</label>
                                <input type="tel" name="contacts[0][phone]"
                                    class="mt-1 block w-full rounded border-light shadow-sm-sm focus:border-brand-500 focus:ring-brand-500 sm:fs-6"
                                    value="{{ old('contacts.0.phone') }}"
                                    placeholder="(11) 99999-9999">
                            </div>
                            
                            <div>
                                <label class="block fs-6 fw-medium text-dark">Cargo</label>
                                <input type="text" name="contacts[0][position]"
                                    class="mt-1 block w-full rounded border-light shadow-sm-sm focus:border-brand-500 focus:ring-brand-500 sm:fs-6"
                                    value="{{ old('contacts.0.position') }}"
                                    placeholder="Cargo/função">
                            </div>
                            
                            <div>
                                <label class="block fs-6 fw-medium text-dark">Departamento</label>
                                <input type="text" name="contacts[0][department]"
                                    class="mt-1 block w-full rounded border-light shadow-sm-sm focus:border-brand-500 focus:ring-brand-500 sm:fs-6"
                                    value="{{ old('contacts.0.department') }}"
                                    placeholder="Departamento">
                            </div>
                            
                            <div class="d-flex align-items-center">
                                <input type="checkbox" name="contacts[0][is_primary]" value="1" checked
                                    class="h-4 w-4 text-primary focus:ring-brand-500 border-light rounded">
                                <label class="ml-2 block fs-6 text-dark">Contato principal</label>
                            </div>
                        </div>
                        
                        <input type="hidden" name="contacts[0][is_primary]" value="1">
                    </div>
                </div>
            </div>
        </div>

        <!-- Botões de Ação -->
        <div class="d-flex justify-content-end space-x-3">
            <a href="{{ route('clients.index') }}" 
                class="inline-d-flex align-items-center px-4 py-2 border border-light shadow-sm-sm fs-6 fw-medium rounded text-dark bg-white hover:bg-light focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Cancelar
            </a>
            
            <button type="submit" 
                class="inline-d-flex align-items-center px-4 py-2 border border-transparent shadow-sm-sm fs-6 fw-medium rounded text-white bg-primary hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Criar Cliente
            </button>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
    let contactIndex = 1;

    function addContact() {
        const container = document.getElementById('contacts-container');
        const contactDiv = document.createElement('div');
        contactDiv.className = 'contact-item border border-gray-200 rounded-lg p-4 bg-white';
        contactDiv.innerHTML = `
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h4 class="fs-6 fw-medium text-dark">Contato Adicional</h4>
                <button type="button" onclick="removeContact(this)" 
                    class="inline-d-flex align-items-center px-2 py-1 border border-transparent fs-6 fw-medium rounded text-red-700 bg-danger bg-opacity-10 hover:bg-red-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Remover
                </button>
            </div>
            
            <div class="grid row-cols-1 gap-4 sm:row-cols-2">
                <div>
                    <label class="block fs-6 fw-medium text-dark">Nome *</label>
                    <input type="text" name="contacts[${contactIndex}][name]" required
                        class="mt-1 block w-full rounded border-light shadow-sm-sm focus:border-brand-500 focus:ring-brand-500 sm:fs-6"
                        placeholder="Nome completo do contato">
                </div>
                
                <div>
                    <label class="block fs-6 fw-medium text-dark">Email *</label>
                    <input type="email" name="contacts[${contactIndex}][email]" required
                        class="mt-1 block w-full rounded border-light shadow-sm-sm focus:border-brand-500 focus:ring-brand-500 sm:fs-6"
                        placeholder="email@contato.com">
                </div>
                
                <div>
                    <label class="block fs-6 fw-medium text-dark">Telefone</label>
                    <input type="tel" name="contacts[${contactIndex}][phone]"
                        class="mt-1 block w-full rounded border-light shadow-sm-sm focus:border-brand-500 focus:ring-brand-500 sm:fs-6"
                        placeholder="(11) 99999-9999">
                </div>
                
                <div>
                    <label class="block fs-6 fw-medium text-dark">Cargo</label>
                    <input type="text" name="contacts[${contactIndex}][position]"
                        class="mt-1 block w-full rounded border-light shadow-sm-sm focus:border-brand-500 focus:ring-brand-500 sm:fs-6"
                        placeholder="Cargo/função">
                </div>
                
                <div>
                    <label class="block fs-6 fw-medium text-dark">Departamento</label>
                    <input type="text" name="contacts[${contactIndex}][department]"
                        class="mt-1 block w-full rounded border-light shadow-sm-sm focus:border-brand-500 focus:ring-brand-500 sm:fs-6"
                        placeholder="Departamento">
                </div>
                
                <div class="d-flex align-items-center">
                    <input type="checkbox" name="contacts[${contactIndex}][is_primary]" value="1"
                        class="h-4 w-4 text-primary focus:ring-brand-500 border-light rounded">
                    <label class="ml-2 block fs-6 text-dark">Contato principal</label>
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

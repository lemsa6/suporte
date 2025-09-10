@extends('layouts.app')

@section('title', 'Novo Cliente')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <div class="flex items-center mb-2">
            <x-button variant="outline" size="sm" tag="a" href="{{ route('clients.index') }}" class="mr-3">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Voltar
            </x-button>
            <h1 class="page-title mb-0">Novo Cliente</h1>
        </div>
        <p class="text-cinza-claro">
            Cadastre uma nova empresa cliente
        </p>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <form action="{{ route('clients.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Informações da Empresa -->
        <h2 class="section-title mb-4">Informações da Empresa</h2>
        <x-card>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nome da Empresa -->
                <div class="md:col-span-2">
                    <x-input 
                        label="Nome da Empresa"
                        name="company_name"
                        value="{{ old('company_name') }}"
                        placeholder="Nome completo da empresa"
                        required
                        error="{{ $errors->first('company_name') }}"
                    />
                </div>

                <!-- Nome Fantasia e CNPJ -->
                <div>
                    <x-input 
                        label="Nome Fantasia"
                        name="trade_name"
                        value="{{ old('trade_name') }}"
                        placeholder="Nome fantasia (se houver)"
                        error="{{ $errors->first('trade_name') }}"
                    />
                </div>

                <div>
                    <x-input 
                        label="CNPJ"
                        name="cnpj"
                        value="{{ old('cnpj') }}"
                        placeholder="00.000.000/0000-00"
                        maxlength="18"
                        required
                        error="{{ $errors->first('cnpj') }}"
                    />
                </div>

                <!-- Email e Telefone -->
                <div>
                    <x-input 
                        label="Email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        placeholder="email@empresa.com"
                        error="{{ $errors->first('email') }}"
                    />
                </div>

                <div>
                    <x-input 
                        label="Telefone"
                        name="phone"
                        type="tel"
                        value="{{ old('phone') }}"
                        placeholder="(11) 99999-9999"
                        error="{{ $errors->first('phone') }}"
                    />
                </div>

                <!-- Endereço -->
                <div class="md:col-span-2">
                    <x-textarea 
                        label="Endereço"
                        name="address"
                        rows="3"
                        placeholder="Endereço completo da empresa"
                        error="{{ $errors->first('address') }}"
                    >
                        {{ old('address') }}
                    </x-textarea>
                </div>

                <!-- Observações -->
                <div class="md:col-span-2">
                    <x-textarea 
                        label="Observações"
                        name="notes"
                        rows="3"
                        placeholder="Informações adicionais sobre a empresa"
                        error="{{ $errors->first('notes') }}"
                    >
                        {{ old('notes') }}
                    </x-textarea>
                </div>

                <!-- Status -->
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <div class="flex items-center h-5">
                            <input type="checkbox" id="is_active" name="is_active" value="1" 
                                   class="h-4 w-4 text-roxo focus:ring-roxo border-cinza-claro rounded"
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_active" class="font-medium text-cinza">
                                Cliente ativo
                            </label>
                        </div>
                    </div>
                    @error('is_active')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </x-card>

        <!-- Contatos -->
        <x-card>
            <div class="flex items-center justify-between mb-6">
                <h2 class="section-title mb-0">Contatos</h2>
                <x-button variant="outline" size="sm" type="button" onclick="addContact()">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Adicionar Contato
                </x-button>
            </div>
            
            <div id="contacts-container" class="space-y-4">
                <!-- Contato Principal -->
                <div class="contact-item border border-cinza-claro rounded-lg p-4 bg-cinza-claro-2">
                    <div class="flex items-center justify-between mb-4">
                        <h6 class="font-medium text-cinza">Contato Principal</h6>
                        <x-badge variant="primary">Principal</x-badge>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input 
                                label="Nome"
                                name="contacts[0][name]"
                                value="{{ old('contacts.0.name') }}"
                                placeholder="Nome completo do contato"
                                required
                            />
                        </div>
                        
                        <div>
                            <x-input 
                                label="Email"
                                name="contacts[0][email]"
                                type="email"
                                value="{{ old('contacts.0.email') }}"
                                placeholder="email@contato.com"
                                required
                            />
                        </div>
                        
                        <div>
                            <x-input 
                                label="Telefone"
                                name="contacts[0][phone]"
                                type="tel"
                                value="{{ old('contacts.0.phone') }}"
                                placeholder="(11) 99999-9999"
                            />
                        </div>
                        
                        <div>
                            <x-input 
                                label="Cargo"
                                name="contacts[0][position]"
                                value="{{ old('contacts.0.position') }}"
                                placeholder="Cargo/função"
                            />
                        </div>
                        
                        <div>
                            <x-input 
                                label="Departamento"
                                name="contacts[0][department]"
                                value="{{ old('contacts.0.department') }}"
                                placeholder="Departamento"
                            />
                        </div>
                        
                        <div class="flex items-center">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="contacts[0][is_primary]" value="1" checked
                                       class="h-4 w-4 text-roxo focus:ring-roxo border-cinza-claro rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label class="font-medium text-cinza">Contato principal</label>
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" name="contacts[0][is_primary]" value="1">
                </div>
            </div>
        </x-card>

        <!-- Botões de Ação -->
        <div class="flex justify-end gap-3">
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

@endsection

@push('scripts')
<script>
    let contactIndex = 1;

    function addContact() {
        const container = document.getElementById('contacts-container');
        const contactDiv = document.createElement('div');
        contactDiv.className = 'contact-item border border-cinza-claro rounded-lg p-4 bg-white';
        contactDiv.innerHTML = `
            <div class="flex items-center justify-between mb-4">
                <h6 class="font-medium text-cinza">Contato Adicional</h6>
                <x-button variant="outline" size="sm" type="button" onclick="removeContact(this)">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Remover
                </x-button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-cinza mb-2">Nome *</label>
                    <input type="text" name="contacts[${contactIndex}][name]" required
                        class="w-full px-3 py-2 border border-cinza-claro rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent"
                        placeholder="Nome completo do contato">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-cinza mb-2">Email *</label>
                    <input type="email" name="contacts[${contactIndex}][email]" required
                        class="w-full px-3 py-2 border border-cinza-claro rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent"
                        placeholder="email@contato.com">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-cinza mb-2">Telefone</label>
                    <input type="tel" name="contacts[${contactIndex}][phone]"
                        class="w-full px-3 py-2 border border-cinza-claro rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent"
                        placeholder="(11) 99999-9999">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-cinza mb-2">Cargo</label>
                    <input type="text" name="contacts[${contactIndex}][position]"
                        class="w-full px-3 py-2 border border-cinza-claro rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent"
                        placeholder="Cargo/função">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-cinza mb-2">Departamento</label>
                    <input type="text" name="contacts[${contactIndex}][department]"
                        class="w-full px-3 py-2 border border-cinza-claro rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent"
                        placeholder="Departamento">
                </div>
                
                <div class="flex items-center">
                    <div class="flex items-center h-5">
                        <input type="checkbox" name="contacts[${contactIndex}][is_primary]" value="1"
                            class="h-4 w-4 text-roxo focus:ring-roxo border-cinza-claro rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label class="font-medium text-cinza">Contato principal</label>
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
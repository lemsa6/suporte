@extends('layouts.app')

@section('title', 'Editar Cliente - ' . $client->company_name)

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <div class="flex items-center">
            <x-button variant="outline" size="sm" tag="a" href="{{ route('clients.show', $client) }}" class="mr-3">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Voltar
            </x-button>
            <div>
                <h1 class="page-title mb-0">Editar Cliente</h1>
                <p class="text-cinza-claro mb-0">{{ $client->company_name }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <form action="{{ route('clients.update', $client) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Informações da Empresa -->
        <h2 class="section-title mb-4">Informações da Empresa</h2>
        <x-card>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nome da Empresa -->
                <div class="md:col-span-2">
                    <x-input 
                        label="Nome da Empresa"
                        name="company_name"
                        value="{{ old('company_name', $client->company_name) }}"
                        placeholder="Nome completo da empresa"
                        required
                        error="{{ $errors->first('company_name') }}"
                    />
                </div>

                <!-- Nome Fantasia -->
                <div>
                    <x-input 
                        label="Nome Fantasia"
                        name="trade_name"
                        value="{{ old('trade_name', $client->trade_name) }}"
                        placeholder="Nome fantasia (se houver)"
                        error="{{ $errors->first('trade_name') }}"
                    />
                </div>

                <!-- CNPJ -->
                <div>
                    <x-input 
                        label="CNPJ"
                        name="cnpj"
                        value="{{ old('cnpj', $client->cnpj) }}"
                        placeholder="00.000.000/0000-00"
                        maxlength="18"
                        required
                        error="{{ $errors->first('cnpj') }}"
                    />
                </div>

                <!-- Email -->
                <div>
                    <x-input 
                        label="Email"
                        name="email"
                        type="email"
                        value="{{ old('email', $client->email) }}"
                        placeholder="email@empresa.com"
                        error="{{ $errors->first('email') }}"
                    />
                </div>

                <!-- Telefone -->
                <div>
                    <x-input 
                        label="Telefone"
                        name="phone"
                        type="tel"
                        value="{{ old('phone', $client->phone) }}"
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
                        {{ old('address', $client->address) }}
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
                        {{ old('notes', $client->notes) }}
                    </x-textarea>
                </div>

                <!-- Status -->
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <div class="flex items-center h-5">
                            <input type="checkbox" id="is_active" name="is_active" value="1" 
                                   class="h-4 w-4 text-roxo focus:ring-roxo border-cinza-claro rounded"
                                   {{ old('is_active', $client->is_active) ? 'checked' : '' }}>
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

        <!-- Contatos Existentes -->
        <x-card>
            <div class="flex items-center justify-between mb-6">
                <h2 class="section-title mb-0">Contatos Existentes</h2>
                <x-button variant="primary" size="sm" type="button" onclick="showAddContactModal()">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Adicionar Contato
                </x-button>
            </div>
            
            @if($client->contacts->count() > 0)
                <div id="contacts-container" class="space-y-4">
                    @foreach($client->contacts as $index => $contact)
                        <div class="contact-item border border-cinza-claro rounded-lg p-4 {{ $contact->is_primary ? 'bg-cinza-claro-2' : 'bg-white' }}">
                            <div class="flex items-center justify-between mb-4">
                                <h6 class="font-medium text-cinza">
                                    @if($contact->is_primary)
                                        Contato Principal
                                    @else
                                        Contato {{ $index + 1 }}
                                    @endif
                                </h6>
                                <div class="flex items-center gap-2">
                                    @if($contact->is_primary)
                                        <x-badge variant="primary">Principal</x-badge>
                                    @endif
                                    @if(!$contact->is_primary)
                                        <x-button variant="outline" size="sm" type="button" onclick="removeContact({{ $contact->id }})">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Remover
                                        </x-button>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input 
                                        label="Nome"
                                        name="contacts[{{ $index }}][name]"
                                        value="{{ $contact->name }}"
                                        placeholder="Nome completo do contato"
                                        required
                                    />
                                </div>
                                
                                <div>
                                    <x-input 
                                        label="Email"
                                        name="contacts[{{ $index }}][email]"
                                        type="email"
                                        value="{{ $contact->email }}"
                                        placeholder="email@contato.com"
                                        required
                                    />
                                </div>
                                
                                <div>
                                    <x-input 
                                        label="Telefone"
                                        name="contacts[{{ $index }}][phone]"
                                        type="tel"
                                        value="{{ $contact->phone }}"
                                        placeholder="(11) 99999-9999"
                                    />
                                </div>
                                
                                <div>
                                    <x-input 
                                        label="Cargo"
                                        name="contacts[{{ $index }}][position]"
                                        value="{{ $contact->position }}"
                                        placeholder="Cargo/função"
                                    />
                                </div>
                                
                                <div>
                                    <x-input 
                                        label="Departamento"
                                        name="contacts[{{ $index }}][department]"
                                        value="{{ $contact->department }}"
                                        placeholder="Departamento"
                                    />
                                </div>
                                
                                <div class="flex items-center">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" name="contacts[{{ $index }}][is_primary]" value="1" 
                                               class="h-4 w-4 text-roxo focus:ring-roxo border-cinza-claro rounded"
                                               {{ $contact->is_primary ? 'checked' : '' }}
                                               onchange="updatePrimaryContact(this, {{ $index }})">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label class="font-medium text-cinza">Contato principal</label>
                                    </div>
                                </div>
                            </div>
                            
                            <input type="hidden" name="contacts[{{ $index }}][id]" value="{{ $contact->id }}">
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto mb-4 text-cinza-claro" width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h5 class="text-lg font-medium text-cinza mb-2">Nenhum contato cadastrado</h5>
                    <p class="text-cinza-claro mb-0">Adicione pelo menos um contato para esta empresa.</p>
                </div>
            @endif
        </x-card>

        <!-- Botões de Ação -->
        <div class="flex justify-end gap-3">
            <x-button variant="outline" tag="a" href="{{ route('clients.show', $client) }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Cancelar
            </x-button>
            
            <x-button variant="primary" type="submit">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Atualizar Cliente
            </x-button>
        </div>
    </form>
</div>

<!-- Modal para adicionar novo contato -->
<div id="add-contact-modal" class="fixed inset-0 bg-cinza-escuro bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-4">
            <h5 class="text-lg font-semibold text-cinza">Adicionar Novo Contato</h5>
            <button type="button" onclick="closeAddContactModal()" class="text-cinza-claro hover:text-cinza">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form id="new-contact-form" class="space-y-4">
            <div>
                <label for="new-contact-name" class="block text-sm font-medium text-cinza mb-2">Nome *</label>
                <input type="text" id="new-contact-name" name="name" required 
                       class="w-full px-3 py-2 border border-cinza-claro rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent">
            </div>
            
            <div>
                <label for="new-contact-email" class="block text-sm font-medium text-cinza mb-2">Email *</label>
                <input type="email" id="new-contact-email" name="email" required 
                       class="w-full px-3 py-2 border border-cinza-claro rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent">
            </div>
            
            <div>
                <label for="new-contact-phone" class="block text-sm font-medium text-cinza mb-2">Telefone</label>
                <input type="tel" id="new-contact-phone" name="phone" 
                       class="w-full px-3 py-2 border border-cinza-claro rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent">
            </div>
            
            <div>
                <label for="new-contact-position" class="block text-sm font-medium text-cinza mb-2">Cargo</label>
                <input type="text" id="new-contact-position" name="position" 
                       class="w-full px-3 py-2 border border-cinza-claro rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent">
            </div>
            
            <div>
                <label for="new-contact-department" class="block text-sm font-medium text-cinza mb-2">Departamento</label>
                <input type="text" id="new-contact-department" name="department" 
                       class="w-full px-3 py-2 border border-cinza-claro rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent">
            </div>
            
            <div class="flex items-center">
                <div class="flex items-center h-5">
                    <input type="checkbox" id="new-contact-is-primary" name="is_primary" value="1" 
                           class="h-4 w-4 text-roxo focus:ring-roxo border-cinza-claro rounded">
                </div>
                <div class="ml-3 text-sm">
                    <label for="new-contact-is-primary" class="font-medium text-cinza">Contato principal</label>
                </div>
            </div>
        </form>
        
        <div class="flex justify-end gap-3 mt-6">
            <x-button variant="outline" type="button" onclick="closeAddContactModal()">Cancelar</x-button>
            <x-button variant="primary" type="button" onclick="addNewContact()">Adicionar</x-button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let contactIndex = {{ $client->contacts->count() }};

    function showAddContactModal() {
        document.getElementById('add-contact-modal').classList.remove('hidden');
        document.getElementById('add-contact-modal').classList.add('flex');
    }

    function closeAddContactModal() {
        document.getElementById('add-contact-modal').classList.add('hidden');
        document.getElementById('add-contact-modal').classList.remove('flex');
    }

    function addNewContact() {
        const form = document.getElementById('new-contact-form');
        const formData = new FormData(form);
        
        // Criar novo contato dinamicamente
        const contactDiv = document.createElement('div');
        contactDiv.className = 'contact-item border border-cinza-claro rounded-lg p-4 bg-white';
        contactDiv.innerHTML = `
            <div class="flex items-center justify-between mb-4">
                <h6 class="font-medium text-cinza">Novo Contato</h6>
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
                        value="${formData.get('name')}"
                        placeholder="Nome completo do contato">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-cinza mb-2">Email *</label>
                    <input type="email" name="contacts[${contactIndex}][email]" required
                        class="w-full px-3 py-2 border border-cinza-claro rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent"
                        value="${formData.get('email')}"
                        placeholder="email@contato.com">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-cinza mb-2">Telefone</label>
                    <input type="tel" name="contacts[${contactIndex}][phone]"
                        class="w-full px-3 py-2 border border-cinza-claro rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent"
                        value="${formData.get('phone')}"
                        placeholder="(11) 99999-9999">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-cinza mb-2">Cargo</label>
                    <input type="text" name="contacts[${contactIndex}][position]"
                        class="w-full px-3 py-2 border border-cinza-claro rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent"
                        value="${formData.get('position')}"
                        placeholder="Cargo/função">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-cinza mb-2">Departamento</label>
                    <input type="text" name="contacts[${contactIndex}][department]"
                        class="w-full px-3 py-2 border border-cinza-claro rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent"
                        value="${formData.get('department')}"
                        placeholder="Departamento">
                </div>
                
                <div class="flex items-center">
                    <div class="flex items-center h-5">
                        <input type="checkbox" name="contacts[${contactIndex}][is_primary]" value="1"
                            class="h-4 w-4 text-roxo focus:ring-roxo border-cinza-claro rounded"
                            ${formData.get('is_primary') ? 'checked' : ''}
                            onchange="updatePrimaryContact(this, ${contactIndex})">
                    </div>
                    <div class="ml-3 text-sm">
                        <label class="font-medium text-cinza">Contato principal</label>
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
            const contactsSection = document.querySelector('.p-6');
            const newContainer = document.createElement('div');
            newContainer.id = 'contacts-container';
            newContainer.className = 'space-y-4';
            newContainer.appendChild(contactDiv);
            contactsSection.appendChild(newContainer);
        }
        
        contactIndex++;
        
        // Fechar modal
        closeAddContactModal();
        
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
@extends('layouts.app')

@section('title', 'Editar Contato')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <div class="flex items-center mb-2">
            <x-button variant="outline" size="sm" tag="a" href="{{ route('clients.show', $client) }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Voltar
            </x-button>
        </div>
        <span class="text-sm text-cinza-claro">Cliente: {{ $client->trade_name ?? $client->company_name }}</span>
        <h1 class="page-title mt-1">Editar Contato</h1>
        <p class="text-cinza mt-2">Atualize as informações do contato</p>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <x-card title="Informações do Contato">
        <form action="{{ route('clients.contacts.update', ['client' => $client, 'contact' => $contact]) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nome Completo -->
                <div>
                    <label for="name" class="block text-sm font-medium text-cinza mb-2">Nome Completo *</label>
                    <input type="text" id="name" name="name" required 
                        class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent @error('name') border-vermelho @enderror"
                        value="{{ old('name', $contact->name) }}">
                    @error('name')
                        <div class="text-vermelho text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-cinza mb-2">Email *</label>
                    <input type="email" id="email" name="email" required 
                        class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent @error('email') border-vermelho @enderror"
                        value="{{ old('email', $contact->email) }}">
                    @error('email')
                        <div class="text-vermelho text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Telefone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-cinza mb-2">Telefone</label>
                    <input type="tel" id="phone" name="phone" 
                        class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent @error('phone') border-vermelho @enderror"
                        value="{{ old('phone', $contact->phone) }}"
                        placeholder="(11) 99999-9999">
                    @error('phone')
                        <div class="text-vermelho text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Cargo -->
                <div>
                    <label for="position" class="block text-sm font-medium text-cinza mb-2">Cargo</label>
                    <input type="text" id="position" name="position" 
                        class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent @error('position') border-vermelho @enderror"
                        value="{{ old('position', $contact->position) }}"
                        placeholder="Ex: Gerente, Diretor, etc.">
                    @error('position')
                        <div class="text-vermelho text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Departamento -->
                <div>
                    <label for="department" class="block text-sm font-medium text-cinza mb-2">Departamento</label>
                    <input type="text" id="department" name="department" 
                        class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent @error('department') border-vermelho @enderror"
                        value="{{ old('department', $contact->department) }}"
                        placeholder="Ex: TI, Vendas, Suporte, etc.">
                    @error('department')
                        <div class="text-vermelho text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tipo de Usuário -->
                <div>
                    <label for="user_type" class="block text-sm font-medium text-cinza mb-2">Tipo de Usuário *</label>
                    <select id="user_type" name="user_type" required
                        class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent @error('user_type') border-vermelho @enderror">
                        <option value="">Selecione o tipo</option>
                        <option value="cliente_funcionario" {{ old('user_type', $contact->user_type) == 'cliente_funcionario' ? 'selected' : '' }}>Funcionário</option>
                        <option value="cliente_gestor" {{ old('user_type', $contact->user_type) == 'cliente_gestor' ? 'selected' : '' }}>Gestor</option>
                    </select>
                    @error('user_type')
                        <div class="text-vermelho text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Observações -->
            <div>
                <label for="notes" class="block text-sm font-medium text-cinza mb-2">Observações</label>
                <textarea id="notes" name="notes" rows="3" 
                    class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent @error('notes') border-vermelho @enderror"
                    placeholder="Informações adicionais sobre o contato...">{{ old('notes', $contact->notes) }}</textarea>
                @error('notes')
                    <div class="text-vermelho text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Configurações -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-cinza">Configurações</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Status -->
                    <div class="flex items-center">
                        <input type="checkbox" id="is_active" name="is_active" value="1" 
                            class="w-4 h-4 text-roxo border-cinza-claro-2 rounded focus:ring-roxo" 
                            {{ old('is_active', $contact->is_active) ? 'checked' : '' }}>
                        <label for="is_active" class="ml-2 text-sm text-cinza">Contato ativo</label>
                    </div>

                    <!-- Receber Notificações -->
                    <div class="flex items-center">
                        <input type="checkbox" id="receive_notifications" name="receive_notifications" value="1" 
                            class="w-4 h-4 text-roxo border-cinza-claro-2 rounded focus:ring-roxo" 
                            {{ old('receive_notifications', $contact->receive_notifications) ? 'checked' : '' }}>
                        <label for="receive_notifications" class="ml-2 text-sm text-cinza">Receber notificações</label>
                    </div>

                    <!-- Contato Principal -->
                    <div class="flex items-center">
                        <input type="checkbox" id="is_primary" name="is_primary" value="1" 
                            class="w-4 h-4 text-roxo border-cinza-claro-2 rounded focus:ring-roxo" 
                            {{ old('is_primary', $contact->is_primary) ? 'checked' : '' }}>
                        <label for="is_primary" class="ml-2 text-sm text-cinza">Contato principal</label>
                    </div>

                    <!-- Acesso ao Sistema -->
                    <div class="flex items-center">
                        <input type="checkbox" id="has_system_access" name="has_system_access" value="1" 
                            class="w-4 h-4 text-roxo border-cinza-claro-2 rounded focus:ring-roxo" 
                            {{ old('has_system_access', $contact->has_system_access) ? 'checked' : '' }}>
                        <label for="has_system_access" class="ml-2 text-sm text-cinza">Acesso ao sistema</label>
                    </div>
                </div>
            </div>

            <!-- Botões -->
            <div class="flex justify-end gap-3 pt-6 border-t border-cinza-claro-2">
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
                    Salvar Alterações
                </x-button>
            </div>
        </form>
    </x-card>

    <!-- Informações Adicionais -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <x-card title="Informações do Sistema">
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-sm text-cinza-claro">Criado em:</span>
                    <span class="text-sm text-cinza">{{ $contact->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-cinza-claro">Última atualização:</span>
                    <span class="text-sm text-cinza">{{ $contact->updated_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-cinza-claro">ID do contato:</span>
                    <span class="text-sm text-cinza">#{{ $contact->id }}</span>
                </div>
            </div>
        </x-card>

        <x-card title="Ações Rápidas">
            <div class="space-y-2">
                <x-button variant="outline" size="sm" tag="a" href="mailto:{{ $contact->email }}">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Enviar Email
                </x-button>
                
                @if($contact->phone)
                    <x-button variant="outline" size="sm" tag="a" href="tel:{{ $contact->phone }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        Ligar
                    </x-button>
                @endif
                
                <x-button variant="outline" size="sm" onclick="duplicateContact()">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    Duplicar
                </x-button>
            </div>
        </x-card>
    </div>
</div>

<script>
function duplicateContact() {
    if (confirm('Tem certeza que deseja duplicar este contato?')) {
        // Implementar duplicação do contato
        console.log('Duplicando contato:', {{ $contact->id }});
    }
}
</script>
@endsection
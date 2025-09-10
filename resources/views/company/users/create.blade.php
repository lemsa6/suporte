@extends('layouts.app')

@section('title', 'Adicionar Usuário')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <span class="text-sm text-cinza-claro">Empresa</span>
        <h1 class="page-title mt-1">Adicionar Usuário</h1>
        <p class="text-cinza mt-2">Cadastre um novo usuário para sua empresa</p>
    </div>
    <x-button variant="outline" tag="a" href="{{ route('company.users.index') }}">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Voltar
    </x-button>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <form id="createUserForm" method="POST" action="{{ route('company.users.store') }}" class="space-y-6">
        @csrf
        
        <!-- Informações Básicas -->
        <x-card title="Informações Básicas">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nome Completo -->
                <div>
                    <label for="name" class="block text-sm font-medium text-cinza mb-2">Nome Completo *</label>
                    <input type="text" id="name" name="name" required 
                        class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent @error('name') border-vermelho @enderror"
                        value="{{ old('name') }}">
                    @error('name')
                        <div class="text-vermelho text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-cinza mb-2">Email *</label>
                    <input type="email" id="email" name="email" required 
                        class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent @error('email') border-vermelho @enderror"
                        value="{{ old('email') }}">
                    @error('email')
                        <div class="text-vermelho text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Cargo -->
                <div>
                    <label for="role" class="block text-sm font-medium text-cinza mb-2">Cargo *</label>
                    <select id="role" name="role" required 
                        class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent @error('role') border-vermelho @enderror">
                        <option value="">Selecione um cargo</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                        <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Gerente</option>
                        <option value="technician" {{ old('role') == 'technician' ? 'selected' : '' }}>Técnico</option>
                        <option value="client" {{ old('role') == 'client' ? 'selected' : '' }}>Cliente</option>
                    </select>
                    @error('role')
                        <div class="text-vermelho text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Telefone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-cinza mb-2">Telefone</label>
                    <input type="tel" id="phone" name="phone" 
                        class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent @error('phone') border-vermelho @enderror"
                        value="{{ old('phone') }}"
                        placeholder="(11) 99999-9999">
                    @error('phone')
                        <div class="text-vermelho text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </x-card>

        <!-- Senha -->
        <x-card title="Senha">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nova Senha -->
                <div>
                    <label for="password" class="block text-sm font-medium text-cinza mb-2">Senha *</label>
                    <input type="password" id="password" name="password" required 
                        class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent @error('password') border-vermelho @enderror">
                    @error('password')
                        <div class="text-vermelho text-sm mt-1">{{ $message }}</div>
                    @enderror
                    <p class="text-xs text-cinza-claro mt-1">Mínimo de 8 caracteres</p>
                </div>

                <!-- Confirmar Senha -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-cinza mb-2">Confirmar Senha *</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required 
                        class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent @error('password_confirmation') border-vermelho @enderror">
                    @error('password_confirmation')
                        <div class="text-vermelho text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </x-card>

        <!-- Configurações Adicionais -->
        <x-card title="Configurações Adicionais">
            <div class="space-y-4">
                <!-- Status -->
                <div class="flex items-center">
                    <input type="checkbox" id="is_active" name="is_active" value="1" 
                        class="w-4 h-4 text-roxo border-cinza-claro-2 rounded focus:ring-roxo" 
                        {{ old('is_active', true) ? 'checked' : '' }}>
                    <label for="is_active" class="ml-2 text-sm text-cinza">Usuário ativo</label>
                </div>

                <!-- Enviar Email de Boas-vindas -->
                <div class="flex items-center">
                    <input type="checkbox" id="send_welcome_email" name="send_welcome_email" value="1" 
                        class="w-4 h-4 text-roxo border-cinza-claro-2 rounded focus:ring-roxo" 
                        {{ old('send_welcome_email', true) ? 'checked' : '' }}>
                    <label for="send_welcome_email" class="ml-2 text-sm text-cinza">Enviar email de boas-vindas</label>
                </div>

                <!-- Notificações por Email -->
                <div class="flex items-center">
                    <input type="checkbox" id="email_notifications" name="email_notifications" value="1" 
                        class="w-4 h-4 text-roxo border-cinza-claro-2 rounded focus:ring-roxo" 
                        {{ old('email_notifications', true) ? 'checked' : '' }}>
                    <label for="email_notifications" class="ml-2 text-sm text-cinza">Receber notificações por email</label>
                </div>
            </div>
        </x-card>

        <!-- Botões -->
        <div class="flex justify-end gap-3 pt-6 border-t border-cinza-claro-2">
            <x-button variant="outline" type="button" onclick="resetForm()">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Limpar
            </x-button>
            <x-button variant="primary" type="submit">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Criar Usuário
            </x-button>
        </div>
    </form>
</div>

<script>
function resetForm() {
    if (confirm('Tem certeza que deseja limpar o formulário?')) {
        document.getElementById('createUserForm').reset();
    }
}

// Validação de senha em tempo real
document.getElementById('password_confirmation').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmation = this.value;
    
    if (password !== confirmation) {
        this.setCustomValidity('As senhas não coincidem');
    } else {
        this.setCustomValidity('');
    }
});

document.getElementById('password').addEventListener('input', function() {
    const confirmation = document.getElementById('password_confirmation');
    if (confirmation.value) {
        confirmation.dispatchEvent(new Event('input'));
    }
});
</script>
@endsection
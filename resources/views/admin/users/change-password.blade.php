@extends('layouts.app')

@section('title', 'Alterar Senha - ' . $user->name)

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <div class="flex items-center">
            <x-button variant="outline" tag="a" href="{{ route('admin.users.index') }}" class="mr-3">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Voltar
            </x-button>
            <div>
                <span class="text-sm text-cinza-claro">Alterar Senha</span>
                <h1 class="page-title mt-1">{{ $user->name }}</h1>
                <p class="text-cinza mt-2">{{ $user->email }} - {{ ucfirst($user->role) }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <x-card>
        <div class="mb-6">
            <h2 class="section-title mb-2">Alteração de Senha pelo Administrador</h2>
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">
                            Atenção - Ação Administrativa Crítica
                        </h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Você está prestes a alterar a senha de outro usuário. Esta ação:</p>
                            <ul class="list-disc list-inside mt-1 space-y-1">
                                <li>Será registrada nos logs de auditoria</li>
                                <li>Requer confirmação explícita de ciência</li>
                                <li>Deve ter uma justificativa válida</li>
                                <li>É irreversível</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.users.password.update', $user) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Informações do Usuário -->
            <div class="bg-creme p-4 rounded-lg">
                <h3 class="font-medium text-cinza mb-3">Dados do Usuário</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-cinza-claro">Nome:</span>
                        <span class="font-medium text-cinza ml-2">{{ $user->name }}</span>
                    </div>
                    <div>
                        <span class="text-cinza-claro">Email:</span>
                        <span class="font-medium text-cinza ml-2">{{ $user->email }}</span>
                    </div>
                    <div>
                        <span class="text-cinza-claro">Perfil:</span>
                        <span class="font-medium text-cinza ml-2">{{ ucfirst($user->role) }}</span>
                    </div>
                    <div>
                        <span class="text-cinza-claro">Status:</span>
                        <span class="font-medium {{ $user->is_active ? 'text-green-600' : 'text-red-600' }} ml-2">
                            {{ $user->is_active ? 'Ativo' : 'Inativo' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Nova Senha -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="new_password" class="block text-sm font-medium text-cinza mb-2">
                        Nova Senha <span class="text-red-500">*</span>
                    </label>
                    <x-input 
                        type="password" 
                        id="new_password" 
                        name="new_password" 
                        required 
                        placeholder="Mínimo 8 caracteres"
                        autocomplete="new-password" />
                </div>

                <div>
                    <label for="new_password_confirmation" class="block text-sm font-medium text-cinza mb-2">
                        Confirmar Nova Senha <span class="text-red-500">*</span>
                    </label>
                    <x-input 
                        type="password" 
                        id="new_password_confirmation" 
                        name="new_password_confirmation" 
                        required 
                        placeholder="Repita a nova senha"
                        autocomplete="new-password" />
                </div>
            </div>

            <!-- Justificativa -->
            <div>
                <label for="reason" class="block text-sm font-medium text-cinza mb-2">
                    Justificativa/Motivo
                </label>
                <x-textarea 
                    id="reason" 
                    name="reason" 
                    rows="3" 
                    placeholder="Descreva o motivo da alteração da senha (ex: usuário esqueceu, solicitação via ticket, etc.)" />
                <p class="mt-1 text-sm text-cinza-claro">
                    Embora opcional, é recomendado informar o motivo para fins de auditoria.
                </p>
            </div>

            <!-- Confirmação do Admin -->
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input 
                            type="checkbox" 
                            id="admin_confirmation" 
                            name="admin_confirmation" 
                            value="1" 
                            required
                            class="h-4 w-4 text-red-600 focus:ring-red-500 border-red-300 rounded">
                    </div>
                    <div class="ml-3">
                        <label for="admin_confirmation" class="text-sm font-medium text-red-800">
                            Confirmação de Ciência <span class="text-red-600">*</span>
                        </label>
                        <p class="text-sm text-red-700 mt-1">
                            Eu, <strong>{{ auth()->user()->name }}</strong>, confirmo que estou ciente de que estou alterando a senha do usuário 
                            <strong>{{ $user->name }}</strong> e que esta ação será registrada nos logs de auditoria do sistema.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Botões -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-padrao">
                <x-button variant="outline" tag="a" href="{{ route('admin.users.index') }}">
                    Cancelar
                </x-button>
                <x-button variant="danger" type="submit">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m0 0v6a2 2 0 01-2 2H9a2 2 0 01-2-2V9a2 2 0 012-2m6 0V7a2 2 0 00-2-2H9a2 2 0 00-2-2v2m6 0V5a2 2 0 00-2-2H9a2 2 0 00-2-2v4"></path>
                    </svg>
                    Alterar Senha
                </x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    form.addEventListener('submit', function(e) {
        const confirmation = confirm(
            'Tem certeza que deseja alterar a senha do usuário {{ $user->name }}?\n\n' +
            'Esta ação será registrada nos logs de auditoria e não pode ser desfeita.'
        );
        
        if (!confirmation) {
            e.preventDefault();
            return false;
        }
        
        // Desabilitar botão para evitar duplo clique
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Alterando...';
    });
});
</script>
@endpush

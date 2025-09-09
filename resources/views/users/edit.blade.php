@extends('layouts.app')

@section('title', 'Editar Usuário')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <span class="text-sm text-gray-500">Usuários</span>
        <h1 class="page-title mt-1">Editar Usuário</h1>
        <p class="text-gray-600 mt-2">Atualize as informações do usuário</p>
    </div>
    <x-button variant="outline" tag="a" href="{{ route('settings.users') }}">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Voltar
    </x-button>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Informações Básicas -->
        <h2 class="section-title mb-4">Informações Básicas</h2>
        <x-card>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nome -->
                <x-input 
                    label="Nome Completo" 
                    name="name" 
                    value="{{ old('name', $user->name) }}"
                    placeholder="Digite o nome completo"
                    required
                    error="{{ $errors->first('name') }}"
                />

                <!-- Email -->
                <x-input 
                    label="E-mail" 
                    name="email" 
                    type="email"
                    value="{{ old('email', $user->email) }}"
                    placeholder="Digite o e-mail"
                    required
                    error="{{ $errors->first('email') }}"
                />

                <!-- Cargo -->
                <x-select 
                    label="Cargo" 
                    name="role"
                    :options="[
                        '' => 'Selecione o cargo',
                        'admin' => 'Administrador',
                        'tecnico' => 'Técnico',
                        'cliente_gestor' => 'Cliente Gestor'
                    ]"
                    :selected="old('role', $user->role)"
                    required
                    error="{{ $errors->first('role') }}"
                />

                <!-- Status -->
                <x-select 
                    label="Status" 
                    name="is_active"
                    :options="[
                        '1' => 'Ativo',
                        '0' => 'Inativo'
                    ]"
                    :selected="old('is_active', $user->is_active)"
                    error="{{ $errors->first('is_active') }}"
                />
            </div>
        </x-card>

        <!-- Configurações de Notificação -->
        <h2 class="section-title mb-4">Configurações de Notificação</h2>
        <x-card>
            <div class="space-y-4">
                <div class="flex items-center">
                    <input type="checkbox" name="notify_ticket_created" id="notify_ticket_created" value="1"
                        class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                        {{ old('notify_ticket_created', $user->notify_ticket_created) ? 'checked' : '' }}>
                    <label for="notify_ticket_created" class="ml-2 block text-sm text-gray-900">
                        Notificar sobre novos tickets
                    </label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="notify_ticket_replied" id="notify_ticket_replied" value="1"
                        class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                        {{ old('notify_ticket_replied', $user->notify_ticket_replied) ? 'checked' : '' }}>
                    <label for="notify_ticket_replied" class="ml-2 block text-sm text-gray-900">
                        Notificar sobre respostas em tickets
                    </label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="notify_ticket_status_changed" id="notify_ticket_status_changed" value="1"
                        class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                        {{ old('notify_ticket_status_changed', $user->notify_ticket_status_changed) ? 'checked' : '' }}>
                    <label for="notify_ticket_status_changed" class="ml-2 block text-sm text-gray-900">
                        Notificar sobre mudanças de status
                    </label>
                <div class="flex items-center">
                    <input type="checkbox" name="notify_ticket_priority_changed" id="notify_ticket_priority_changed" value="1"
                        class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                        {{ old('notify_ticket_priority_changed', $user->notify_ticket_priority_changed) ? 'checked' : '' }}>
                    <label for="notify_ticket_priority_changed" class="ml-2 block text-sm text-gray-900">
                        Notificar sobre mudanças de prioridade
                    </label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="notify_ticket_urgent" id="notify_ticket_urgent" value="1"
                        class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                        {{ old('notify_ticket_urgent', $user->notify_ticket_urgent) ? 'checked' : '' }}>
                    <label for="notify_ticket_urgent" class="ml-2 block text-sm text-gray-900">
                        Notificar sobre tickets urgentes
                    </label>
                </div>
            </div>
        </x-card>

        <!-- Ações -->
        <div class="flex gap-3 justify-end">
            <x-button variant="secondary" tag="a" href="{{ route('settings.users') }}">
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
</div>
@endsection

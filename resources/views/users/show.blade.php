@extends('layouts.app')

@section('title', 'Detalhes do Usuário')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <span class="text-sm text-cinza-claro">Usuários</span>
        <h1 class="page-title mt-1">Detalhes do Usuário</h1>
        <p class="text-cinza mt-2">Informações completas do usuário</p>
    </div>
    <div class="flex gap-2">
        <x-button variant="warning" tag="a" href="{{ route('users.edit', $user) }}">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Editar
        </x-button>
        <x-button variant="outline" tag="a" href="{{ route('settings.users') }}">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Voltar
        </x-button>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Informações do Usuário -->
    <x-card>
        <div class="flex items-center mb-6">
            <div class="bg-roxo bg-opacity-10 rounded-full w-16 h-16 flex items-center justify-center mr-4">
                <span class="font-bold text-roxo text-xl">{{ substr($user->name, 0, 1) }}</span>
            </div>
            <div>
                <h5 class="font-semibold text-cinza text-lg">{{ $user->name }}</h5>
                <p class="text-cinza-claro mb-2">{{ $user->email }}</p>
                <x-badge variant="{{ $user->role == 'admin' ? 'danger' : ($user->role == 'tecnico' ? 'warning' : 'info') }}">
                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                </x-badge>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Informações Básicas -->
            <div>
                <h6 class="font-semibold text-cinza mb-4">Informações Básicas</h6>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-cinza-claro">Nome:</span>
                        <span class="font-medium text-cinza">{{ $user->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-cinza-claro">E-mail:</span>
                        <span class="font-medium text-cinza">{{ $user->email }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-cinza-claro">Cargo:</span>
                        <span class="font-medium text-cinza">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-cinza-claro">Status:</span>
                        <x-badge variant="{{ $user->is_active ? 'success' : 'secondary' }}">
                            {{ $user->is_active ? 'Ativo' : 'Inativo' }}
                        </x-badge>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-cinza-claro">Criado em:</span>
                        <span class="font-medium text-cinza">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-cinza-claro">Último acesso:</span>
                        <span class="font-medium text-cinza">{{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Nunca' }}</span>
                    </div>
                </div>
            </div>

            <!-- Configurações de Notificação -->
            <div>
                <h6 class="font-semibold text-cinza mb-4">Configurações de Notificação</h6>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-cinza-claro">Novos tickets:</span>
                        <x-badge variant="{{ $user->notify_ticket_created ? 'success' : 'secondary' }}">
                            {{ $user->notify_ticket_created ? 'Ativo' : 'Inativo' }}
                        </x-badge>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-cinza-claro">Respostas:</span>
                        <x-badge variant="{{ $user->notify_ticket_replied ? 'success' : 'secondary' }}">
                            {{ $user->notify_ticket_replied ? 'Ativo' : 'Inativo' }}
                        </x-badge>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-cinza-claro">Mudança de status:</span>
                        <x-badge variant="{{ $user->notify_ticket_status_changed ? 'success' : 'secondary' }}">
                            {{ $user->notify_ticket_status_changed ? 'Ativo' : 'Inativo' }}
                        </x-badge>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-cinza-claro">Mudança de prioridade:</span>
                        <x-badge variant="{{ $user->notify_ticket_priority_changed ? 'success' : 'secondary' }}">
                            {{ $user->notify_ticket_priority_changed ? 'Ativo' : 'Inativo' }}
                        </x-badge>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-cinza-claro">Tickets urgentes:</span>
                        <x-badge variant="{{ $user->notify_ticket_urgent ? 'success' : 'secondary' }}">
                            {{ $user->notify_ticket_urgent ? 'Ativo' : 'Inativo' }}
                        </x-badge>
                    </div>
                </div>
            </div>
        </div>
    </x-card>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <x-stat-card 
            title="Tickets Atribuídos"
            :value="$user->assignedTickets->count()"
            color="primary"
        >
            <svg class="w-6 h-6 text-roxo" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
        </x-stat-card>

        <x-stat-card 
            title="Mensagens Enviadas"
            :value="$user->ticketMessages->count()"
            color="success"
        >
            <svg class="w-6 h-6 text-verde" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
            </svg>
        </x-stat-card>

        <x-stat-card 
            title="Último Acesso"
            :value="$user->last_login_at ? $user->last_login_at->diffForHumans() : 'Nunca'"
            color="info"
        >
            <svg class="w-6 h-6 text-azul" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </x-stat-card>
    </div>
</div>
@endsection
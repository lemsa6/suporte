@extends('layouts.app')

@section('title', 'Detalhes do Usuário')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <span class="text-sm text-gray-500">Usuários</span>
        <h1 class="page-title mt-1">Detalhes do Usuário</h1>
        <p class="text-gray-600 mt-2">Informações completas do usuário</p>
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
<div class="d-flex flex-column gap-4">
    <!-- Informações do Usuário -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3 icon-circle-md">
                    <span class="fw-bold text-primary fs-4">{{ substr($user->name, 0, 1) }}</span>
                </div>
                <div>
                    <h5 class="mb-1 fw-semibold text-dark">{{ $user->name }}</h5>
                    <p class="text-muted mb-0">{{ $user->email }}</p>
                    <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'tecnico' ? 'warning' : 'info') }}">
                        {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <!-- Informações Básicas -->
                <div class="col-12 col-md-6">
                    <h6 class="fw-semibold text-dark mb-3">Informações Básicas</h6>
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Nome:</span>
                            <span class="fw-medium">{{ $user->name }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">E-mail:</span>
                            <span class="fw-medium">{{ $user->email }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Cargo:</span>
                            <span class="fw-medium">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Status:</span>
                            <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                {{ $user->is_active ? 'Ativo' : 'Inativo' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Criado em:</span>
                            <span class="fw-medium">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Último acesso:</span>
                            <span class="fw-medium">{{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Nunca' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Configurações de Notificação -->
                <div class="col-12 col-md-6">
                    <h6 class="fw-semibold text-dark mb-3">Configurações de Notificação</h6>
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Novos tickets:</span>
                            <span class="badge bg-{{ $user->notify_ticket_created ? 'success' : 'secondary' }}">
                                {{ $user->notify_ticket_created ? 'Ativo' : 'Inativo' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Respostas:</span>
                            <span class="badge bg-{{ $user->notify_ticket_replied ? 'success' : 'secondary' }}">
                                {{ $user->notify_ticket_replied ? 'Ativo' : 'Inativo' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Mudança de status:</span>
                            <span class="badge bg-{{ $user->notify_ticket_status_changed ? 'success' : 'secondary' }}">
                                {{ $user->notify_ticket_status_changed ? 'Ativo' : 'Inativo' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Mudança de prioridade:</span>
                            <span class="badge bg-{{ $user->notify_ticket_priority_changed ? 'success' : 'secondary' }}">
                                {{ $user->notify_ticket_priority_changed ? 'Ativo' : 'Inativo' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Tickets urgentes:</span>
                            <span class="badge bg-{{ $user->notify_ticket_urgent ? 'success' : 'secondary' }}">
                                {{ $user->notify_ticket_urgent ? 'Ativo' : 'Inativo' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="row g-4">
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3 icon-circle-md">
                        <svg class="text-primary" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <h4 class="fw-bold text-dark mb-1">{{ $user->assignedTickets->count() }}</h4>
                    <p class="text-muted mb-0">Tickets Atribuídos</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3 icon-circle-md">
                        <svg class="text-success" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <h4 class="fw-bold text-dark mb-1">{{ $user->ticketMessages->count() }}</h4>
                    <p class="text-muted mb-0">Mensagens Enviadas</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3 icon-circle-md">
                        <svg class="text-info" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h4 class="fw-bold text-dark mb-1">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Nunca' }}</h4>
                    <p class="text-muted mb-0">Último Acesso</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

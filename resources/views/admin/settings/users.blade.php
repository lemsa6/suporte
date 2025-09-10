@extends('layouts.app')

@section('title', 'Gerenciar Usuários')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <span class="text-sm text-cinza-claro">Administração</span>
        <h1 class="page-title mt-1">Gerenciar Usuários</h1>
        <p class="text-cinza mt-2">Gerencie usuários, permissões e configurações de acesso ao sistema</p>
    </div>
    <x-button 
        variant="primary" 
        tag="a"
        href="{{ route('users.create') }}"
    >
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Novo Usuário
    </x-button>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Filtros e Busca -->
    <h2 class="section-title mb-4">Filtros e Busca</h2>
    <x-card>
        <form method="GET" action="{{ route('settings.users') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Busca -->
                <x-input 
                    label="Buscar" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Nome, email, cargo..."
                />

                <!-- Status -->
                <x-select 
                    label="Status" 
                    name="status"
                    :options="[
                        '' => 'Todos',
                        'active' => 'Ativo',
                        'inactive' => 'Inativo'
                    ]"
                    :selected="request('status')"
                />

                <!-- Cargo -->
                <x-select 
                    label="Cargo" 
                    name="role"
                    :options="[
                        '' => 'Todos',
                        'admin' => 'Administrador',
                        'tecnico' => 'Técnico',
                        'cliente_gestor' => 'Cliente Gestor'
                    ]"
                    :selected="request('role')"
                />

                <!-- Ações -->
                <div class="flex gap-2">
                    <x-button variant="primary" type="submit" class="flex-1">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Buscar
                    </x-button>
                    <x-button variant="outline" tag="a" href="{{ route('settings.users') }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Limpar
                    </x-button>
                </div>
            </div>
        </form>
    </x-card>

    <!-- Lista de Usuários -->
    <x-card>
        <div class="flex justify-between items-center mb-4">
            <h2 class="section-title">Lista de Usuários</h2>
            <x-badge variant="primary">{{ $users->total() }} usuários</x-badge>
        </div>
        @if($users->count() > 0)
            <x-table striped hover>
                <thead>
                    <tr>
                        <th>Usuário</th>
                        <th>Cargo</th>
                        <th>Contato</th>
                        <th>Status</th>
                        <th>Último Acesso</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>
                                <div class="flex items-center">
                                    <div class="user-avatar bg-primary-100 text-primary-600 mr-3">
                                        <span class="text-sm font-medium">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <div class="font-medium text-cinza">{{ $user->name }}</div>
                                        <div class="text-sm text-cinza-claro">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <x-badge variant="{{ $user->role == 'admin' ? 'danger' : ($user->role == 'tecnico' ? 'warning' : 'info') }}">
                                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                </x-badge>
                            </td>
                            <td>
                                <div class="text-sm text-cinza-claro">
                                    @if($user->phone)
                                        <div>{{ $user->phone }}</div>
                                    @endif
                                    <div>{{ $user->email }}</div>
                                </div>
                            </td>
                            <td>
                                <x-badge variant="{{ $user->is_active ? 'success' : 'default' }}">
                                    {{ $user->is_active ? 'Ativo' : 'Inativo' }}
                                </x-badge>
                            </td>
                            <td>
                                <div class="text-sm text-cinza-claro">
                                    {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Nunca' }}
                                </div>
                            </td>
                            <td>
                                <div class="flex gap-1">
                                    <x-button variant="outline" size="sm" tag="a" href="{{ route('users.show', $user) }}" title="Ver">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </x-button>
                                    <x-button variant="outline" size="sm" tag="a" href="{{ route('users.edit', $user) }}" title="Editar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </x-button>
                                    <form method="POST" action="{{ route('users.toggle-status', $user) }}" class="inline">
                                        @csrf
                                        <x-button variant="outline" size="sm" type="submit" title="{{ $user->is_active ? 'Desativar' : 'Ativar' }}">
                                            @if($user->is_active)
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            @endif
                                        </x-button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-table>

            <!-- Paginação -->
            @if($users->hasPages())
                <div class="mt-6">
                    {{ $users->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                <h3 class="empty-state-title">Nenhum usuário encontrado</h3>
                <p class="empty-state-text">Não há usuários que correspondam aos filtros aplicados.</p>
                <x-button variant="primary" tag="a" href="{{ route('users.create') }}">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Criar Primeiro Usuário
                </x-button>
            </div>
        @endif
    </x-card>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Gerenciar Usuários - ' . $client->display_name)

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <div class="flex items-center">
            <x-button variant="outline" tag="a" href="{{ route('clients.show', $client) }}" class="mr-3">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Voltar
            </x-button>
            <div>
                <span class="text-sm text-cinza-claro">{{ $client->display_name }}</span>
                <h1 class="page-title mt-1">Gerenciar Usuários</h1>
                <p class="text-cinza mt-2">Gerencie os usuários e permissões da sua empresa</p>
            </div>
        </div>
    </div>
    <x-button variant="primary" tag="a" href="{{ route('clients.users.create', $client) }}">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Novo Usuário
    </x-button>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Mensagens de Sucesso/Erro -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex">
                <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="text-sm text-green-700">
                    {{ session('success') }}
                </div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <svg class="w-5 h-5 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="text-sm text-red-700">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <x-stat-card 
            title="Total de Usuários" 
            :value="$contacts->count()" 
            icon="users"
            color="blue" 
        />
        <x-stat-card 
            title="Usuários Ativos" 
            :value="$contacts->where('is_active', true)->count()" 
            icon="user-check"
            color="green" 
        />
        <x-stat-card 
            title="Gestores" 
            :value="$contacts->where('user_type', 'cliente_gestor')->count()" 
            icon="shield"
            color="purple" 
        />
        <x-stat-card 
            title="Funcionários" 
            :value="$contacts->where('user_type', 'cliente_funcionario')->count()" 
            icon="user"
            color="gray" 
        />
    </div>

    <!-- Lista de Usuários -->
    <x-card>
        <x-slot name="header">
            <h2 class="section-title">Usuários da Empresa</h2>
        </x-slot>

        @if($contacts->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-cinza-claro">
                    <thead class="bg-creme">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-cinza-claro uppercase tracking-wider">
                                Usuário
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-cinza-claro uppercase tracking-wider">
                                Contato
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-cinza-claro uppercase tracking-wider">
                                Tipo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-cinza-claro uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-cinza-claro uppercase tracking-wider">
                                Notificações
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-cinza-claro uppercase tracking-wider">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-branco divide-y divide-cinza-claro">
                        @foreach($contacts as $contact)
                            <tr class="hover:bg-creme transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-roxo bg-opacity-10 rounded-full flex items-center justify-center mr-4">
                                            <span class="text-sm font-medium text-roxo">{{ substr($contact->name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-cinza">{{ $contact->name }}</div>
                                            <div class="text-sm text-cinza-claro">{{ $contact->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-cinza">
                                        @if($contact->phone)
                                            <div>{{ $contact->phone }}</div>
                                        @endif
                                        @if($contact->position)
                                            <div class="text-cinza-claro">{{ $contact->position }}</div>
                                        @endif
                                        @if($contact->department)
                                            <div class="text-cinza-claro">{{ $contact->department }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <x-badge variant="{{ $contact->user_type === 'cliente_gestor' ? 'success' : 'info' }}">
                                            {{ $contact->user_type === 'cliente_gestor' ? 'Gestor' : 'Funcionário' }}
                                        </x-badge>
                                        @if($contact->is_primary)
                                            <x-badge variant="warning">Principal</x-badge>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-badge variant="{{ $contact->is_active ? 'success' : 'danger' }}">
                                        {{ $contact->is_active ? 'Ativo' : 'Inativo' }}
                                    </x-badge>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full mr-2 {{ $contact->receive_notifications ? 'bg-green-500' : 'bg-red-500' }}"></div>
                                        <span class="text-sm text-cinza">
                                            {{ $contact->receive_notifications ? 'Sim' : 'Não' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <x-button variant="outline" size="sm" tag="a" href="{{ route('clients.users.edit', [$client, $contact]) }}">
                                            Editar
                                        </x-button>
                                        
                                        @if(!$contact->is_primary)
                                            <form method="POST" action="{{ route('clients.users.toggle-status', [$client, $contact]) }}" class="inline">
                                                @csrf
                                                <x-button variant="outline" size="sm" type="submit" 
                                                          class="{{ $contact->is_active ? 'text-red-600 hover:text-red-700' : 'text-green-600 hover:text-green-700' }}"
                                                          onclick="return confirm('Tem certeza que deseja alterar o status deste usuário?')">
                                                    {{ $contact->is_active ? 'Desativar' : 'Ativar' }}
                                                </x-button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <svg width="64" height="64" fill="none" stroke="currentColor" class="text-cinza-claro mb-4 mx-auto" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h6 class="text-cinza-claro mb-2">Nenhum usuário cadastrado</h6>
                <p class="text-cinza-claro-2 text-sm mb-4">Comece adicionando o primeiro usuário para sua empresa.</p>
                <x-button variant="primary" tag="a" href="{{ route('clients.users.create', $client) }}">
                    Adicionar Primeiro Usuário
                </x-button>
            </div>
        @endif
    </x-card>
</div>
@endsection
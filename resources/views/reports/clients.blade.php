@extends('layouts.app')

@section('title', 'Relatório de Clientes')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <div class="flex items-center">
            <x-button variant="outline" size="sm" tag="a" href="{{ route('reports.index') }}" class="mr-3">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Voltar
            </x-button>
            <div>
                <h1 class="page-title mb-0">Relatório de Clientes</h1>
                <p class="text-cinza-claro mb-0">Analise e filtre todos os clientes do sistema</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Filtros -->
    <x-card title="Filtros">
        <form method="GET" action="{{ route('reports.clients') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-cinza mb-2">Status</label>
                <select name="status" id="status" 
                        class="w-full px-3 py-2 border border-cinza-claro rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent">
                    <option value="">Todos os status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Ativo</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inativo</option>
                </select>
            </div>

            <!-- Data Inicial -->
            <div>
                <label for="date_from" class="block text-sm font-medium text-cinza mb-2">Data Inicial</label>
                <input type="date" name="date_from" id="date_from" 
                       class="w-full px-3 py-2 border border-cinza-claro rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent"
                       value="{{ request('date_from') }}">
            </div>

            <!-- Data Final -->
            <div>
                <label for="date_to" class="block text-sm font-medium text-cinza mb-2">Data Final</label>
                <input type="date" name="date_to" id="date_to" 
                       class="w-full px-3 py-2 border border-cinza-claro rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent"
                       value="{{ request('date_to') }}">
            </div>

            <!-- Botões -->
            <div class="flex items-end gap-2">
                <x-button variant="outline" tag="a" href="{{ route('reports.clients') }}" class="flex-1">
                    Limpar
                </x-button>
                
                <x-button variant="primary" type="submit" class="flex-1">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Filtrar
                </x-button>
            </div>
        </form>
    </x-card>

    <!-- Resultados -->
    <x-card>
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-cinza">
                Resultados ({{ $clients->total() }} clientes encontrados)
            </h3>
            
            @if(request()->hasAny(['status', 'date_from', 'date_to']))
                <x-badge variant="info">Filtros aplicados</x-badge>
            @endif
        </div>

        @if($clients->count() > 0)
            <x-table>
                <x-slot name="header">
                    <x-table-header>Cliente</x-table-header>
                    <x-table-header>CNPJ</x-table-header>
                    <x-table-header>Contatos</x-table-header>
                    <x-table-header>Status</x-table-header>
                    <x-table-header>Tickets</x-table-header>
                    <x-table-header>Criado</x-table-header>
                    <x-table-header>Ações</x-table-header>
                </x-slot>

                @foreach($clients as $client)
                    <x-table-row>
                        <x-table-cell>
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-roxo bg-opacity-10 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-6 h-6 text-roxo" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-medium text-cinza">{{ $client->company_name }}</div>
                                    @if($client->trade_name)
                                        <div class="text-cinza-claro text-sm">{{ $client->trade_name }}</div>
                                    @endif
                                </div>
                            </div>
                        </x-table-cell>
                        
                        <x-table-cell>
                            <div class="font-mono text-cinza">{{ $client->cnpj }}</div>
                        </x-table-cell>
                        
                        <x-table-cell>
                            @if($client->contacts_count > 0)
                                <x-badge variant="success">{{ $client->contacts_count }} contato(s)</x-badge>
                            @else
                                <x-badge variant="danger">Sem contatos</x-badge>
                            @endif
                        </x-table-cell>
                        
                        <x-table-cell>
                            @if($client->is_active)
                                <x-badge variant="success">Ativo</x-badge>
                            @else
                                <x-badge variant="danger">Inativo</x-badge>
                            @endif
                        </x-table-cell>
                        
                        <x-table-cell>
                            @if($client->tickets_count > 0)
                                <x-badge variant="primary">{{ $client->tickets_count }} ticket(s)</x-badge>
                            @else
                                <x-badge variant="secondary">Sem tickets</x-badge>
                            @endif
                        </x-table-cell>
                        
                        <x-table-cell class="text-cinza-claro">
                            {{ $client->created_at->format('d/m/Y') }}
                        </x-table-cell>
                        
                        <x-table-cell>
                            <x-button variant="link" size="sm" tag="a" href="{{ route('clients.show', $client) }}">
                                Ver detalhes
                            </x-button>
                        </x-table-cell>
                    </x-table-row>
                @endforeach
            </x-table>

            <!-- Paginação -->
            <div class="mt-6">
                {{ $clients->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg width="48" height="48" fill="none" stroke="currentColor" class="mx-auto mb-4 text-cinza-claro" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <h6 class="text-lg font-medium text-cinza mb-2">Nenhum cliente encontrado</h6>
                <p class="text-cinza-claro">
                    @if(request()->hasAny(['status', 'date_from', 'date_to']))
                        Tente ajustar os filtros aplicados.
                    @else
                        Não há clientes cadastrados no sistema.
                    @endif
                </p>
            </div>
        @endif
    </x-card>
</div>
@endsection
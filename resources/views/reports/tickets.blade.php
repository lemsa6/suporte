@extends('layouts.app')

@section('title', 'Relatório de Tickets')

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
                <h1 class="page-title mb-0">Relatório de Tickets</h1>
                <p class="text-cinza-claro mb-0">Analise e filtre todos os tickets do sistema</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Filtros -->
    <x-card title="Filtros">
        <form method="GET" action="{{ route('reports.tickets') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-cinza mb-2">Status</label>
                <select name="status" id="status" 
                        class="w-full px-3 py-2 border border-cinza-claro rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent">
                    <option value="">Todos os status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Prioridade -->
            <div>
                <label for="priority" class="block text-sm font-medium text-cinza mb-2">Prioridade</label>
                <select name="priority" id="priority" 
                        class="w-full px-3 py-2 border border-cinza-claro rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent">
                    <option value="">Todas as prioridades</option>
                    @foreach($priorities as $priority)
                        <option value="{{ $priority }}" {{ request('priority') == $priority ? 'selected' : '' }}>
                            {{ ucfirst($priority) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Categoria -->
            <div>
                <label for="category_id" class="block text-sm font-medium text-cinza mb-2">Categoria</label>
                <select name="category_id" id="category_id" 
                        class="w-full px-3 py-2 border border-cinza-claro rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent">
                    <option value="">Todas as categorias</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
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
                <x-button variant="outline" tag="a" href="{{ route('reports.tickets') }}" class="flex-1">
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
                Resultados ({{ $tickets->total() }} tickets encontrados)
            </h3>
            
            @if(request()->hasAny(['status', 'priority', 'category_id', 'date_from', 'date_to']))
                <x-badge variant="info">Filtros aplicados</x-badge>
            @endif
        </div>

        @if($tickets->count() > 0)
            <x-table>
                <x-slot name="header">
                    <x-table-header>Ticket</x-table-header>
                    <x-table-header>Cliente</x-table-header>
                    <x-table-header>Categoria</x-table-header>
                    <x-table-header>Status</x-table-header>
                    <x-table-header>Prioridade</x-table-header>
                    <x-table-header>Atribuído</x-table-header>
                    <x-table-header>Criado</x-table-header>
                </x-slot>

                @foreach($tickets as $ticket)
                    <x-table-row class="cursor-pointer hover:bg-cinza-claro-2" onclick="window.location.href='{{ route('tickets.show', $ticket->ticket_number) }}'">
                        <x-table-cell>
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3 text-white font-bold text-sm"
                                     style="background-color: {{ $ticket->category->color ?? '#6B7280' }};">
                                    #{{ $ticket->ticket_number }}
                                </div>
                                <div>
                                    <div class="font-medium text-cinza">
                                        {{ Str::limit($ticket->title, 50) }}
                                    </div>
                                    @if($ticket->is_urgent)
                                        <x-badge variant="danger" size="sm">Urgente</x-badge>
                                    @endif
                                </div>
                            </div>
                        </x-table-cell>
                        
                        <x-table-cell>
                            <div class="font-medium text-cinza">{{ $ticket->client->company_name }}</div>
                            <div class="text-cinza-claro text-sm">{{ $ticket->client->trade_name }}</div>
                        </x-table-cell>
                        
                        <x-table-cell>
                            <x-category-badge :category="$ticket->category" />
                        </x-table-cell>
                        
                        <x-table-cell>
                            <x-badge variant="
                                @if($ticket->status === 'aberto') warning
                                @elseif($ticket->status === 'em_andamento') info
                                @elseif($ticket->status === 'resolvido') success
                                @else secondary
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                            </x-badge>
                        </x-table-cell>
                        
                        <x-table-cell>
                            <x-badge variant="
                                @if($ticket->priority === 'baixa') success
                                @elseif($ticket->priority === 'média') warning
                                @elseif($ticket->priority === 'alta') danger
                                @else secondary
                                @endif">
                                {{ ucfirst($ticket->priority) }}
                            </x-badge>
                        </x-table-cell>
                        
                        <x-table-cell>
                            @if($ticket->assignedTo)
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-roxo bg-opacity-10 rounded-full flex items-center justify-center mr-2">
                                        <span class="text-roxo font-medium text-sm">{{ substr($ticket->assignedTo->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <div class="font-medium text-cinza">{{ $ticket->assignedTo->name }}</div>
                                        <div class="text-cinza-claro text-sm">{{ ucfirst($ticket->assignedTo->role) }}</div>
                                    </div>
                                </div>
                            @else
                                <span class="text-cinza-claro">Não atribuído</span>
                            @endif
                        </x-table-cell>
                        
                        <x-table-cell class="text-cinza-claro">
                            {{ $ticket->created_at->format('d/m/Y H:i') }}
                        </x-table-cell>
                    </x-table-row>
                @endforeach
            </x-table>

            <!-- Paginação -->
            <div class="mt-6">
                {{ $tickets->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg width="48" height="48" fill="none" stroke="currentColor" class="mx-auto mb-4 text-cinza-claro" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h6 class="text-lg font-medium text-cinza mb-2">Nenhum ticket encontrado</h6>
                <p class="text-cinza-claro">
                    @if(request()->hasAny(['status', 'priority', 'category_id', 'date_from', 'date_to']))
                        Tente ajustar os filtros aplicados.
                    @else
                        Não há tickets cadastrados no sistema.
                    @endif
                </p>
            </div>
        @endif
    </x-card>
</div>
@endsection
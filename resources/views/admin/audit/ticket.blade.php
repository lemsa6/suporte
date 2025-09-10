@extends('layouts.app')

@section('title', 'Auditoria do Ticket')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <span class="text-sm text-cinza-claro">Administração</span>
        <h1 class="page-title mt-1">Auditoria do Ticket #{{ $ticket->ticket_number }}</h1>
        <p class="text-cinza mt-2">Histórico completo de alterações do ticket</p>
    </div>
    <div class="flex gap-2">
        <x-button variant="outline" tag="a" href="{{ route('tickets.show', $ticket->ticket_number) }}">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
            Ver Ticket
        </x-button>
        <x-button variant="outline" tag="a" href="{{ route('admin.audit.index') }}">
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
    <!-- Informações do Ticket -->
    <x-card title="Informações do Ticket">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <label class="text-xs text-cinza-claro uppercase tracking-wide">Número do Ticket</label>
                <div class="text-lg font-semibold text-cinza">#{{ $ticket->ticket_number }}</div>
            </div>
            <div>
                <label class="text-xs text-cinza-claro uppercase tracking-wide">Título</label>
                <div class="text-cinza">{{ $ticket->title }}</div>
            </div>
            <div>
                <label class="text-xs text-cinza-claro uppercase tracking-wide">Status</label>
                <x-badge variant="{{ $ticket->status === 'aberto' ? 'success' : ($ticket->status === 'fechado' ? 'danger' : 'warning') }}">
                    {{ ucfirst($ticket->status) }}
                </x-badge>
            </div>
            <div>
                <label class="text-xs text-cinza-claro uppercase tracking-wide">Prioridade</label>
                <x-badge variant="{{ $ticket->is_urgent ? 'danger' : 'info' }}">
                    {{ $ticket->is_urgent ? 'Urgente' : 'Normal' }}
                </x-badge>
            </div>
            <div>
                <label class="text-xs text-cinza-claro uppercase tracking-wide">Cliente</label>
                <div class="text-cinza">{{ $ticket->client->trade_name ?? $ticket->client->company_name }}</div>
            </div>
            <div>
                <label class="text-xs text-cinza-claro uppercase tracking-wide">Criado em</label>
                <div class="text-cinza">{{ $ticket->created_at->format('d/m/Y H:i') }}</div>
            </div>
        </div>
    </x-card>

    <!-- Filtros -->
    <x-card title="Filtros">
        <form method="GET" action="{{ route('admin.audit.ticket', $ticket->id) }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Tipo de Evento -->
                <div>
                    <label for="event_type" class="block text-sm font-medium text-cinza mb-2">Tipo de Evento</label>
                    <select id="event_type" name="event_type" 
                        class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent">
                        <option value="">Todos os tipos</option>
                        <option value="created" {{ request('event_type') == 'created' ? 'selected' : '' }}>Criado</option>
                        <option value="updated" {{ request('event_type') == 'updated' ? 'selected' : '' }}>Atualizado</option>
                        <option value="deleted" {{ request('event_type') == 'deleted' ? 'selected' : '' }}>Excluído</option>
                        <option value="restored" {{ request('event_type') == 'restored' ? 'selected' : '' }}>Restaurado</option>
                    </select>
                </div>

                <!-- Usuário -->
                <div>
                    <label for="user_id" class="block text-sm font-medium text-cinza mb-2">Usuário</label>
                    <select id="user_id" name="user_id" 
                        class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent">
                        <option value="">Todos os usuários</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Data -->
                <div>
                    <label for="date" class="block text-sm font-medium text-cinza mb-2">Data</label>
                    <input type="date" id="date" name="date" 
                        class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent"
                        value="{{ request('date') }}">
                </div>
            </div>

            <!-- Botões -->
            <div class="flex justify-end gap-3 pt-4 border-t border-cinza-claro-2">
                <x-button variant="outline" tag="a" href="{{ route('admin.audit.ticket', $ticket->id) }}">
                    Limpar Filtros
                </x-button>
                <x-button variant="primary" type="submit">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Aplicar Filtros
                </x-button>
            </div>
        </form>
    </x-card>

    <!-- Logs de Auditoria -->
    <x-card title="Histórico de Alterações">
        @if($auditLogs->count() > 0)
            <div class="space-y-4">
                @foreach($auditLogs as $log)
                    <div class="border border-cinza-claro-2 rounded-lg p-4 hover:bg-cinza-claro-2 transition-colors">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <x-audit-badge :event-type="$log->event_type" />
                                <div>
                                    <h4 class="font-medium text-cinza">
                                        {{ ucfirst(str_replace('_', ' ', $log->event_type)) }}
                                    </h4>
                                    <p class="text-sm text-cinza-claro">
                                        {{ $log->created_at->format('d/m/Y H:i:s') }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-cinza-claro">
                                    @if($log->user)
                                        {{ $log->user->name }}
                                    @else
                                        Sistema
                                    @endif
                                </div>
                                <div class="text-xs text-cinza-claro">
                                    {{ $log->ip_address ?? 'N/A' }}
                                </div>
                            </div>
                        </div>

                        @if($log->old_values || $log->new_values)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if($log->old_values)
                                    <div>
                                        <label class="text-xs text-cinza-claro uppercase tracking-wide mb-2 block">Valores Anteriores</label>
                                        <div class="bg-cinza-claro-2 rounded p-3 text-sm">
                                            <pre class="whitespace-pre-wrap text-cinza">{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        </div>
                                    </div>
                                @endif

                                @if($log->new_values)
                                    <div>
                                        <label class="text-xs text-cinza-claro uppercase tracking-wide mb-2 block">Novos Valores</label>
                                        <div class="bg-cinza-claro-2 rounded p-3 text-sm">
                                            <pre class="whitespace-pre-wrap text-cinza">{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if($log->metadata)
                            <div class="mt-3">
                                <label class="text-xs text-cinza-claro uppercase tracking-wide mb-2 block">Metadados</label>
                                <div class="bg-cinza-claro-2 rounded p-3 text-sm">
                                    <pre class="whitespace-pre-wrap text-cinza">{{ json_encode($log->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            </div>
                        @endif

                        <div class="flex justify-end mt-3 pt-3 border-t border-cinza-claro-2">
                            <x-button variant="link" size="sm" tag="a" href="{{ route('admin.audit.show', $log->id) }}">
                                Ver Detalhes
                            </x-button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginação -->
            <div class="mt-6">
                {{ $auditLogs->links() }}
            </div>
        @else
            <div class="text-center py-8">
                <svg class="w-16 h-16 text-cinza-claro mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-cinza mb-2">Nenhum log encontrado</h3>
                <p class="text-cinza-claro">Não há registros de auditoria para este ticket com os filtros aplicados.</p>
            </div>
        @endif
    </x-card>
</div>
@endsection
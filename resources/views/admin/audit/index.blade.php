@extends('layouts.app')

@section('title', 'Logs de Auditoria')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <span class="text-sm text-gray-500">Administração</span>
        <h1 class="page-title mt-1">Logs de Auditoria</h1>
        <p class="text-gray-600 mt-2">Rastreamento de todas as ações realizadas no sistema</p>
    </div>
    <div class="flex gap-2">
        <x-button variant="outline" tag="a" href="{{ route('admin.audit.export', request()->query()) }}">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Exportar CSV
        </x-button>
        <x-button variant="outline" tag="a" href="{{ route('admin.audit.statistics') }}">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            Estatísticas
        </x-button>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Filtros -->
    <h2 class="section-title mb-4">Filtros</h2>
    <x-card>
        <form method="GET" action="{{ route('admin.audit.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Tipo de Evento -->
                <x-select 
                    label="Tipo de Evento" 
                    name="event_type"
                    :options="array_merge(['' => 'Todos os tipos'], $eventTypes)"
                    :selected="request('event_type')"
                />

                <!-- Tipo de Modelo -->
                <x-select 
                    label="Tipo de Modelo" 
                    name="auditable_type"
                    :options="array_merge(['' => 'Todos os modelos'], $auditableTypes)"
                    :selected="request('auditable_type')"
                />

                <!-- IP -->
                <x-input 
                    label="Endereço IP" 
                    name="ip_address" 
                    value="{{ request('ip_address') }}"
                    placeholder="Ex: 192.168.1.100"
                />

                <!-- Data Inicial -->
                <x-input 
                    label="Data Inicial" 
                    name="date_from" 
                    type="date"
                    value="{{ request('date_from') }}"
                />

                <!-- Data Final -->
                <x-input 
                    label="Data Final" 
                    name="date_to" 
                    type="date"
                    value="{{ request('date_to') }}"
                />

                <!-- ID do Modelo -->
                <x-input 
                    label="ID do Modelo" 
                    name="auditable_id" 
                    type="number"
                    value="{{ request('auditable_id') }}"
                    placeholder="Ex: 123"
                />
            </div>

            <div class="flex gap-2">
                <x-button variant="primary" type="submit">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Filtrar
                </x-button>
                <x-button variant="outline" tag="a" href="{{ route('admin.audit.index') }}">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Limpar
                </x-button>
            </div>
        </form>
    </x-card>

    <!-- Lista de Logs -->
    <h2 class="section-title mb-4">Logs de Auditoria</h2>
    <x-card>
        <div class="flex justify-between items-center mb-4">
            <x-badge variant="primary">{{ $logs->total() }} registros</x-badge>
        </div>
        @if($logs->count() > 0)
            <x-table striped hover>
                <thead>
                    <tr>
                        <th>Ação</th>
                        <th>Usuário</th>
                        <th>Modelo</th>
                        <th>IP</th>
                        <th>Data/Hora</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                            @foreach($logs as $log)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-{{ $log->event_type == 'created' ? 'success' : ($log->event_type == 'updated' ? 'warning' : ($log->event_type == 'deleted' ? 'danger' : 'info')) }} me-2">
                                                {{ $eventTypes[$log->event_type] ?? ucfirst($log->event_type) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-medium">{{ $log->user_name }}</span>
                                            @if($log->user_type)
                                                <small class="text-muted">{{ ucfirst($log->user_type) }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-medium">{{ $auditableTypes[$log->auditable_type] ?? class_basename($log->auditable_type) }}</span>
                                            <small class="text-muted">ID: {{ $log->auditable_id }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-medium">{{ $log->ip_address ?? 'N/A' }}</span>
                                            <small class="text-muted">{{ $log->browser_info }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-medium">{{ $log->created_at->format('d/m/Y') }}</span>
                                            <small class="text-muted">{{ $log->created_at->format('H:i:s') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('admin.audit.show', $log) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Ver detalhes">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
            </x-table>
            @else
                <div class="text-center py-5">
                    <svg class="text-muted mb-3" width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h5 class="text-muted">Nenhum log encontrado</h5>
                    <p class="text-muted">Não há registros de auditoria para os filtros selecionados.</p>
                </div>
            @endif
        </div>
        
        @if($logs->hasPages())
            <div class="mt-4">
                {{ $logs->links() }}
            </div>
        @endif
    </x-card>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Logs de Auditoria')

@section('header')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
    <div class="flex-grow-1">
        <h2 class="fs-2 fw-bold text-dark mb-1">
            Logs de Auditoria
        </h2>
        <p class="text-muted">
            Rastreamento de todas as ações realizadas no sistema
        </p>
    </div>
    <div class="mt-3 mt-md-0 d-flex gap-2">
        <a href="{{ route('admin.audit.export', request()->query()) }}" class="btn btn-outline-success d-inline-flex align-items-center">
            <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Exportar CSV
        </a>
        <a href="{{ route('admin.audit.statistics') }}" class="btn btn-outline-info d-inline-flex align-items-center">
            <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            Estatísticas
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="d-flex flex-column gap-4">
    <!-- Filtros -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0 fw-semibold">Filtros</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.audit.index') }}" class="d-flex flex-column gap-4">
                <div class="row g-3">
                    <!-- Tipo de Evento -->
                    <div class="col-12 col-md-3">
                        <label for="event_type" class="form-label fw-medium text-dark">Tipo de Evento</label>
                        <select name="event_type" id="event_type" class="form-select">
                            <option value="">Todos os tipos</option>
                            @foreach($eventTypes as $value => $label)
                                <option value="{{ $value }}" {{ request('event_type') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tipo de Modelo -->
                    <div class="col-12 col-md-3">
                        <label for="auditable_type" class="form-label fw-medium text-dark">Tipo de Modelo</label>
                        <select name="auditable_type" id="auditable_type" class="form-select">
                            <option value="">Todos os modelos</option>
                            @foreach($auditableTypes as $value => $label)
                                <option value="{{ $value }}" {{ request('auditable_type') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- IP -->
                    <div class="col-12 col-md-3">
                        <label for="ip_address" class="form-label fw-medium text-dark">Endereço IP</label>
                        <input type="text" name="ip_address" id="ip_address" value="{{ request('ip_address') }}"
                            class="form-control" placeholder="Ex: 192.168.1.100">
                    </div>

                    <!-- Data Inicial -->
                    <div class="col-12 col-md-3">
                        <label for="date_from" class="form-label fw-medium text-dark">Data Inicial</label>
                        <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                            class="form-control">
                    </div>

                    <!-- Data Final -->
                    <div class="col-12 col-md-3">
                        <label for="date_to" class="form-label fw-medium text-dark">Data Final</label>
                        <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                            class="form-control">
                    </div>

                    <!-- ID do Modelo -->
                    <div class="col-12 col-md-3">
                        <label for="auditable_id" class="form-label fw-medium text-dark">ID do Modelo</label>
                        <input type="number" name="auditable_id" id="auditable_id" value="{{ request('auditable_id') }}"
                            class="form-control" placeholder="Ex: 123">
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Filtrar
                    </button>
                    <a href="{{ route('admin.audit.index') }}" class="btn btn-outline-secondary">
                        <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Limpar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Logs -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold">Logs de Auditoria</h5>
                <span class="badge bg-primary">{{ $logs->total() }} registros</span>
            </div>
        </div>
        <div class="card-body p-0">
            @if($logs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">Ação</th>
                                <th class="border-0">Usuário</th>
                                <th class="border-0">Modelo</th>
                                <th class="border-0">IP</th>
                                <th class="border-0">Data/Hora</th>
                                <th class="border-0">Ações</th>
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
                    </table>
                </div>
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
            <div class="card-footer bg-white border-0">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

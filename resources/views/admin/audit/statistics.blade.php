@extends('layouts.app')

@section('title', 'Estatísticas de Auditoria')

@section('header')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
    <div class="flex-grow-1">
        <h2 class="fs-2 fw-bold text-dark mb-1">
            Estatísticas de Auditoria
        </h2>
        <p class="text-muted">
            Análise dos logs de auditoria do sistema
        </p>
    </div>
    <div class="mt-3 mt-md-0">
        <a href="{{ route('admin.audit.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center">
            <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Voltar
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
            <form method="GET" action="{{ route('admin.audit.statistics') }}" class="d-flex flex-column gap-4">
                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <label for="date_from" class="form-label fw-medium text-dark">Data Inicial</label>
                        <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                            class="form-control">
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="date_to" class="form-label fw-medium text-dark">Data Final</label>
                        <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                            class="form-control">
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="event_type" class="form-label fw-medium text-dark">Tipo de Evento</label>
                        <select name="event_type" id="event_type" class="form-select">
                            <option value="">Todos os tipos</option>
                            <option value="created" {{ request('event_type') == 'created' ? 'selected' : '' }}>Criado</option>
                            <option value="updated" {{ request('event_type') == 'updated' ? 'selected' : '' }}>Atualizado</option>
                            <option value="deleted" {{ request('event_type') == 'deleted' ? 'selected' : '' }}>Excluído</option>
                            <option value="replied" {{ request('event_type') == 'replied' ? 'selected' : '' }}>Respondido</option>
                            <option value="closed" {{ request('event_type') == 'closed' ? 'selected' : '' }}>Fechado</option>
                            <option value="viewed" {{ request('event_type') == 'viewed' ? 'selected' : '' }}>Visualizado</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
                    <a href="{{ route('admin.audit.statistics') }}" class="btn btn-outline-secondary">Limpar</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Resumo Geral -->
    <div class="row g-4">
        <div class="col-12 col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <svg class="text-primary" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h4 class="fw-bold text-dark mb-1">{{ number_format($statistics['total_logs']) }}</h4>
                    <p class="text-muted mb-0">Total de Logs</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <svg class="text-success" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <h4 class="fw-bold text-dark mb-1">{{ $statistics['by_user']->count() }}</h4>
                    <p class="text-muted mb-0">Usuários Ativos</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <svg class="text-warning" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h4 class="fw-bold text-dark mb-1">{{ $statistics['by_event_type']->count() }}</h4>
                    <p class="text-muted mb-0">Tipos de Eventos</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <svg class="text-info" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h4 class="fw-bold text-dark mb-1">{{ $statistics['by_ip']->count() }}</h4>
                    <p class="text-muted mb-0">IPs Únicos</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos e Tabelas -->
    <div class="row g-4">
        <!-- Eventos por Tipo -->
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-semibold">Eventos por Tipo</h5>
                </div>
                <div class="card-body">
                    @if($statistics['by_event_type']->count() > 0)
                        <div class="d-flex flex-column gap-3">
                            @foreach($statistics['by_event_type'] as $eventType => $count)
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-medium">{{ ucfirst($eventType) }}</span>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress" style="width: 100px; height: 8px;">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: {{ ($count / $statistics['total_logs']) * 100 }}%"
                                                 aria-valuenow="{{ $count }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="{{ $statistics['total_logs'] }}">
                                            </div>
                                        </div>
                                        <span class="badge bg-primary">{{ $count }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center">Nenhum dado disponível</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Usuários Mais Ativos -->
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-semibold">Usuários Mais Ativos</h5>
                </div>
                <div class="card-body">
                    @if($statistics['by_user']->count() > 0)
                        <div class="d-flex flex-column gap-3">
                            @foreach($statistics['by_user']->take(10) as $userName => $count)
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-medium">{{ $userName }}</span>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress" style="width: 100px; height: 8px;">
                                            <div class="progress-bar bg-success" role="progressbar" 
                                                 style="width: {{ ($count / $statistics['by_user']->max()) * 100 }}%"
                                                 aria-valuenow="{{ $count }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="{{ $statistics['by_user']->max() }}">
                                            </div>
                                        </div>
                                        <span class="badge bg-success">{{ $count }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center">Nenhum dado disponível</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Atividade Recente -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0 fw-semibold">Atividade Recente</h5>
        </div>
        <div class="card-body">
            @if($statistics['recent_activity']->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">Ação</th>
                                <th class="border-0">Usuário</th>
                                <th class="border-0">Modelo</th>
                                <th class="border-0">IP</th>
                                <th class="border-0">Data/Hora</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($statistics['recent_activity'] as $log)
                                <tr>
                                    <td>
                                        <span class="badge bg-{{ $log->event_type == 'created' ? 'success' : ($log->event_type == 'updated' ? 'warning' : ($log->event_type == 'deleted' ? 'danger' : 'info')) }}">
                                            {{ ucfirst($log->event_type) }}
                                        </span>
                                    </td>
                                    <td>{{ $log->user_name }}</td>
                                    <td>{{ class_basename($log->auditable_type) }} #{{ $log->auditable_id }}</td>
                                    <td>{{ $log->ip_address ?? 'N/A' }}</td>
                                    <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted text-center">Nenhuma atividade recente</p>
            @endif
        </div>
    </div>
</div>
@endsection

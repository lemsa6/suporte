@extends('layouts.app')

@section('title', 'Detalhes do Log de Auditoria')

@section('header')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
    <div class="flex-grow-1">
        <h2 class="fs-2 fw-bold text-dark mb-1">
            Detalhes do Log de Auditoria
        </h2>
        <p class="text-muted">
            Informações completas sobre a ação registrada
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
    <!-- Informações Básicas -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0 fw-semibold">Informações Básicas</h5>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-12 col-md-6">
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">ID do Log:</span>
                            <span class="fw-medium">#{{ $auditLog->id }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Tipo de Evento:</span>
                            <span class="badge bg-{{ $auditLog->event_type == 'created' ? 'success' : ($auditLog->event_type == 'updated' ? 'warning' : ($auditLog->event_type == 'deleted' ? 'danger' : 'info')) }}">
                                {{ ucfirst($auditLog->event_type) }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Modelo:</span>
                            <span class="fw-medium">{{ class_basename($auditLog->auditable_type) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">ID do Modelo:</span>
                            <span class="fw-medium">{{ $auditLog->auditable_id }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Data/Hora:</span>
                            <span class="fw-medium">{{ $auditLog->created_at->format('d/m/Y H:i:s') }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Usuário:</span>
                            <span class="fw-medium">{{ $auditLog->user_name }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Tipo de Usuário:</span>
                            <span class="fw-medium">{{ $auditLog->user_type ? ucfirst($auditLog->user_type) : 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Endereço IP:</span>
                            <span class="fw-medium">{{ $auditLog->ip_address ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Método HTTP:</span>
                            <span class="fw-medium">{{ $auditLog->method ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">URL:</span>
                            <span class="fw-medium text-break">{{ $auditLog->url ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informações do Navegador -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0 fw-semibold">Informações do Navegador</h5>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-12">
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Navegador/Sistema:</span>
                            <span class="fw-medium">{{ $auditLog->browser_info }}</span>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="text-muted mb-2">User Agent Completo:</span>
                            <div class="bg-light p-3 rounded">
                                <code class="text-break">{{ $auditLog->user_agent ?? 'N/A' }}</code>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Descrição da Ação -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0 fw-semibold">Descrição da Ação</h5>
        </div>
        <div class="card-body">
            <p class="mb-0">{{ $auditLog->formatted_description }}</p>
        </div>
    </div>

    <!-- Valores Antigos e Novos -->
    @if($auditLog->old_values || $auditLog->new_values)
        <div class="row g-4">
            @if($auditLog->old_values)
                <div class="col-12 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0">
                            <h5 class="mb-0 fw-semibold text-danger">Valores Anteriores</h5>
                        </div>
                        <div class="card-body">
                            <div class="bg-light p-3 rounded">
                                <pre class="mb-0"><code>{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($auditLog->new_values)
                <div class="col-12 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0">
                            <h5 class="mb-0 fw-semibold text-success">Valores Novos</h5>
                        </div>
                        <div class="card-body">
                            <div class="bg-light p-3 rounded">
                                <pre class="mb-0"><code>{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Informações do Modelo Relacionado -->
    @if($auditLog->auditable)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0 fw-semibold">Informações do Modelo Relacionado</h5>
            </div>
            <div class="card-body">
                <div class="bg-light p-3 rounded">
                    <pre class="mb-0"><code>{{ json_encode($auditLog->auditable->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

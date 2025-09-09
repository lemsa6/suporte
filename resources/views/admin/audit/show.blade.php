@extends('layouts.app')

@section('title', 'Detalhes do Log de Auditoria')

@section('header')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
    <div class="flex-grow-1">
        <h1 class="page-title mb-1">
            Detalhes do Log de Auditoria
        </h1>
        <p class="text-muted">
            Informações completas sobre a ação registrada
        </p>
    </div>
    <div class="mt-3 mt-md-0">
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
<div class="d-flex flex-column gap-4">
    <!-- Informações Básicas -->
    <h2 class="section-title mb-4">Informações Básicas</h2>
    <x-card>
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
    </x-card>

    <!-- Informações do Navegador -->
    <h2 class="section-title mb-4">Informações do Navegador</h2>
    <x-card>
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
    </x-card>

    <!-- Descrição da Ação -->
    <h2 class="section-title mb-4">Descrição da Ação</h2>
    <x-card>
        <p class="mb-0">{{ $auditLog->formatted_description }}</p>
    </x-card>

    <!-- Valores Antigos e Novos -->
    @if($auditLog->old_values || $auditLog->new_values)
        <div class="row g-4">
            @if($auditLog->old_values)
                <div class="col-12 col-md-6">
                    <h3 class="section-title mb-4 text-danger">Valores Anteriores</h3>
                    <x-card>
                        <div class="bg-light p-3 rounded">
                            <pre class="mb-0"><code>{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                        </div>
                    </x-card>
                </div>
            @endif

            @if($auditLog->new_values)
                <div class="col-12 col-md-6">
                    <h3 class="section-title mb-4 text-success">Valores Novos</h3>
                    <x-card>
                        <div class="bg-light p-3 rounded">
                            <pre class="mb-0"><code>{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                        </div>
                    </x-card>
                </div>
            @endif
        </div>
    @endif

    <!-- Informações do Modelo Relacionado -->
    @if($auditLog->auditable)
        <h2 class="section-title mb-4">Informações do Modelo Relacionado</h2>
        <x-card>
            <div class="bg-light p-3 rounded">
                <pre class="mb-0"><code>{{ json_encode($auditLog->auditable->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
            </div>
        </x-card>
    @endif
</div>
@endsection

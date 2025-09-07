@extends('layouts.app')

@section('title', 'Logs de Auditoria do Ticket')

@section('header')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
    <div class="flex-grow-1">
        <h2 class="fs-2 fw-bold text-dark mb-1">
            Logs de Auditoria
        </h2>
        <p class="text-muted">
            Histórico completo de ações do Ticket #{{ $ticket->ticket_number }}
        </p>
    </div>
    <div class="mt-3 mt-md-0 d-flex gap-2">
        <a href="{{ route('tickets.show', $ticket->ticket_number) }}" class="btn btn-outline-primary d-inline-flex align-items-center">
            <svg class="me-2" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
            Ver Ticket
        </a>
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
    <!-- Informações do Ticket -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0 fw-semibold">Informações do Ticket</h5>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-12 col-md-6">
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Número:</span>
                            <span class="fw-bold">#{{ $ticket->ticket_number }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Título:</span>
                            <span class="fw-medium">{{ $ticket->title }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Status:</span>
                            <span class="badge bg-{{ $ticket->status == 'aberto' ? 'success' : ($ticket->status == 'fechado' ? 'danger' : 'warning') }}">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Prioridade:</span>
                            <span class="badge bg-{{ $ticket->priority == 'alta' ? 'danger' : ($ticket->priority == 'média' ? 'warning' : 'info') }}">
                                {{ ucfirst($ticket->priority) }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Cliente:</span>
                            <span class="fw-medium">{{ $ticket->client->name ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Categoria:</span>
                            <span class="fw-medium">{{ $ticket->category->name ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Atribuído a:</span>
                            <span class="fw-medium">{{ $ticket->assignedTo->name ?? 'Não atribuído' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Criado em:</span>
                            <span class="fw-medium">{{ $ticket->created_at->format('d/m/Y H:i:s') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Logs de Auditoria -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold">Histórico de Ações</h5>
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
                                <th class="border-0">IP</th>
                                <th class="border-0">Data/Hora</th>
                                <th class="border-0">Detalhes</th>
                                <th class="border-0">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-{{ $log->event_type == 'created' ? 'success' : ($log->event_type == 'updated' ? 'warning' : ($log->event_type == 'deleted' ? 'danger' : 'info')) }} me-2">
                                                {{ ucfirst($log->event_type) }}
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
                                        <span class="text-muted">{{ Str::limit($log->description, 50) }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.audit.show', $log) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Ver detalhes">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
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
                    <p class="text-muted">Não há registros de auditoria para este ticket.</p>
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

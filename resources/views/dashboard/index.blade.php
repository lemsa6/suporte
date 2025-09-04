@extends('layouts.app')

@section('title', 'Dashboard')

@section('header')
<div class="main-header">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
        <div class="flex-grow-1">
            <h2 class="header-title">
                Dashboard
            </h2>
            <p class="header-subtitle">
                Bem-vindo, {{ auth()->user()->name }}! Aqui está o resumo dos seus tickets.
            </p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="{{ route('tickets.create') }}" class="btn btn-primary">
                <svg class="me-2" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Novo Ticket
            </a>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="row g-3">
        <!-- Estatísticas Gerais -->
        <div class="col-12">
            <div class="row g-3">
                <!-- Total de Tickets -->
                <div class="col-sm-6 col-lg-3">
                    <div class="metric-card">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon me-3">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="flex-grow-1">
                                <div class="metric-label">Total de Tickets</div>
                                <div class="metric-value">{{ $stats['total'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tickets Ativos -->
                <div class="col-sm-6 col-lg-3">
                    <div class="metric-card">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon me-3">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-grow-1">
                                <div class="metric-label">Tickets Ativos</div>
                                <div class="metric-value">{{ $stats['active'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tickets Abertos -->
                <div class="col-sm-6 col-lg-3">
                    <div class="metric-card">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon me-3">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <div class="flex-grow-1">
                                <div class="metric-label">Tickets Abertos</div>
                                <div class="metric-value">{{ $stats['open'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tickets Resolvidos -->
                <div class="col-sm-6 col-lg-3">
                    <div class="metric-card">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon me-3">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-grow-1">
                                <div class="metric-label">Tickets Resolvidos</div>
                                <div class="metric-value">{{ $stats['resolved'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos e Estatísticas Detalhadas -->
        <div class="col-12">
            <div class="row g-3">
                <!-- Gráfico de Status -->
                <div class="col-lg-8">
                    <div class="main-card">
                        <div class="card-header">
                            <h5 class="mb-0">Status dos Tickets</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="statusChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Estatísticas Rápidas -->
                <div class="col-lg-4">
                    <div class="main-card">
                        <div class="card-body">
                            <div class="d-flex flex-column gap-2">
                                <!-- Tickets Urgentes -->
                                <div class="quick-stats-item">
                                    <div class="d-flex align-items-center">
                                        <div class="quick-stats-icon me-3">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="dashboard-title mb-1">Urgentes</div>
                                            <div class="text-secondary small">{{ $urgentTickets->count() }} tickets</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tickets Não Atribuídos -->
                                <div class="quick-stats-item">
                                    <div class="d-flex align-items-center">
                                        <div class="quick-stats-icon me-3">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="dashboard-title mb-1">Não Atribuídos</div>
                                            <div class="text-secondary small">{{ $unassignedTickets->count() }} tickets</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tempo Médio de Resposta -->
                                <div class="quick-stats-item">
                                    <div class="d-flex align-items-center">
                                        <div class="quick-stats-icon me-3">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="dashboard-title mb-1">Tempo Médio</div>
                                            <div class="text-secondary small">{{ $avgResponseTime ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tickets Recentes -->
        <div class="col-12">
            <div class="main-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tickets Recentes</h5>
                    <a href="{{ route('tickets.index') }}" class="btn btn-secondary btn-sm">Ver Todos</a>
                </div>
                <div class="card-body p-0">
                    @if($recentTickets->count() > 0)
                        <div class="data-table">
                            <div class="table-container">
                                <table class="table table-hover mb-0">
                                    <thead class="table-header">
                                        <tr>
                                            <th class="border-0">ID</th>
                                            <th class="border-0">Título</th>
                                            <th class="border-0">Cliente</th>
                                            <th class="border-0">Status</th>
                                            <th class="border-0">Prioridade</th>
                                            <th class="border-0">Criado</th>
                                            <th class="border-0">Ações</th>
                                        </tr>
                                    </thead>
                                <tbody>
                                    @foreach($recentTickets as $ticket)
                                        <tr class="clickable" onclick="window.location.href='{{ route('tickets.show', $ticket->ticket_number) }}'">
                                            <td class="align-middle">
                                                <span class="fw-medium">#{{ $ticket->ticket_number }}</span>
                                            </td>
                                            <td class="align-middle">
                                                <div>
                                                    <div class="fw-medium text-dark">{{ Str::limit($ticket->title, 50) }}</div>
                                                    <div class="dashboard-subtitle small">{{ Str::limit($ticket->description, 60) }}</div>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex align-items-center">
                                                     <div class="dashboard-client-avatar">
                                                <span class="fw-medium">{{ substr($ticket->client->name ?? 'N/A', 0, 1) }}</span>
                                            </div>
                                                    <span class="fw-medium">{{ $ticket->client->name ?? 'N/A' }}</span>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                @switch($ticket->status)
                                                    @case('aberto')
                                                        <span class="badge bg-warning text-dark">Aberto</span>
                                                        @break
                                                    @case('em_andamento')
                                                        <span class="badge bg-info">Em Andamento</span>
                                                        @break
                                                    @case('resolvido')
                                                        <span class="badge bg-success">Resolvido</span>
                                                        @break
                                                    @case('fechado')
                                                        <span class="badge bg-secondary">Fechado</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-light text-dark">{{ ucfirst($ticket->status) }}</span>
                                                @endswitch
                                            </td>
                                            <td class="align-middle">
                                                @switch($ticket->priority)
                                                    @case('baixa')
                                                        <span class="badge bg-success">Baixa</span>
                                                        @break
                                                    @case('média')
                                                        <span class="badge bg-warning text-dark">Média</span>
                                                        @break
                                                    @case('alta')
                                                        <span class="badge bg-danger">Alta</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-light text-dark">{{ ucfirst($ticket->priority) }}</span>
                                                @endswitch
                                            </td>
                                            <td class="align-middle">
                                                <span class="dashboard-subtitle">{{ $ticket->created_at->diffForHumans() }}</span>
                                            </td>
                                            <td class="align-middle" onclick="event.stopPropagation();">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('tickets.show', $ticket->ticket_number) }}" class="btn btn-secondary btn-sm">
                                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                    </a>
                                                    <a href="{{ route('tickets.edit', $ticket->ticket_number) }}" class="btn btn-secondary btn-sm">
                                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="empty-state">
                            <svg width="48" height="48" fill="none" stroke="currentColor" class="mb-3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h6>Nenhum ticket encontrado</h6>
                            <p>Crie seu primeiro ticket para começar</p>
                            <a href="{{ route('tickets.create') }}" class="btn btn-primary">Criar Ticket</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de Status
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    const statusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Abertos', 'Em Andamento', 'Resolvidos', 'Fechados'],
            datasets: [{
                data: [
                    {{ $stats['open'] }},
                    {{ $stats['active'] }},
                    {{ $stats['resolved'] }},
                    {{ $stats['closed'] ?? 0 }}
                ],
                backgroundColor: [
                    '#F59E0B', // amarelo sóbrio
                    '#6366F1', // roxo moderno
                    '#8B5CF6', // roxo secundário
                    '#E5E7EB'  // gray neutro
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 16,
                        color: '#374151',
                        font: {
                            size: 13,
                            weight: '500'
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection
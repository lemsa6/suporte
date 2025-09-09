@extends('layouts.app')

@section('title', 'Relatórios')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <span class="text-sm text-gray-500">Relatórios</span>
        <h1 class="page-title mt-1">Relatórios e Estatísticas</h1>
        <p class="text-gray-600 mt-2">Visualize relatórios e estatísticas do sistema</p>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Estatísticas Gerais -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-stat-card 
            title="Total de Tickets"
            :value="$stats['total_tickets'] ?? 0"
            color="primary"
        >
            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </x-stat-card>

        <x-stat-card 
            title="Resolvidos"
            :value="$stats['resolved_tickets'] ?? 0"
            color="success"
        >
            <svg class="w-5 h-5 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </x-stat-card>

        <x-stat-card 
            title="Total de Clientes"
            :value="$stats['total_clients'] ?? 0"
            color="info"
        >
            <svg class="w-5 h-5 text-info-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
        </x-stat-card>

        <x-stat-card 
            title="Tempo Médio"
            :value="$stats['avg_response_time'] ?? 'N/A'"
            color="warning"
        >
            <svg class="w-5 h-5 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </x-stat-card>
    </div>

    <!-- Relatórios Disponíveis -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Relatório de Tickets -->
        <x-card title="Relatório de Tickets">
            <p class="text-gray-600 mb-4">Analise o desempenho dos tickets por período, status e prioridade.</p>
            <div class="space-y-2">
                <x-button variant="outline" tag="a" href="{{ route('reports.tickets') }}" class="w-full">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Ver Relatório de Tickets
                </x-button>
                <div class="text-gray-500 text-sm">
                    Inclui: Status, prioridade, tempo de resolução, categorias
                </div>
            </div>
        </x-card>

        <!-- Relatório de Clientes -->
        <x-card title="Relatório de Clientes">
            <p class="text-gray-600 mb-4">Visualize estatísticas sobre clientes e seu engajamento.</p>
            <div class="space-y-2">
                <x-button variant="outline" tag="a" href="{{ route('reports.clients') }}" class="w-full">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Ver Relatório de Clientes
                </x-button>
                <div class="text-gray-500 text-sm">
                    Inclui: Atividade, volume de tickets, satisfação
                </div>
            </div>
        </x-card>

        <!-- Relatório de Performance -->
        <x-card title="Relatório de Performance">
            <p class="text-gray-600 mb-4">Acompanhe métricas de performance da equipe de suporte.</p>
            <div class="space-y-2">
                <x-button variant="outline" disabled class="w-full">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Em Desenvolvimento
                </x-button>
                <div class="text-gray-500 text-sm">
                    Inclui: Tempo de resposta, SLA, produtividade
                </div>
            </div>
        </x-card>

        <!-- Relatório de Tendências -->
        <x-card title="Relatório de Tendências">
            <p class="text-gray-600 mb-4">Analise tendências e padrões ao longo do tempo.</p>
            <div class="space-y-2">
                <x-button variant="outline" disabled class="w-full">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                    </svg>
                    Em Desenvolvimento
                </x-button>
                <div class="text-gray-500 text-sm">
                    Inclui: Sazonalidade, crescimento, padrões
                </div>
            </div>
        </x-card>
    </div>

    <!-- Filtros de Período -->
    <div class="card border-0">
        <div class="card-header">
            <h5 class="mb-0 fw-semibold">Filtros de Período</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.index') }}" class="row g-3">
                <div class="col-12 col-sm-6 col-lg-3">
                    <label for="date_from" class="form-label">Data Inicial</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="form-control">
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <label for="date_to" class="form-label">Data Final</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="form-control">
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <label for="group_by" class="form-label">Agrupar por</label>
                    <select name="group_by" id="group_by" class="form-select">
                        <option value="day" {{ request('group_by') == 'day' ? 'selected' : '' }}>Dia</option>
                        <option value="week" {{ request('group_by') == 'week' ? 'selected' : '' }}>Semana</option>
                        <option value="month" {{ request('group_by') == 'month' ? 'selected' : '' }}>Mês</option>
                        <option value="quarter" {{ request('group_by') == 'quarter' ? 'selected' : '' }}>Trimestre</option>
                        <option value="year" {{ request('group_by') == 'year' ? 'selected' : '' }}>Ano</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-lg-3 d-flex align-items-end">
                    <x-button variant="primary" type="submit">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Aplicar Filtros
                    </x-button>
                </div>
            </form>
        </div>
    </div>

    <!-- Gráficos Rápidos -->
    @if(isset($charts))
    <div class="row g-4">
        <!-- Gráfico de Status -->
        <div class="col-12 col-lg-6">
            <div class="card border-0">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-semibold">Status dos Tickets</h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Gráfico de Prioridades -->
        <div class="col-12 col-lg-6">
            <div class="card border-0">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-semibold">Prioridades dos Tickets</h5>
                </div>
                <div class="card-body">
                    <canvas id="priorityChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Exportação -->
    <div class="card border-0">
        <div class="card-header">
            <h5 class="mb-0 fw-semibold">Exportar Relatórios</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12 col-sm-6 col-lg-3">
                    <x-button variant="outline" class="w-100" onclick="exportReport('excel')">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Excel
                    </x-button>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <x-button variant="outline" class="w-100" onclick="exportReport('pdf')">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        PDF
                    </x-button>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <x-button variant="outline" class="w-100" onclick="exportReport('csv')">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        CSV
                    </x-button>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <x-button variant="outline" class="w-100" onclick="exportReport('json')">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                        </svg>
                        JSON
                    </x-button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Função para exportar relatórios
function exportReport(format) {
    const dateFrom = document.getElementById('date_from').value;
    const dateTo = document.getElementById('date_to').value;
    const groupBy = document.getElementById('group_by').value;
    
    let url = `/reports/export?format=${format}`;
    if (dateFrom) url += `&date_from=${dateFrom}`;
    if (dateTo) url += `&date_to=${dateTo}`;
    if (groupBy) url += `&group_by=${groupBy}`;
    
    window.open(url, '_blank');
}

// Inicializar gráficos se existirem
document.addEventListener('DOMContentLoaded', function() {
    @if(isset($charts))
        // Gráfico de Status
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Abertos', 'Em Andamento', 'Resolvidos', 'Fechados'],
                datasets: [{
                    data: [{{ $charts['status'] ?? '0,0,0,0' }}],
                    backgroundColor: ['#fbbf24', '#3b82f6', '#10b981', '#6b7280'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Gráfico de Prioridades
        const priorityCtx = document.getElementById('priorityChart').getContext('2d');
        new Chart(priorityCtx, {
            type: 'bar',
            data: {
                labels: ['Baixa', 'Média', 'Alta'],
                datasets: [{
                    label: 'Quantidade',
                    data: [{{ $charts['priority'] ?? '0,0,0' }}],
                    backgroundColor: ['#10b981', '#fbbf24', '#ef4444'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    @endif
});
</script>
@endpush
@endsection

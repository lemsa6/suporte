@extends('layouts.app')

@section('title', 'Relatórios')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <span class="text-sm text-cinza-claro">Relatórios</span>
        <h1 class="page-title mt-1">Relatórios e Estatísticas</h1>
        <p class="text-cinza mt-2">Visualize relatórios e estatísticas do sistema</p>
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
    <h2 class="section-title mb-6">Relatórios Disponíveis</h2>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Relatório de Tickets -->
        <div class="report-card-container">
            <div class="report-card-header">
                <div class="report-card-icon">
                    <svg class="w-8 h-8 text-roxo" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="report-card-title-section">
                    <h3 class="report-card-title">Relatório de Tickets</h3>
                    <p class="report-card-subtitle">Análise de desempenho e métricas</p>
                </div>
            </div>
            <x-card class="report-card-content">
                <p class="report-card-description">Analise o desempenho dos tickets por período, status e prioridade com gráficos detalhados e insights.</p>
                <div class="report-card-features">
                    <div class="feature-tag">Status</div>
                    <div class="feature-tag">Prioridade</div>
                    <div class="feature-tag">Tempo de Resolução</div>
                    <div class="feature-tag">Categorias</div>
                </div>
                <div class="report-card-actions">
                    <x-button variant="primary" tag="a" href="{{ route('reports.tickets') }}" class="w-full">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Ver Relatório de Tickets
                    </x-button>
                </div>
            </x-card>
        </div>

        <!-- Relatório de Clientes -->
        <div class="report-card-container">
            <div class="report-card-header">
                <div class="report-card-icon">
                    <svg class="w-8 h-8 text-roxo" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="report-card-title-section">
                    <h3 class="report-card-title">Relatório de Clientes</h3>
                    <p class="report-card-subtitle">Estatísticas e engajamento</p>
                </div>
            </div>
            <x-card class="report-card-content">
                <p class="report-card-description">Visualize estatísticas sobre clientes e seu engajamento com o sistema de suporte.</p>
                <div class="report-card-features">
                    <div class="feature-tag">Atividade</div>
                    <div class="feature-tag">Volume de Tickets</div>
                    <div class="feature-tag">Satisfação</div>
                    <div class="feature-tag">Tendências</div>
                </div>
                <div class="report-card-actions">
                    <x-button variant="primary" tag="a" href="{{ route('reports.clients') }}" class="w-full">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Ver Relatório de Clientes
                    </x-button>
                </div>
            </x-card>
        </div>

        <!-- Relatório de Performance -->
        <div class="report-card-container">
            <div class="report-card-header">
                <div class="report-card-icon">
                    <svg class="w-8 h-8 text-roxo" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div class="report-card-title-section">
                    <h3 class="report-card-title">Relatório de Performance</h3>
                    <p class="report-card-subtitle">Métricas da equipe</p>
                </div>
            </div>
            <x-card class="report-card-content">
                <p class="report-card-description">Acompanhe métricas de performance da equipe de suporte e indicadores de qualidade.</p>
                <div class="report-card-features">
                    <div class="feature-tag">Tempo de Resposta</div>
                    <div class="feature-tag">SLA</div>
                    <div class="feature-tag">Produtividade</div>
                    <div class="feature-tag">Eficiência</div>
                </div>
                <div class="report-card-actions">
                    <x-button variant="outline" disabled class="w-full">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Em Desenvolvimento
                    </x-button>
                </div>
            </x-card>
        </div>

        <!-- Relatório de Tendências -->
        <div class="report-card-container">
            <div class="report-card-header">
                <div class="report-card-icon">
                    <svg class="w-8 h-8 text-roxo" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                    </svg>
                </div>
                <div class="report-card-title-section">
                    <h3 class="report-card-title">Relatório de Tendências</h3>
                    <p class="report-card-subtitle">Análise temporal</p>
                </div>
            </div>
            <x-card class="report-card-content">
                <p class="report-card-description">Analise tendências e padrões ao longo do tempo para insights estratégicos.</p>
                <div class="report-card-features">
                    <div class="feature-tag">Sazonalidade</div>
                    <div class="feature-tag">Crescimento</div>
                    <div class="feature-tag">Padrões</div>
                    <div class="feature-tag">Previsões</div>
                </div>
                <div class="report-card-actions">
                    <x-button variant="outline" disabled class="w-full">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                        </svg>
                        Em Desenvolvimento
                    </x-button>
                </div>
            </x-card>
        </div>
    </div>

    <!-- Filtros de Período -->
    <h2 class="section-title mb-4">Filtros de Período</h2>
    <x-card>
        <form method="GET" action="{{ route('reports.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label for="date_from" class="block text-sm font-medium text-cinza mb-2">Data Inicial</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" 
                       class="w-full px-3 py-2 border border-cinza-claro rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent">
            </div>
            <div>
                <label for="date_to" class="block text-sm font-medium text-cinza mb-2">Data Final</label>
                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" 
                       class="w-full px-3 py-2 border border-cinza-claro rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent">
            </div>
            <div>
                <label for="group_by" class="block text-sm font-medium text-cinza mb-2">Agrupar por</label>
                <select name="group_by" id="group_by" 
                        class="w-full px-3 py-2 border border-cinza-claro rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent">
                    <option value="day" {{ request('group_by') == 'day' ? 'selected' : '' }}>Dia</option>
                    <option value="week" {{ request('group_by') == 'week' ? 'selected' : '' }}>Semana</option>
                    <option value="month" {{ request('group_by') == 'month' ? 'selected' : '' }}>Mês</option>
                    <option value="quarter" {{ request('group_by') == 'quarter' ? 'selected' : '' }}>Trimestre</option>
                    <option value="year" {{ request('group_by') == 'year' ? 'selected' : '' }}>Ano</option>
                </select>
            </div>
            <div class="flex items-end">
                <x-button variant="primary" type="submit" class="w-full">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Aplicar Filtros
                </x-button>
            </div>
        </form>
    </x-card>

    <!-- Gráficos Rápidos -->
    @if(isset($charts))
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Gráfico de Status -->
        <x-card title="Status dos Tickets">
            <canvas id="statusChart" width="400" height="200"></canvas>
        </x-card>

        <!-- Gráfico de Prioridades -->
        <x-card title="Prioridades dos Tickets">
            <canvas id="priorityChart" width="400" height="200"></canvas>
        </x-card>
    </div>
    @endif

    <!-- Exportação -->
    <h2 class="section-title mb-6">Exportar Relatórios</h2>
    <x-card>
        <div class="p-6">
            <p class="text-cinza mb-6 text-center">Baixe seus relatórios nos formatos mais utilizados</p>
            <div class="export-buttons">
                <button class="export-button" onclick="exportReport('excel')">
                    <svg class="export-button-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="export-button-text">Excel</span>
                </button>
                <button class="export-button" onclick="exportReport('pdf')">
                    <svg class="export-button-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <span class="export-button-text">PDF</span>
                </button>
                <button class="export-button" onclick="exportReport('csv')">
                    <svg class="export-button-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="export-button-text">CSV</span>
                </button>
                <button class="export-button" onclick="exportReport('json')">
                    <svg class="export-button-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                    </svg>
                    <span class="export-button-text">JSON</span>
                </button>
            </div>
        </div>
    </x-card>
</div>

@push('styles')
<style>
/* Report Cards Styling */
.report-card-container {
    @apply space-y-4;
}

.report-card-header {
    @apply flex items-start space-x-4;
}

.report-card-icon {
    @apply flex-shrink-0 w-12 h-12 bg-roxo/10 rounded-xl flex items-center justify-center;
}

.report-card-title-section {
    @apply flex-1;
}

.report-card-title {
    @apply text-xl font-semibold text-cinza mb-1;
}

.report-card-subtitle {
    @apply text-sm text-cinza-claro;
}

.report-card-content {
    @apply p-6;
}

.report-card-description {
    @apply text-cinza mb-4 leading-relaxed;
}

.report-card-features {
    @apply flex flex-wrap gap-2 mb-6;
}

.feature-tag {
    @apply inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-roxo/10 text-roxo border border-roxo/20;
}

.report-card-actions {
    @apply mt-auto;
}

/* Melhorar espaçamento geral */
.section-title {
    @apply text-2xl font-bold text-cinza;
}

/* Melhorar cards de estatísticas */
.stat-card {
    @apply transition-all duration-200 hover:shadow-lg hover:-translate-y-1;
}

/* Melhorar botões de exportação */
.export-buttons {
    @apply grid grid-cols-2 md:grid-cols-4 gap-4;
}

.export-button {
    @apply flex flex-col items-center p-4 border border-cinza-claro rounded-lg hover:border-roxo hover:bg-roxo/5 transition-all duration-200;
}

.export-button-icon {
    @apply w-8 h-8 text-cinza-claro mb-2;
}

.export-button:hover .export-button-icon {
    @apply text-roxo;
}

.export-button-text {
    @apply text-sm font-medium text-cinza;
}
</style>
@endpush

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
                    backgroundColor: ['#fbbf24', '#7c55c3', '#10b981', '#6b7280'],
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

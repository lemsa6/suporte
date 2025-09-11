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
            <svg class="w-5 h-5 text-roxo" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </x-stat-card>

        <x-stat-card 
            title="Resolvidos"
            :value="$stats['resolved_tickets'] ?? 0"
            color="success"
        >
            <svg class="w-5 h-5 text-verde" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </x-stat-card>

        <x-stat-card 
            title="Total de Clientes"
            :value="$stats['total_clients'] ?? 0"
            color="info"
        >
            <svg class="w-5 h-5 text-roxo-det" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
        </x-stat-card>

        <x-stat-card 
            title="Tempo Médio"
            :value="$stats['avg_response_time'] ?? 'N/A'"
            color="warning"
        >
            <svg class="w-5 h-5 text-amarelo" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </x-stat-card>
    </div>

    <!-- Relatórios Disponíveis -->
    <h2 class="section-title mb-4">Relatórios Disponíveis</h2>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Relatório de Tickets -->
        <x-card>
            <div class="flex items-start space-x-4 mb-4">
                <div class="flex-shrink-0 w-12 h-12 bg-roxo bg-opacity-10 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-roxo" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-medium text-cinza mb-1">Relatório de Tickets</h3>
                    <p class="text-sm text-cinza-claro">Análise de desempenho e métricas</p>
                </div>
            </div>
            
            <p class="text-cinza mb-4">Analise o desempenho dos tickets por período, status e prioridade com gráficos detalhados e insights.</p>
            
            <div class="flex flex-wrap gap-2 mb-6">
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-cinza-claro-2 text-cinza">Status</span>
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-cinza-claro-2 text-cinza">Prioridade</span>
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-cinza-claro-2 text-cinza">Tempo de Resolução</span>
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-cinza-claro-2 text-cinza">Categorias</span>
            </div>
            
            <x-button variant="primary" tag="a" href="{{ route('reports.tickets') }}" class="w-full">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Ver Relatório de Tickets
            </x-button>
        </x-card>

        <!-- Relatório de Clientes -->
        <x-card>
            <div class="flex items-start space-x-4 mb-4">
                <div class="flex-shrink-0 w-12 h-12 bg-roxo bg-opacity-10 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-roxo" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-medium text-cinza mb-1">Relatório de Clientes</h3>
                    <p class="text-sm text-cinza-claro">Estatísticas e engajamento</p>
                </div>
            </div>
            
            <p class="text-cinza mb-4">Visualize estatísticas sobre clientes e seu engajamento com o sistema de suporte.</p>
            
            <div class="flex flex-wrap gap-2 mb-6">
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-cinza-claro-2 text-cinza">Atividade</span>
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-cinza-claro-2 text-cinza">Volume de Tickets</span>
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-cinza-claro-2 text-cinza">Satisfação</span>
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-cinza-claro-2 text-cinza">Tendências</span>
            </div>
            
            <x-button variant="primary" tag="a" href="{{ route('reports.clients') }}" class="w-full">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Ver Relatório de Clientes
            </x-button>
        </x-card>

        <!-- Relatório de Performance -->
        <x-card>
            <div class="flex items-start space-x-4 mb-4">
                <div class="flex-shrink-0 w-12 h-12 bg-cinza-claro-2 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-cinza-claro" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-medium text-cinza-claro mb-1">Relatório de Performance</h3>
                    <p class="text-sm text-cinza-claro">Métricas da equipe</p>
                </div>
            </div>
            
            <p class="text-cinza-claro mb-4">Acompanhe métricas de performance da equipe de suporte e indicadores de qualidade.</p>
            
            <div class="flex flex-wrap gap-2 mb-6">
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-cinza-claro-2 text-cinza-claro">Tempo de Resposta</span>
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-cinza-claro-2 text-cinza-claro">SLA</span>
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-cinza-claro-2 text-cinza-claro">Produtividade</span>
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-cinza-claro-2 text-cinza-claro">Eficiência</span>
            </div>
            
            <x-button variant="outline" disabled class="w-full">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                Em Desenvolvimento
            </x-button>
        </x-card>

        <!-- Relatório de Tendências -->
        <x-card>
            <div class="flex items-start space-x-4 mb-4">
                <div class="flex-shrink-0 w-12 h-12 bg-cinza-claro-2 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-cinza-claro" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-medium text-cinza-claro mb-1">Relatório de Tendências</h3>
                    <p class="text-sm text-cinza-claro">Análise temporal</p>
                </div>
            </div>
            
            <p class="text-cinza-claro mb-4">Analise tendências e padrões ao longo do tempo para insights estratégicos.</p>
            
            <div class="flex flex-wrap gap-2 mb-6">
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-cinza-claro-2 text-cinza-claro">Sazonalidade</span>
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-cinza-claro-2 text-cinza-claro">Crescimento</span>
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-cinza-claro-2 text-cinza-claro">Padrões</span>
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-cinza-claro-2 text-cinza-claro">Previsões</span>
            </div>
            
            <x-button variant="outline" disabled class="w-full">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                </svg>
                Em Desenvolvimento
            </x-button>
        </x-card>
    </div>

    <!-- Filtros de Período -->
    <h2 class="section-title mb-4">Filtros de Período</h2>
    <x-card>
        <form method="GET" action="{{ route('reports.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label for="date_from" class="block text-sm font-medium text-cinza mb-2">Data Inicial</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" 
                       class="form-input">
            </div>
            <div>
                <label for="date_to" class="block text-sm font-medium text-cinza mb-2">Data Final</label>
                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" 
                       class="form-input">
            </div>
            <div>
                <label for="group_by" class="block text-sm font-medium text-cinza mb-2">Agrupar por</label>
                <select name="group_by" id="group_by" class="form-select">
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
    <h2 class="section-title mb-4">Gráficos</h2>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <x-card title="Status dos Tickets">
            <canvas id="statusChart" width="400" height="200"></canvas>
        </x-card>

        <x-card title="Prioridades dos Tickets">
            <canvas id="priorityChart" width="400" height="200"></canvas>
        </x-card>
    </div>
    @endif

    <!-- Exportação -->
    <h2 class="section-title mb-4">Exportar Relatórios</h2>
    <x-card>
        <p class="text-cinza mb-6 text-center">Baixe seus relatórios nos formatos mais utilizados</p>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <x-button variant="outline" onclick="exportReport('excel')" class="flex-col h-20">
                <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Excel
            </x-button>
            <x-button variant="outline" onclick="exportReport('pdf')" class="flex-col h-20">
                <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                PDF
            </x-button>
            <x-button variant="outline" onclick="exportReport('csv')" class="flex-col h-20">
                <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                CSV
            </x-button>
            <x-button variant="outline" onclick="exportReport('json')" class="flex-col h-20">
                <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                </svg>
                JSON
            </x-button>
        </div>
    </x-card>
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
                    backgroundColor: ['#f0ba00', '#7c55c3', '#55c38e', '#5a5a5a'],
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
                    backgroundColor: ['#55c38e', '#f0ba00', '#ef4444'],
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
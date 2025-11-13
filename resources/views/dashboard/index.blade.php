@extends('layouts.app')

@section('title', 'Dashboard')

@section('header')
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div class="mb-4 sm:mb-0">
            <span class="text-sm text-cinza-claro">Dashboard</span>
            <h1 class="page-title mt-1">Bem-vindo, {{ auth()->user()->name }}!</h1>
        </div>
        <x-button 
            variant="primary" 
            tag="a"
            href="{{ route('tickets.create') }}"
        >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Novo Ticket
        </x-button>
    </div>
</div>
@endsection

@section('content')
<div class="dashboard-content space-y-6">
    
    <!-- ALERTAS CRÍTICOS (Topo - Destaque) -->
    @if(count($criticalAlerts) > 0)
    <div class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($criticalAlerts as $alert)
            <div class="bg-{{ $alert['color'] }}-50 border border-{{ $alert['color'] }}-200 rounded-lg p-4 cursor-pointer hover:bg-{{ $alert['color'] }}-100 transition-colors"
                 onclick="window.location.href='{{ $alert['action'] }}'">
                <div class="flex items-center">
                    <span class="text-2xl mr-3">{{ $alert['icon'] }}</span>
                    <div>
                        <h3 class="font-semibold text-{{ $alert['color'] }}-800">{{ $alert['title'] }}</h3>
                        <p class="text-{{ $alert['color'] }}-600">{{ $alert['count'] }} {{ $alert['count'] == 1 ? 'item' : 'itens' }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- MÉTRICAS PRINCIPAIS (Mantidas) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <x-stat-card 
            title="Total de Tickets" 
            :value="$stats['total']" 
            icon="📊"
            color="blue"
        />
        <x-stat-card 
            title="Abertos" 
            :value="$stats['open']" 
            icon="📋"
            color="blue"
        />
        <x-stat-card 
            title="Urgentes" 
            :value="$stats['urgent']" 
            icon="🚨"
            color="red"
        />
        <x-stat-card 
            title="Resolvidos" 
            :value="$stats['resolved']" 
            icon="✅"
            color="green"
        />
    </div>

    <!-- WIDGETS ACIONÁVEIS (Principal) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        
        <!-- TICKETS MAIS ANTIGOS -->
        <div>
            <h2 class="section-title mb-4">Tickets Mais Antigos</h2>
            <x-card class="h-full">
                @if($oldestTickets->count() > 0)
                    <div class="space-y-3">
                        @foreach($oldestTickets as $ticket)
                        <div class="p-3 border border-padrao rounded-lg hover:bg-creme cursor-pointer transition-colors"
                             onclick="window.location.href='{{ route('tickets.show', $ticket->ticket_number) }}'">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-medium text-cinza">#{{ $ticket->ticket_number }}</span>
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-{{ $ticket->priority_color }}-100 text-{{ $ticket->priority_color }}-800">
                                        {{ ucfirst($ticket->priority) }}
                                    </span>
                                    <span class="text-xs text-cinza-claro">{{ $ticket->days_open }}d</span>
                                </div>
                            </div>
                            <p class="text-sm text-cinza-claro truncate">{{ $ticket->title }}</p>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-cinza-claro">Nenhum ticket antigo</p>
                    </div>
                @endif
            </x-card>
        </div>

        <!-- MINHA FILA DE TRABALHO -->
        @if(auth()->user()->canManageTickets())
        <div>
            <h2 class="section-title mb-4">Minha Fila de Trabalho</h2>
            <x-card class="h-full">
                @if($myWorkQueue->count() > 0)
                    <div class="space-y-3">
                        @foreach($myWorkQueue as $ticket)
                        <div class="p-3 border border-padrao rounded-lg hover:bg-creme cursor-pointer transition-colors"
                             onclick="window.location.href='{{ route('tickets.show', $ticket->ticket_number) }}'">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-medium text-cinza">#{{ $ticket->ticket_number }}</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-{{ $ticket->priority_color }}-100 text-{{ $ticket->priority_color }}-800">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </div>
                            <p class="text-sm text-cinza-claro truncate">{{ $ticket->title }}</p>
                            <p class="text-xs text-cinza-claro mt-1">{{ $ticket->opened_at->diffForHumans() }}</p>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-cinza-claro">Fila vazia</p>
                    </div>
                @endif
            </x-card>
        </div>
        @endif

        <!-- AÇÕES RÁPIDAS -->
        <div>
            <h2 class="section-title mb-4">Ações Rápidas</h2>
            <x-card class="h-full">
                <div class="space-y-3">
                    @foreach($quickActions as $action)
                    <div class="p-4 border border-padrao rounded-lg hover:bg-creme cursor-pointer transition-colors"
                         onclick="window.location.href='{{ $action['action'] }}'">
                        <div>
                            <h3 class="font-medium text-cinza">{{ $action['title'] }}</h3>
                            <p class="text-sm text-cinza-claro">{{ $action['subtitle'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </x-card>
        </div>
    </div>

    <!-- TICKETS RECENTES (Otimizado) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div>
            <h2 class="section-title mb-4">Tickets Recentes</h2>
            <x-card>
                @if($recentTickets->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentTickets as $ticket)
                        <div class="p-3 border border-padrao rounded-lg hover:bg-creme cursor-pointer transition-colors"
                             onclick="window.location.href='{{ route('tickets.show', $ticket->ticket_number) }}'">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-medium text-cinza">#{{ $ticket->ticket_number }}</span>
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-{{ $ticket->status_color }}-100 text-{{ $ticket->status_color }}-800">
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-{{ $ticket->priority_color }}-100 text-{{ $ticket->priority_color }}-800">
                                        {{ ucfirst($ticket->priority) }}
                                    </span>
                                </div>
                            </div>
                            <p class="text-sm text-cinza-claro truncate">{{ $ticket->title }}</p>
                            <p class="text-xs text-cinza-claro mt-1">{{ $ticket->updated_at->diffForHumans() }}</p>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-cinza-claro">Nenhum ticket recente</p>
                    </div>
                @endif
            </x-card>
        </div>

        <!-- ESTATÍSTICAS RÁPIDAS (Mantidas) -->
        <div>
            <h2 class="section-title mb-4">Estatísticas Rápidas</h2>
            <x-card>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-cinza-claro">Tempo de Resposta</span>
                        <span class="font-semibold text-cinza">{{ $quickStats['avg_response_time'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-cinza-claro">Taxa de Resolução</span>
                        <span class="font-semibold text-cinza">{{ $quickStats['resolution_rate'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-cinza-claro">Resolvidos Hoje</span>
                        <span class="font-semibold text-cinza">{{ $quickStats['resolved_today'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-cinza-claro">Criados esta Semana</span>
                        <span class="font-semibold text-cinza">{{ $quickStats['created_this_week'] }}</span>
                    </div>
                </div>
            </x-card>
        </div>
    </div>

    <!-- GRÁFICOS (Mantidos) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Status dos Tickets -->
        <div>
            <h2 class="section-title mb-4">Status dos Tickets</h2>
            <x-card>
                <div class="chart-container">
                    <canvas id="statusChart" width="400" height="200"></canvas>
                </div>
            </x-card>
        </div>

        <!-- Tickets por Prioridade -->
        <div>
            <h2 class="section-title mb-4">Tickets por Prioridade</h2>
            <x-card>
                <div class="chart-container">
                    <canvas id="priorityChart" width="400" height="200"></canvas>
                </div>
            </x-card>
        </div>
    </div>

    <!-- ÚLTIMOS LOGINS (Admin) -->
    @if(auth()->user()->isAdmin() && $recentLogins->count() > 0)
    <div class="mt-6">
        <h2 class="section-title mb-4">Últimos Logins</h2>
        <x-card>
            <div class="space-y-3">
                @foreach($recentLogins as $login)
                <div class="flex items-center justify-between p-3 border border-padrao rounded-lg">
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full mr-3 {{ $login->event_type == 'login_success' ? 'bg-green-500' : 'bg-red-500' }}"></div>
                        <span class="text-sm font-medium text-cinza mr-2">
                            {{ $login->event_type == 'login_success' ? 'Entrou' : 'Saiu' }}
                        </span>
                        <span class="text-cinza-claro">{{ $login->user->name ?? 'Usuário #' . $login->user_id }}</span>
                    </div>
                    <span class="text-xs text-cinza-claro">{{ $login->created_at->diffForHumans() }}</span>
                </div>
                @endforeach
            </div>
        </x-card>
    </div>
    @endif

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de Status
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Aberto', 'Em Progresso', 'Resolvido', 'Fechado'],
            datasets: [{
                data: [
                    {{ $ticketsByStatus['aberto'] }},
                    {{ $ticketsByStatus['em_andamento'] }},
                    {{ $ticketsByStatus['resolvido'] }},
                    {{ $ticketsByStatus['fechado'] }}
                ],
                backgroundColor: ['#3B82F6', '#F59E0B', '#10B981', '#6B7280']
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

    // Gráfico de Prioridade
    const priorityCtx = document.getElementById('priorityChart').getContext('2d');
    new Chart(priorityCtx, {
        type: 'bar',
        data: {
            labels: ['Baixa', 'Média', 'Alta'],
            datasets: [{
                label: 'Tickets',
                data: [
                    {{ $ticketsByPriority['baixa'] }},
                    {{ $ticketsByPriority['média'] }},
                    {{ $ticketsByPriority['alta'] }}
                ],
                backgroundColor: ['#10B981', '#F59E0B', '#EF4444']
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
});
</script>
@endpush
@endsection
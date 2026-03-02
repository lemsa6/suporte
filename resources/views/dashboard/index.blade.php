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
<div class="dashboard-content space-y-8">
    
    @if(count($criticalAlerts) > 0)
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
    @endif

    <!-- METRICAS PRINCIPAIS -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <x-stat-card title="Total de Tickets" :value="$stats['total']" color="primary">
            <svg class="w-5 h-5 text-roxo" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
        </x-stat-card>
        <x-stat-card title="Abertos" :value="$stats['open']" color="warning">
            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </x-stat-card>
        <x-stat-card title="Urgentes" :value="$stats['urgent']" color="danger">
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
        </x-stat-card>
        <x-stat-card title="Resolvidos" :value="$stats['resolved']" color="success">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </x-stat-card>
    </div>

    <!-- SLA + TIMELINE -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- SLA COMPLIANCE -->
        <x-card title="SLA por Prioridade">
            <div class="space-y-5">
                @foreach(['alta' => 'Alta', 'média' => 'Média', 'baixa' => 'Baixa'] as $key => $label)
                @php $sla = $slaData[$key]; @endphp
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-sm font-medium text-cinza">{{ $label }} <span class="text-cinza-claro font-normal">({{ $sla['target_hours'] }}h)</span></span>
                        <span class="text-sm font-semibold {{ $sla['pct'] >= 80 ? 'text-green-600' : ($sla['pct'] >= 50 ? 'text-yellow-600' : 'text-red-600') }}">{{ $sla['pct'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2.5">
                        <div class="h-2.5 rounded-full transition-all duration-500 {{ $sla['pct'] >= 80 ? 'bg-green-500' : ($sla['pct'] >= 50 ? 'bg-yellow-500' : 'bg-red-500') }}" style="width: {{ $sla['pct'] }}%"></div>
                    </div>
                    <p class="text-xs text-cinza-claro mt-1">{{ $sla['within'] }}/{{ $sla['total'] }} dentro do SLA</p>
                </div>
                @endforeach
                <div class="pt-3 border-t border-padrao">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-cinza">SLA Geral</span>
                        <span class="text-lg font-bold {{ $slaData['overall'] >= 80 ? 'text-green-600' : ($slaData['overall'] >= 50 ? 'text-yellow-600' : 'text-red-600') }}">{{ $slaData['overall'] }}%</span>
                    </div>
                </div>
            </div>
        </x-card>

        <!-- TIMELINE -->
        <div class="lg:col-span-2">
            <x-card title="Fluxo de Tickets (14 dias)">
                <div style="height: 240px; position: relative;">
                    <canvas id="timelineChart"></canvas>
                </div>
            </x-card>
        </div>
    </div>

    <!-- WIDGETS ACIONAVEIS -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <x-card title="Tickets Mais Antigos">
            @if($oldestTickets->count() > 0)
                <div class="space-y-3">
                    @foreach($oldestTickets as $ticket)
                    <div class="p-3 border border-padrao rounded-lg hover:bg-creme cursor-pointer transition-colors"
                         onclick="window.location.href='{{ route('tickets.show', $ticket->ticket_number) }}'">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-medium text-sm text-cinza">#{{ $ticket->ticket_number }}</span>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $ticket->priority_color }}-100 text-{{ $ticket->priority_color }}-800">{{ ucfirst($ticket->priority) }}</span>
                                <span class="text-xs text-cinza-claro">{{ $ticket->days_open }}d</span>
                            </div>
                        </div>
                        <p class="text-sm text-cinza-claro truncate">{{ $ticket->title }}</p>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6"><p class="text-cinza-claro text-sm">Nenhum ticket antigo</p></div>
            @endif
        </x-card>

        @if(auth()->user()->canManageTickets())
        <x-card title="Minha Fila de Trabalho">
            @if($myWorkQueue->count() > 0)
                <div class="space-y-3">
                    @foreach($myWorkQueue as $ticket)
                    <div class="p-3 border border-padrao rounded-lg hover:bg-creme cursor-pointer transition-colors"
                         onclick="window.location.href='{{ route('tickets.show', $ticket->ticket_number) }}'">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-medium text-sm text-cinza">#{{ $ticket->ticket_number }}</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $ticket->priority_color }}-100 text-{{ $ticket->priority_color }}-800">{{ ucfirst($ticket->priority) }}</span>
                        </div>
                        <p class="text-sm text-cinza-claro truncate">{{ $ticket->title }}</p>
                        <p class="text-xs text-cinza-claro mt-1">{{ $ticket->opened_at->diffForHumans() }}</p>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6"><p class="text-cinza-claro text-sm">Fila vazia</p></div>
            @endif
        </x-card>
        @endif

        <x-card title="Resumo & Ações">
            <div class="space-y-4 mb-5">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-cinza-claro">Tempo Médio de Resposta</span>
                    <span class="text-sm font-semibold text-cinza">{{ $quickStats['avg_response_time'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-cinza-claro">Taxa de Resolução</span>
                    <span class="text-sm font-semibold text-cinza">{{ $quickStats['resolution_rate'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-cinza-claro">Resolvidos Hoje</span>
                    <span class="text-sm font-semibold text-cinza">{{ $quickStats['resolved_today'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-cinza-claro">Criados esta Semana</span>
                    <span class="text-sm font-semibold text-cinza">{{ $quickStats['created_this_week'] }}</span>
                </div>
            </div>
            <div class="pt-4 border-t border-padrao space-y-2">
                @foreach($quickActions as $action)
                <a href="{{ $action['action'] }}" class="flex items-center p-3 border border-padrao rounded-lg hover:bg-creme transition-colors">
                    <div>
                        <span class="text-sm font-medium text-cinza">{{ $action['title'] }}</span>
                        <span class="text-xs text-cinza-claro ml-2">{{ $action['subtitle'] }}</span>
                    </div>
                </a>
                @endforeach
            </div>
        </x-card>
    </div>

    <!-- TICKETS RECENTES + GRAFICOS -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <x-card title="Tickets Recentes">
            @if($recentTickets->count() > 0)
                <div class="space-y-3">
                    @foreach($recentTickets as $ticket)
                    <div class="p-3 border border-padrao rounded-lg hover:bg-creme cursor-pointer transition-colors"
                         onclick="window.location.href='{{ route('tickets.show', $ticket->ticket_number) }}'">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-medium text-sm text-cinza">#{{ $ticket->ticket_number }}</span>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $ticket->status_color }}-100 text-{{ $ticket->status_color }}-800">{{ ucfirst($ticket->status) }}</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $ticket->priority_color }}-100 text-{{ $ticket->priority_color }}-800">{{ ucfirst($ticket->priority) }}</span>
                            </div>
                        </div>
                        <p class="text-sm text-cinza-claro truncate">{{ $ticket->title }}</p>
                        <p class="text-xs text-cinza-claro mt-1">{{ $ticket->updated_at->diffForHumans() }}</p>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6"><p class="text-cinza-claro text-sm">Nenhum ticket recente</p></div>
            @endif
        </x-card>

        <x-card title="Status dos Tickets">
            <div style="height: 240px; position: relative;">
                <canvas id="statusChart"></canvas>
            </div>
        </x-card>
    </div>

    @if(auth()->user()->isAdmin() && $recentLogins->count() > 0)
    <x-card title="Últimos Logins">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($recentLogins as $login)
            <div class="flex items-center justify-between p-3 border border-padrao rounded-lg">
                <div class="flex items-center">
                    <div class="w-2.5 h-2.5 rounded-full mr-3 {{ $login->event_type == 'login_success' ? 'bg-green-500' : 'bg-red-500' }}"></div>
                    <div>
                        <span class="text-sm font-medium text-cinza">{{ $login->user->name ?? 'Usuário #' . $login->user_id }}</span>
                        <span class="text-xs text-cinza-claro ml-2">{{ $login->event_type == 'login_success' ? 'Entrou' : 'Saiu' }}</span>
                    </div>
                </div>
                <span class="text-xs text-cinza-claro">{{ $login->created_at->diffForHumans() }}</span>
            </div>
            @endforeach
        </div>
    </x-card>
    @endif

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fontFamily = "'Lato', sans-serif";
    Chart.defaults.font.family = fontFamily;
    Chart.defaults.color = '#a0a0a0';

    // Timeline Chart
    const timelineCtx = document.getElementById('timelineChart');
    if (timelineCtx) {
        new Chart(timelineCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: {!! json_encode(collect($ticketsTimeline)->pluck('label')) !!},
                datasets: [
                    {
                        label: 'Criados',
                        data: {!! json_encode(collect($ticketsTimeline)->pluck('created')) !!},
                        borderColor: '#3d235a',
                        backgroundColor: 'rgba(61, 35, 90, 0.1)',
                        fill: true,
                        tension: 0.3,
                        pointRadius: 3,
                        pointBackgroundColor: '#3d235a',
                    },
                    {
                        label: 'Resolvidos',
                        data: {!! json_encode(collect($ticketsTimeline)->pluck('resolved')) !!},
                        borderColor: '#55c38e',
                        backgroundColor: 'rgba(85, 195, 142, 0.1)',
                        fill: true,
                        tension: 0.3,
                        pointRadius: 3,
                        pointBackgroundColor: '#55c38e',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { intersect: false, mode: 'index' },
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 16 } }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: 'rgba(0,0,0,0.04)' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // Status Doughnut
    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        new Chart(statusCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Aberto', 'Em Progresso', 'Resolvido', 'Fechado'],
                datasets: [{
                    data: [{{ $ticketsByStatus['aberto'] }}, {{ $ticketsByStatus['em_andamento'] }}, {{ $ticketsByStatus['resolvido'] }}, {{ $ticketsByStatus['fechado'] }}],
                    backgroundColor: ['#F59E0B', '#3B82F6', '#10B981', '#9CA3AF'],
                    borderWidth: 0,
                    hoverOffset: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 16 } }
                }
            }
        });
    }
});
</script>
@endpush
@endsection

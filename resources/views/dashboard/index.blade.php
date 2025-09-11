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
    <!-- Tickets Recentes (4/6) + Métricas (2/6) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Tickets Recentes (4/6) -->
        <div class="lg:col-span-2">
            <h2 class="section-title mb-4">Tickets Recentes</h2>
            <x-card>
                @if($recentTickets->count() > 0)
                    <x-table striped hover>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Título</th>
                                <th>Cliente</th>
                                <th>Status</th>
                                <th>Prioridade</th>
                                <th>Criado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentTickets as $ticket)
                                <tr class="table-row-clickable" onclick="window.location.href='{{ route('tickets.show', $ticket->ticket_number ?? $ticket->id) }}'">
                                    <td>
                                        <span class="font-medium">#{{ $ticket->ticket_number ?? $ticket->id }}</span>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="font-medium text-cinza">{{ Str::limit($ticket->title ?? $ticket->subject, 50) }}</div>
                                            <div class="text-cinza-claro text-sm">{{ Str::limit($ticket->description ?? '', 60) }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            <div class="table-avatar">
                                                <span class="table-avatar-text">{{ substr($ticket->client->trade_name ?? $ticket->client->company_name ?? 'N/A', 0, 1) }}</span>
                                            </div>
                                            <span class="font-medium">{{ $ticket->client->trade_name ?? $ticket->client->company_name ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <x-ticket-status-badge :status="$ticket->status" />
                                    </td>
                                    <td>
                                        <x-ticket-priority-badge :priority="$ticket->priority" />
                                    </td>
                                    <td>
                                        <span class="text-cinza-claro">{{ $ticket->created_at->diffForHumans() }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </x-table>
                @else
                    <div class="empty-state">
                        <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="empty-state-text">Nenhum ticket recente</p>
                    </div>
                @endif
            </x-card>
        </div>
        
        <!-- Métricas (2/6) -->
        <div class="lg:col-span-1">
            <h2 class="section-title mb-4">Métricas do Sistema</h2>
            <div class="grid grid-cols-2 gap-4">
                <x-stat-card 
                    title="Total"
                    :value="$stats['total'] ?? 0"
                    color="primary"
                />
                
                <x-stat-card 
                    title="Abertos"
                    :value="$stats['open'] ?? 0"
                    color="warning"
                />
                
                <x-stat-card 
                    title="Urgentes"
                    :value="$stats['urgent'] ?? 0"
                    color="danger"
                />
                
                <x-stat-card 
                    title="Resolvidos"
                    :value="$stats['resolved'] ?? 0"
                    color="success"
                />
            </div>
        </div>
    </div>

    <!-- Gráficos - Tickets por Categoria + Status -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Tickets por Categoria (3/6) -->
        <div class="lg:col-span-1">
            <h2 class="section-title mb-4">Tickets por Categoria</h2>
            <x-card>
                <div class="chart-container">
                    <canvas id="categoryChart"></canvas>
                </div>
            </x-card>
        </div>
        
        <!-- Status dos Tickets (3/6) -->
        <div class="lg:col-span-1">
            <h2 class="section-title mb-4">Status dos Tickets</h2>
            <x-card>
                <div class="chart-container">
                    <canvas id="statusChart"></canvas>
                </div>
            </x-card>
        </div>
    </div>

    <!-- Últimos Logins (4/6) + Estatísticas Rápidas (2/6) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Últimos Logins (4/6) -->
        <div class="lg:col-span-2">
            <h2 class="section-title mb-4">Últimos Logins</h2>
            <x-card>
                @if($recentLogins->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentLogins as $login)
                            <div class="login-item">
                                <div class="login-item-content">
                                    <div class="login-avatar">
                                        <span class="login-avatar-text">{{ substr($login->user->name ?? 'U', 0, 1) }}</span>
                                    </div>
                                    <div class="login-info">
                                        <p class="login-name">{{ $login->user->name ?? 'Usuário' }}</p>
                                        <p class="login-action">{{ $login->event_type == 'login' ? 'Entrou' : 'Saiu' }}</p>
                                    </div>
                                </div>
                                <span class="login-time">{{ $login->created_at->diffForHumans() }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        <p class="empty-state-text">Nenhum login recente</p>
                    </div>
                @endif
            </x-card>
        </div>
        
        <!-- Estatísticas Rápidas (2/6) -->
        <div class="lg:col-span-1">
            <h2 class="section-title mb-4">Estatísticas Rápidas</h2>
            <x-card>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-cinza">Tempo de Resposta</span>
                        <x-badge variant="info">2.4h</x-badge>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-cinza">Taxa de Resolução</span>
                        <x-badge variant="success">94%</x-badge>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-cinza">Satisfação</span>
                        <x-badge variant="primary">4.8/5</x-badge>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gráfico de Status (Doughnut)
        const statusCtx = document.getElementById('statusChart');
        if (statusCtx) {
            const statusChart = new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Aberto', 'Em Progresso', 'Resolvido', 'Fechado'],
                    datasets: [{
                        data: [
                            {{ $stats['open'] ?? 0 }}, 
                            {{ $stats['in_progress'] ?? 0 }}, 
                            {{ $stats['resolved'] ?? 0 }}, 
                            {{ $stats['closed'] ?? 0 }}
                        ],
                        backgroundColor: [
                            '#f59e0b', // Warning - Open
                            '#7c55c3', // Primary - In Progress
                            '#10b981', // Success - Resolved
                            '#64748b'  // Secondary - Closed
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        animateRotate: true,
                        animateScale: false
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                                    return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
        }

        // Gráfico de Categorias (Bar)
        const categoryCtx = document.getElementById('categoryChart');
        if (categoryCtx) {
            const categoryData = @json($ticketsByCategory);
            const labels = Object.keys(categoryData);
            const data = Object.values(categoryData);
            
            const categoryChart = new Chart(categoryCtx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Tickets',
                        data: data,
                        backgroundColor: [
                            '#7c55c3', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#84cc16', '#f97316'
                        ],
                        borderColor: [
                            '#3d235a', '#059669', '#d97706', '#dc2626', '#7c3aed', '#0891b2', '#65a30d', '#ea580c'
                        ],
                        borderWidth: 1,
                        borderRadius: 4,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        animateRotate: false,
                        animateScale: false
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        },
                        x: {
                            ticks: {
                                maxRotation: 45,
                                minRotation: 0
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Client;
use App\Models\Category;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // Middleware aplicado nas rotas

    /**
     * Exibe o dashboard principal
     */
    public function index(Request $request): View
    {
        try {
            $user = auth()->user();
            
            // Estatísticas gerais
            $stats = $this->getGeneralStats($user);
            
            // NOVOS WIDGETS ACIONÁVEIS
            $criticalAlerts = $this->getCriticalAlerts($user);
            $oldestTickets = $this->getOldestTickets($user);
            $myWorkQueue = $this->getMyWorkQueue($user);
            $quickActions = $this->getQuickActions($user);
            
            // Tickets por status
            $ticketsByStatus = $this->getTicketsByStatus($user);
            
            // Tickets por prioridade
            $ticketsByPriority = $this->getTicketsByPriority($user);
            
            // Tickets por categoria
            $ticketsByCategory = $this->getTicketsByCategory($user);
            
            // Últimos tickets (otimizado)
            $recentTickets = $this->getRecentTickets($user);
            
            // Tickets urgentes
            $urgentTickets = $this->getUrgentTickets($user);
            
            // Tickets não atribuídos (apenas para técnicos/admin)
            $unassignedTickets = $user->canManageTickets() ? $this->getUnassignedTickets() : collect();
            
            // Últimos logins (apenas para admin) - com fallback
            $recentLogins = collect();
            if ($user->isAdmin()) {
                try {
                    $recentLogins = $this->getRecentLogins();
                } catch (\Exception $e) {
                    $recentLogins = collect();
                }
            }
            
            // Estatísticas rápidas - com fallback
            $quickStats = [];
            try {
                $quickStats = $this->getQuickStats($user);
            } catch (\Exception $e) {
                $quickStats = [
                    'avg_response_time' => 'N/A',
                    'resolution_rate' => '0%',
                    'resolved_today' => 0,
                    'created_this_week' => 0,
                ];
            }
            
            // SLA compliance data
            $slaData = $this->getSlaData($user);
            
            // Timeline: tickets criados nos ultimos 14 dias
            $ticketsTimeline = $this->getTicketsTimeline($user, 14);
            
            return view('dashboard.index', compact(
                'stats',
                'criticalAlerts',
                'oldestTickets',
                'myWorkQueue',
                'quickActions',
                'ticketsByStatus',
                'ticketsByPriority',
                'ticketsByCategory',
                'recentTickets',
                'urgentTickets',
                'unassignedTickets',
                'recentLogins',
                'quickStats',
                'slaData',
                'ticketsTimeline'
            ));
            
        } catch (\Exception $e) {
            \Log::error('Erro crítico no dashboard: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Fallback com dados mínimos
            return view('dashboard.index', [
                'stats' => ['total' => 0, 'open' => 0, 'in_progress' => 0, 'resolved' => 0, 'closed' => 0, 'urgent' => 0, 'active' => 0],
                'ticketsByStatus' => ['aberto' => 0, 'em_andamento' => 0, 'resolvido' => 0, 'fechado' => 0],
                'ticketsByPriority' => ['baixa' => 0, 'média' => 0, 'alta' => 0],
                'ticketsByCategory' => [],
                'recentTickets' => collect(),
                'urgentTickets' => collect(),
                'unassignedTickets' => collect(),
                'recentLogins' => collect(),
                'quickStats' => ['avg_response_time' => 'N/A', 'resolution_rate' => '0%', 'resolved_today' => 0, 'created_this_week' => 0],
                'slaData' => ['alta' => ['total' => 0, 'within' => 0, 'pct' => 0], 'média' => ['total' => 0, 'within' => 0, 'pct' => 0], 'baixa' => ['total' => 0, 'within' => 0, 'pct' => 0], 'overall' => 0],
                'ticketsTimeline' => [],
            ]);
        }
    }

    /**
     * Obtém estatísticas gerais baseadas no perfil do usuário
     */
    private function getGeneralStats(User $user): array
    {
        $query = $this->getUserTicketQuery($user);
        
        $totalTickets = $query->count();
        $openTickets = (clone $query)->open()->count();
        $inProgressTickets = (clone $query)->inProgress()->count();
        $resolvedTickets = (clone $query)->resolved()->count();
        $closedTickets = (clone $query)->closed()->count();
        $urgentTickets = (clone $query)->urgent()->count();
        
        return [
            'total' => $totalTickets,
            'open' => $openTickets,
            'in_progress' => $inProgressTickets,
            'resolved' => $resolvedTickets,
            'closed' => $closedTickets,
            'urgent' => $urgentTickets,
            'active' => $openTickets + $inProgressTickets,
        ];
    }

    /**
     * Obtém query baseada no perfil do usuário
     */
    private function getUserTicketQuery(User $user)
    {
        $query = Ticket::query();
        
        if ($user->isClienteGestor()) {
            $query->whereHas('client', function ($q) use ($user) {
                $q->whereHas('contacts', function ($q2) use ($user) {
                    $q2->where('email', $user->email);
                });
            });
        } elseif ($user->isClienteFuncionario()) {
            $query->whereHas('contact', function ($q) use ($user) {
                $q->where('email', $user->email);
            });
        } elseif ($user->isTecnico()) {
            $query->where('assigned_to', $user->id);
        }
        
        return $query;
    }

    /**
     * Obtém contagem de tickets por status
     */
    private function getTicketsByStatus(User $user): array
    {
        $query = Ticket::query();
        
        if ($user->isClienteGestor()) {
            $query->whereHas('client', function ($q) use ($user) {
                $q->whereHas('contacts', function ($q2) use ($user) {
                    $q2->where('email', $user->email);
                });
            });
        } elseif ($user->isClienteFuncionario()) {
            $query->whereHas('contact', function ($q) use ($user) {
                $q->where('email', $user->email);
            });
        } elseif ($user->isTecnico()) {
            $query->where('assigned_to', $user->id);
        }
        
        return [
            'aberto' => (clone $query)->open()->count(),
            'em_andamento' => (clone $query)->inProgress()->count(),
            'resolvido' => (clone $query)->resolved()->count(),
            'fechado' => (clone $query)->closed()->count(),
        ];
    }

    /**
     * Obtém contagem de tickets por prioridade
     */
    private function getTicketsByPriority(User $user): array
    {
        $query = Ticket::query()->active();
        
        if ($user->isClienteGestor()) {
            $query->whereHas('client', function ($q) use ($user) {
                $q->whereHas('contacts', function ($q2) use ($user) {
                    $q2->where('email', $user->email);
                });
            });
        } elseif ($user->isClienteFuncionario()) {
            $query->whereHas('contact', function ($q) use ($user) {
                $q->where('email', $user->email);
            });
        } elseif ($user->isTecnico()) {
            $query->where('assigned_to', $user->id);
        }
        
        return [
            'baixa' => (clone $query)->byPriority('baixa')->count(),
            'média' => (clone $query)->byPriority('média')->count(),
            'alta' => (clone $query)->byPriority('alta')->count(),
        ];
    }

    /**
     * Obtém contagem de tickets por categoria
     */
    private function getTicketsByCategory(User $user): array
    {
        $query = Ticket::query()->active();
        
        if ($user->isClienteGestor()) {
            $query->whereHas('client', function ($q) use ($user) {
                $q->whereHas('contacts', function ($q2) use ($user) {
                    $q2->where('email', $user->email);
                });
            });
        } elseif ($user->isClienteFuncionario()) {
            $query->whereHas('contact', function ($q) use ($user) {
                $q->where('email', $user->email);
            });
        } elseif ($user->isTecnico()) {
            $query->where('assigned_to', $user->id);
        }
        
        return Category::active()
            ->withCount(['tickets' => function ($q) use ($query) {
                $q->whereIn('id', $query->pluck('id'));
            }])
            ->get()
            ->pluck('tickets_count', 'name')
            ->toArray();
    }

    /**
     * Obtém tickets recentes
     */
    private function getRecentTickets(User $user): \Illuminate\Database\Eloquent\Collection
    {
        return $this->getUserTicketQuery($user)
            ->select('id', 'ticket_number', 'title', 'status', 'priority', 'updated_at')
            ->latest('updated_at')
            ->limit(5)
            ->get()
            ->map(function($ticket) {
                $ticket->status_color = match($ticket->status) {
                    'aberto' => 'blue',
                    'em_andamento' => 'yellow',
                    'resolvido' => 'green',
                    'fechado' => 'gray',
                    default => 'gray'
                };
                $ticket->priority_color = match($ticket->priority) {
                    'alta' => 'red',
                    'média' => 'yellow',
                    'baixa' => 'green',
                    default => 'gray'
                };
                return $ticket;
            });
    }

    /**
     * Obtém tickets urgentes
     */
    private function getUrgentTickets(User $user): \Illuminate\Database\Eloquent\Collection
    {
        $query = Ticket::with(['client', 'category', 'contact'])
            ->urgent()
            ->active()
            ->latest('opened_at')
            ->limit(5);
        
        if ($user->isClienteGestor()) {
            $query->whereHas('client', function ($q) use ($user) {
                $q->whereHas('contacts', function ($q2) use ($user) {
                    $q2->where('email', $user->email);
                });
            });
        } elseif ($user->isClienteFuncionario()) {
            $query->whereHas('contact', function ($q) use ($user) {
                $q->where('email', $user->email);
            });
        } elseif ($user->isTecnico()) {
            $query->where('assigned_to', $user->id);
        }
        
        return $query->get();
    }

    /**
     * Obtém tickets não atribuídos (apenas para técnicos/admin)
     */
    private function getUnassignedTickets(): \Illuminate\Database\Eloquent\Collection
    {
        return Ticket::with(['client', 'category', 'contact'])
            ->unassigned()
            ->active()
            ->latest('opened_at')
            ->limit(10)
            ->get();
    }

    /**
     * Obtém últimos logins (apenas para admin)
     */
    private function getRecentLogins(): \Illuminate\Database\Eloquent\Collection
    {
        return AuditLog::with('user')
            ->whereIn('event_type', ['login_success', 'logout'])
            ->whereNotNull('user_id')
            ->latest()
            ->limit(5)
            ->get();
    }

    /**
     * Obtém estatísticas rápidas calculadas
     */
    private function getQuickStats(User $user): array
    {
        $query = $this->getUserTicketQuery($user);
        
        // Tempo médio de resposta (em horas) - com timeout
        try {
            $avgResponseTime = $this->calculateAverageResponseTime($query);
        } catch (\Exception $e) {
            $avgResponseTime = 'N/A';
        }
        
        // Taxa de resolução (tickets resolvidos vs total)
        try {
            $resolutionRate = $this->calculateResolutionRate($query);
        } catch (\Exception $e) {
            $resolutionRate = '0%';
        }
        
        // Tickets resolvidos hoje
        $resolvedToday = (clone $query)->resolved()
            ->whereDate('resolved_at', today())
            ->count();
        
        // Tickets criados esta semana
        try {
            $createdThisWeek = (clone $query)
                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->count();
        } catch (\Exception $e) {
            $createdThisWeek = 0;
        }
        
        return [
            'avg_response_time' => $avgResponseTime,
            'resolution_rate' => $resolutionRate,
            'resolved_today' => $resolvedToday,
            'created_this_week' => $createdThisWeek,
        ];
    }

    /**
     * Calcula tempo médio de primeira resposta em horas
     */
    private function calculateAverageResponseTime($query): string
    {
        // Query SQL direta - mais eficiente que Eloquent com relacionamentos
        $result = \DB::table('tickets')
            ->join('ticket_messages', 'tickets.id', '=', 'ticket_messages.ticket_id')
            ->whereNotNull('ticket_messages.user_id')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, tickets.created_at, ticket_messages.created_at)) as avg_hours')
            ->selectRaw('COUNT(*) as total_responses')
            ->first();

        if (!$result || $result->total_responses == 0) {
            return 'N/A';
        }

        $avgHours = $result->avg_hours;
        
        if ($avgHours < 1) {
            return round($avgHours * 60) . 'min';
        } elseif ($avgHours < 24) {
            return round($avgHours, 1) . 'h';
        } else {
            return round($avgHours / 24, 1) . 'd';
        }
    }

    /**
     * Calcula taxa de resolução em porcentagem
     */
    private function calculateResolutionRate($query): string
    {
        $total = (clone $query)->count();
        $resolved = (clone $query)->whereIn('status', ['resolvido', 'fechado'])->count();

        if ($total === 0) {
            return '0%';
        }

        $rate = ($resolved / $total) * 100;
        return round($rate) . '%';
    }

    /**
     * SLA targets em horas por prioridade
     */
    private const SLA_TARGETS = [
        'alta' => 4,
        'média' => 8,
        'baixa' => 24,
    ];

    /**
     * Calcula compliance de SLA por prioridade
     */
    private function getSlaData(User $user): array
    {
        $result = [];
        $totalAll = 0;
        $withinAll = 0;

        foreach (self::SLA_TARGETS as $priority => $targetHours) {
            $tickets = $this->getUserTicketQuery($user)
                ->where('priority', $priority)
                ->whereNotNull('resolved_at')
                ->selectRaw('COUNT(*) as total')
                ->selectRaw("SUM(CASE WHEN TIMESTAMPDIFF(HOUR, opened_at, resolved_at) <= {$targetHours} THEN 1 ELSE 0 END) as within_sla")
                ->first();

            $total = $tickets->total ?? 0;
            $within = $tickets->within_sla ?? 0;
            $pct = $total > 0 ? round(($within / $total) * 100) : 0;

            $result[$priority] = [
                'total' => $total,
                'within' => $within,
                'pct' => $pct,
                'target_hours' => $targetHours,
            ];

            $totalAll += $total;
            $withinAll += $within;
        }

        $result['overall'] = $totalAll > 0 ? round(($withinAll / $totalAll) * 100) : 0;

        return $result;
    }

    /**
     * Retorna contagem de tickets criados e resolvidos por dia nos ultimos N dias
     */
    private function getTicketsTimeline(User $user, int $days = 14): array
    {
        $timeline = [];
        $startDate = now()->subDays($days - 1)->startOfDay();

        $created = $this->getUserTicketQuery($user)
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupByRaw('DATE(created_at)')
            ->pluck('count', 'date')
            ->toArray();

        $resolved = $this->getUserTicketQuery($user)
            ->whereNotNull('resolved_at')
            ->where('resolved_at', '>=', $startDate)
            ->selectRaw('DATE(resolved_at) as date, COUNT(*) as count')
            ->groupByRaw('DATE(resolved_at)')
            ->pluck('count', 'date')
            ->toArray();

        for ($i = 0; $i < $days; $i++) {
            $date = now()->subDays($days - 1 - $i)->format('Y-m-d');
            $timeline[] = [
                'date' => $date,
                'label' => Carbon::parse($date)->format('d/m'),
                'created' => $created[$date] ?? 0,
                'resolved' => $resolved[$date] ?? 0,
            ];
        }

        return $timeline;
    }

    /**
     * Obtém alertas críticos que precisam de atenção imediata
     */
    private function getCriticalAlerts($user): array
    {
        $alerts = [];
        
        // Tickets vencidos (SLA baseado em prioridade)
        $overdue = $this->getUserTicketQuery($user)
            ->whereIn('status', ['aberto', 'em_andamento'])
            ->where(function($q) {
                $q->where(function($q1) {
                    $q1->where('priority', 'alta')
                       ->where('opened_at', '<', Carbon::now()->subHours(4));
                })->orWhere(function($q2) {
                    $q2->where('priority', 'média')
                       ->where('opened_at', '<', Carbon::now()->subHours(8));
                })->orWhere(function($q3) {
                    $q3->where('priority', 'baixa')
                       ->where('opened_at', '<', Carbon::now()->subHours(24));
                });
            })
            ->count();
            
        if ($overdue > 0) {
            $alerts[] = [
                'type' => 'overdue',
                'title' => 'Tickets Vencidos',
                'count' => $overdue,
                'color' => 'red',
                'icon' => '⚠️',
                'action' => '/tickets?filter=overdue'
            ];
        }
        
        // Tickets não atribuídos urgentes (apenas para técnicos/admin)
        if ($user->canManageTickets()) {
            $unassignedUrgent = Ticket::whereNull('assigned_to')
                ->where('is_urgent', true)
                ->whereIn('status', ['aberto', 'em_andamento'])
                ->count();
                
            if ($unassignedUrgent > 0) {
                $alerts[] = [
                    'type' => 'unassigned_urgent',
                    'title' => 'Urgentes Não Atribuídos',
                    'count' => $unassignedUrgent,
                    'color' => 'orange',
                    'icon' => '🚨',
                    'action' => '/tickets?filter=unassigned_urgent'
                ];
            }
        }
        
        return $alerts;
    }

    /**
     * Obtém os 5 tickets mais antigos em aberto
     */
    private function getOldestTickets($user)
    {
        return $this->getUserTicketQuery($user)
            ->select('id', 'ticket_number', 'title', 'priority', 'opened_at', 'client_id')
            ->whereIn('status', ['aberto', 'em_andamento'])
            ->orderBy('opened_at', 'asc')
            ->limit(5)
            ->get()
            ->map(function($ticket) {
                $ticket->days_open = $ticket->opened_at->diffInDays(now());
                $ticket->priority_color = match($ticket->priority) {
                    'alta' => 'red',
                    'média' => 'yellow', 
                    'baixa' => 'green',
                    default => 'gray'
                };
                return $ticket;
            });
    }

    /**
     * Obtém fila de trabalho personalizada do usuário
     */
    private function getMyWorkQueue($user)
    {
        if (!$user->canManageTickets()) {
            return collect();
        }
        
        return Ticket::select('id', 'ticket_number', 'title', 'priority', 'status', 'opened_at')
            ->where('assigned_to', $user->id)
            ->whereIn('status', ['aberto', 'em_andamento'])
            ->orderByRaw("FIELD(priority, 'alta', 'média', 'baixa')")
            ->orderBy('opened_at', 'asc')
            ->limit(5)
            ->get()
            ->map(function($ticket) {
                $ticket->priority_color = match($ticket->priority) {
                    'alta' => 'red',
                    'média' => 'yellow',
                    'baixa' => 'green',
                    default => 'gray'
                };
                return $ticket;
            });
    }

    /**
     * Obtém ações rápidas contextuais
     */
    private function getQuickActions($user): array
    {
        $actions = [];
        
        if ($user->canManageTickets()) {
            // Próximo ticket disponível
            $nextTicket = Ticket::whereNull('assigned_to')
                ->whereIn('status', ['aberto'])
                ->orderByRaw("FIELD(priority, 'alta', 'média', 'baixa')")
                ->orderBy('opened_at', 'asc')
                ->first();
                
            if ($nextTicket) {
                $actions[] = [
                    'title' => 'Pegar Próximo Ticket',
                    'subtitle' => "#{$nextTicket->ticket_number}",
                    'action' => "/tickets/{$nextTicket->ticket_number}",
                    'color' => 'blue',
                    'icon' => '🎯'
                ];
            }
        }
        
        $actions[] = [
            'title' => 'Criar Ticket',
            'subtitle' => 'Novo chamado',
            'action' => '/tickets/create',
            'color' => 'green',
            'icon' => '➕'
        ];
        
        return $actions;
    }
}

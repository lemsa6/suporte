<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Client;
use App\Models\Category;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    // Middleware aplicado nas rotas

    /**
     * Exibe o dashboard principal
     */
    public function index(Request $request): View
    {
        try {
            \Log::info('Dashboard: Iniciando carregamento do dashboard');
            $user = auth()->user();
            \Log::info('Dashboard: Usuário autenticado', ['user_id' => $user->id, 'role' => $user->role]);
            
            // Estatísticas gerais
            \Log::info('Dashboard: Carregando estatísticas gerais');
            $stats = $this->getGeneralStats($user);
            \Log::info('Dashboard: Estatísticas gerais carregadas', $stats);
            
            // Tickets por status
            \Log::info('Dashboard: Carregando tickets por status');
            $ticketsByStatus = $this->getTicketsByStatus($user);
            \Log::info('Dashboard: Tickets por status carregados');
            
            // Tickets por prioridade
            \Log::info('Dashboard: Carregando tickets por prioridade');
            $ticketsByPriority = $this->getTicketsByPriority($user);
            \Log::info('Dashboard: Tickets por prioridade carregados');
            
            // Tickets por categoria
            \Log::info('Dashboard: Carregando tickets por categoria');
            $ticketsByCategory = $this->getTicketsByCategory($user);
            \Log::info('Dashboard: Tickets por categoria carregados');
            
            // Últimos tickets
            \Log::info('Dashboard: Carregando últimos tickets');
            $recentTickets = $this->getRecentTickets($user);
            \Log::info('Dashboard: Últimos tickets carregados', ['count' => $recentTickets->count()]);
            
            // Tickets urgentes
            \Log::info('Dashboard: Carregando tickets urgentes');
            $urgentTickets = $this->getUrgentTickets($user);
            \Log::info('Dashboard: Tickets urgentes carregados', ['count' => $urgentTickets->count()]);
            
            // Tickets não atribuídos (apenas para técnicos/admin)
            \Log::info('Dashboard: Carregando tickets não atribuídos');
            $unassignedTickets = $user->canManageTickets() ? $this->getUnassignedTickets() : collect();
            \Log::info('Dashboard: Tickets não atribuídos carregados', ['count' => $unassignedTickets->count()]);
            
            // Últimos logins (apenas para admin) - com fallback
            $recentLogins = collect();
            if ($user->isAdmin()) {
                try {
                    $recentLogins = $this->getRecentLogins();
                } catch (\Exception $e) {
                    \Log::error('Erro ao buscar últimos logins: ' . $e->getMessage());
                    $recentLogins = collect();
                }
            }
            
            // Estatísticas rápidas - com fallback
            $quickStats = [];
            try {
                $quickStats = $this->getQuickStats($user);
            } catch (\Exception $e) {
                \Log::error('Erro ao calcular estatísticas rápidas: ' . $e->getMessage());
                $quickStats = [
                    'avg_response_time' => 'N/A',
                    'resolution_rate' => '0%',
                    'resolved_today' => 0,
                    'created_this_week' => 0,
                ];
            }
            
            \Log::info('Dashboard: Todos os dados carregados com sucesso, renderizando view');
            return view('dashboard.index', compact(
                'stats',
                'ticketsByStatus',
                'ticketsByPriority',
                'ticketsByCategory',
                'recentTickets',
                'urgentTickets',
                'unassignedTickets',
                'recentLogins',
                'quickStats'
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
        $query = Ticket::with(['client', 'category', 'contact'])
            ->latest('updated_at')
            ->limit(10);
        
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
            ->limit(8)
            ->get();
    }

    /**
     * Obtém estatísticas rápidas calculadas
     */
    private function getQuickStats(User $user): array
    {
        \Log::info('Dashboard: Iniciando getQuickStats');
        $query = $this->getUserTicketQuery($user);
        
        // Tempo médio de resposta (em horas) - com timeout
        \Log::info('Dashboard: Calculando tempo médio de resposta');
        try {
            $avgResponseTime = $this->calculateAverageResponseTime($query);
            \Log::info('Dashboard: Tempo médio calculado', ['avg_time' => $avgResponseTime]);
        } catch (\Exception $e) {
            \Log::error('Dashboard: Erro no cálculo de tempo médio: ' . $e->getMessage());
            $avgResponseTime = 'N/A';
        }
        
        // Taxa de resolução (tickets resolvidos vs total)
        \Log::info('Dashboard: Calculando taxa de resolução');
        try {
            $resolutionRate = $this->calculateResolutionRate($query);
            \Log::info('Dashboard: Taxa de resolução calculada', ['rate' => $resolutionRate]);
        } catch (\Exception $e) {
            \Log::error('Dashboard: Erro no cálculo de taxa de resolução: ' . $e->getMessage());
            $resolutionRate = '0%';
        }
        
        // Tickets resolvidos hoje
        \Log::info('Dashboard: Contando tickets resolvidos hoje');
        $resolvedToday = (clone $query)->resolved()
            ->whereDate('resolved_at', today())
            ->count();
        \Log::info('Dashboard: Tickets resolvidos hoje', ['count' => $resolvedToday]);
        
        // Tickets criados esta semana
        \Log::info('Dashboard: Contando tickets criados esta semana');
        $createdThisWeek = (clone $query)
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();
        \Log::info('Dashboard: Tickets criados esta semana', ['count' => $createdThisWeek]);
        
        \Log::info('Dashboard: getQuickStats finalizado com sucesso');
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
        \Log::info('Dashboard: Iniciando cálculo de tempo médio de resposta');
        $tickets = (clone $query)->with(['messages' => function($q) {
            $q->where('user_id', '!=', null)->oldest();
        }])->get();
        \Log::info('Dashboard: Tickets carregados para cálculo', ['count' => $tickets->count()]);

        $totalHours = 0;
        $count = 0;

        foreach ($tickets as $ticket) {
            $firstResponse = $ticket->messages->first();
            if ($firstResponse) {
                $hours = $ticket->created_at->diffInHours($firstResponse->created_at);
                $totalHours += $hours;
                $count++;
            }
        }

        if ($count === 0) {
            return 'N/A';
        }

        $avgHours = $totalHours / $count;
        
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
}

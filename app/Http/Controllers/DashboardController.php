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
        $user = auth()->user();
        
        // Estatísticas gerais
        $stats = $this->getGeneralStats($user);
        
        // Tickets por status
        $ticketsByStatus = $this->getTicketsByStatus($user);
        
        // Tickets por prioridade
        $ticketsByPriority = $this->getTicketsByPriority($user);
        
        // Tickets por categoria
        $ticketsByCategory = $this->getTicketsByCategory($user);
        
        // Últimos tickets
        $recentTickets = $this->getRecentTickets($user);
        
        // Tickets urgentes
        $urgentTickets = $this->getUrgentTickets($user);
        
        // Tickets não atribuídos (apenas para técnicos/admin)
        $unassignedTickets = $user->canManageTickets() ? $this->getUnassignedTickets() : collect();
        
        // Últimos logins (apenas para admin)
        $recentLogins = $user->isAdmin() ? $this->getRecentLogins() : collect();
        
        return view('dashboard.index', compact(
            'stats',
            'ticketsByStatus',
            'ticketsByPriority',
            'ticketsByCategory',
            'recentTickets',
            'urgentTickets',
            'unassignedTickets',
            'recentLogins'
        ));
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
            ->where('event_type', 'login')
            ->orWhere('event_type', 'logout')
            ->latest()
            ->limit(8)
            ->get();
    }
}

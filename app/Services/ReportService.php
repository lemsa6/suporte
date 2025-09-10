<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\Client;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ReportService
{
    /**
     * Get general statistics for the dashboard
     */
    public function getGeneralStats(): array
    {
        return [
            'total_tickets' => Ticket::count(),
            'open_tickets' => Ticket::where('status', 'aberto')->count(),
            'in_progress_tickets' => Ticket::where('status', 'em_andamento')->count(),
            'resolved_tickets' => Ticket::where('status', 'resolvido')->count(),
            'closed_tickets' => Ticket::where('status', 'fechado')->count(),
            'urgent_tickets' => Ticket::where('is_urgent', true)->count(),
            'total_clients' => Client::count(),
            'active_clients' => Client::where('is_active', true)->count(),
            'total_categories' => Category::count(),
            'total_technicians' => User::tecnicos()->count(),
        ];
    }

    /**
     * Get tickets grouped by status
     */
    public function getTicketsByStatus(): array
    {
        return [
            'Aberto' => Ticket::where('status', 'aberto')->count(),
            'Em Andamento' => Ticket::where('status', 'em_andamento')->count(),
            'Resolvido' => Ticket::where('status', 'resolvido')->count(),
            'Fechado' => Ticket::where('status', 'fechado')->count(),
        ];
    }

    /**
     * Get tickets grouped by priority
     */
    public function getTicketsByPriority(): array
    {
        return [
            'baixa' => Ticket::where('priority', 'baixa')->count(),
            'média' => Ticket::where('priority', 'média')->count(),
            'alta' => Ticket::where('priority', 'alta')->count(),
            'urgente' => Ticket::where('priority', 'urgente')->count(),
        ];
    }

    /**
     * Get tickets grouped by month for current year
     */
    public function getTicketsByMonth(): array
    {
        return Ticket::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();
    }

    /**
     * Get tickets report with filters
     */
    public function getTicketsReport(array $filters): LengthAwarePaginator
    {
        $query = Ticket::with(['client', 'category', 'assignedTo']);

        $this->applyTicketFilters($query, $filters);

        return $query->latest()->paginate(20);
    }

    /**
     * Get clients report with filters
     */
    public function getClientsReport(array $filters): LengthAwarePaginator
    {
        $query = Client::withCount('tickets');

        $this->applyClientFilters($query, $filters);

        return $query->latest()->paginate(20);
    }

    /**
     * Get available ticket statuses
     */
    public function getAvailableStatuses(): array
    {
        return ['aberto', 'em_andamento', 'resolvido', 'fechado'];
    }

    /**
     * Get available ticket priorities
     */
    public function getAvailablePriorities(): array
    {
        return ['baixa', 'média', 'alta', 'urgente'];
    }

    /**
     * Apply ticket filters to query
     */
    protected function applyTicketFilters($query, array $filters): void
    {
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }
    }

    /**
     * Apply client filters to query
     */
    protected function applyClientFilters($query, array $filters): void
    {
        if (!empty($filters['status'])) {
            $query->where('is_active', $filters['status'] === 'active');
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }
    }
}

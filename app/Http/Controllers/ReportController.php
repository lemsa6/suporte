<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Client;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    /**
     * Dashboard de relatórios
     */
    public function index(): View
    {
        // Estatísticas gerais
        $stats = [
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

        // Dados para gráficos
        $ticketsByStatus = [
            'Aberto' => Ticket::where('status', 'aberto')->count(),
            'Em Andamento' => Ticket::where('status', 'em_andamento')->count(),
            'Resolvido' => Ticket::where('status', 'resolvido')->count(),
            'Fechado' => Ticket::where('status', 'fechado')->count(),
        ];

        $ticketsByMonth = Ticket::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        return view('reports.index', compact('stats', 'ticketsByStatus', 'ticketsByMonth'));
    }

    /**
     * Relatório de tickets
     */
    public function tickets(Request $request): View
    {
        $query = Ticket::with(['client', 'category', 'assignedTo']);

        // Filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $tickets = $query->latest()->paginate(20);
        $categories = Category::active()->orderBy('name')->get();
        $statuses = ['aberto', 'em_andamento', 'resolvido', 'fechado'];
        $priorities = ['baixa', 'média', 'alta', 'urgente'];

        return view('reports.tickets', compact('tickets', 'categories', 'statuses', 'priorities'));
    }

    /**
     * Relatório de clientes
     */
    public function clients(Request $request): View
    {
        $query = Client::withCount('tickets');

        // Filtros
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $clients = $query->latest()->paginate(20);

        return view('reports.clients', compact('clients'));
    }

    /**
     * API: Dados para gráficos
     */
    public function chartData(): JsonResponse
    {
        $data = [
            'tickets_by_status' => [
                'aberto' => Ticket::where('status', 'aberto')->count(),
                'em_andamento' => Ticket::where('status', 'em_andamento')->count(),
                'resolvido' => Ticket::where('status', 'resolvido')->count(),
                'fechado' => Ticket::where('status', 'fechado')->count(),
            ],
            'tickets_by_priority' => [
                'baixa' => Ticket::where('priority', 'baixa')->count(),
                'média' => Ticket::where('priority', 'média')->count(),
                'alta' => Ticket::where('priority', 'alta')->count(),
                'urgente' => Ticket::where('priority', 'urgente')->count(),
            ],
            'tickets_by_month' => Ticket::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->whereYear('created_at', date('Y'))
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->pluck('count', 'month')
                ->toArray(),
        ];

        return response()->json($data);
    }
}

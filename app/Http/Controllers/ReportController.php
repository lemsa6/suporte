<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Client;
use App\Models\Category;
use App\Models\User;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Dashboard de relatórios
     */
    public function index(): View
    {
        $stats = $this->reportService->getGeneralStats();
        $ticketsByStatus = $this->reportService->getTicketsByStatus();
        $ticketsByMonth = $this->reportService->getTicketsByMonth();

        return view('reports.index', compact('stats', 'ticketsByStatus', 'ticketsByMonth'));
    }

    /**
     * Relatório de tickets
     */
    public function tickets(Request $request): View
    {
        $filters = $this->extractTicketFilters($request);
        $tickets = $this->reportService->getTicketsReport($filters);
        
        $categories = Category::active()->orderBy('name')->get();
        $statuses = $this->reportService->getAvailableStatuses();
        $priorities = $this->reportService->getAvailablePriorities();

        return view('reports.tickets', compact('tickets', 'categories', 'statuses', 'priorities'));
    }

    /**
     * Relatório de clientes
     */
    public function clients(Request $request): View
    {
        $filters = $this->extractClientFilters($request);
        $clients = $this->reportService->getClientsReport($filters);

        return view('reports.clients', compact('clients'));
    }

    /**
     * API: Dados para gráficos
     */
    public function chartData(): JsonResponse
    {
        $data = [
            'tickets_by_status' => $this->reportService->getTicketsByStatus(),
            'tickets_by_priority' => $this->reportService->getTicketsByPriority(),
            'tickets_by_month' => $this->reportService->getTicketsByMonth(),
        ];

        return response()->json($data);
    }

    /**
     * Extract ticket filters from request
     */
    protected function extractTicketFilters(Request $request): array
    {
        return [
            'status' => $request->get('status'),
            'priority' => $request->get('priority'),
            'category_id' => $request->get('category_id'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
        ];
    }

    /**
     * Extract client filters from request
     */
    protected function extractClientFilters(Request $request): array
    {
        return [
            'status' => $request->get('status'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
        ];
    }
}
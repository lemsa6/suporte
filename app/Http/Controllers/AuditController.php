<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Ticket;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class AuditController extends Controller
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    /**
     * Exibe a lista de logs de auditoria
     */
    public function index(Request $request): View
    {
        $filters = $request->only([
            'event_type',
            'user_id',
            'auditable_type',
            'auditable_id',
            'ip_address',
            'date_from',
            'date_to'
        ]);

        $query = $this->auditService->getLogs($filters);
        $logs = $query->paginate(20);

        // Dados para filtros
        $eventTypes = [
            'created' => 'Criado',
            'updated' => 'Atualizado',
            'deleted' => 'Excluído',
            'replied' => 'Respondido',
            'closed' => 'Fechado',
            'reopened' => 'Reaberto',
            'assigned' => 'Atribuído',
            'status_changed' => 'Status alterado',
            'priority_changed' => 'Prioridade alterada',
            'viewed' => 'Visualizado',
        ];

        $auditableTypes = [
            'App\Models\Ticket' => 'Ticket',
            'App\Models\TicketMessage' => 'Mensagem',
            'App\Models\User' => 'Usuário',
            'App\Models\Client' => 'Cliente',
        ];

        return view('admin.audit.index', compact('logs', 'eventTypes', 'auditableTypes', 'filters'));
    }

    /**
     * Exibe detalhes de um log específico
     */
    public function show(AuditLog $auditLog): View
    {
        $auditLog->load(['user', 'auditable']);
        
        return view('admin.audit.show', compact('auditLog'));
    }

    /**
     * Exibe logs de auditoria de um ticket específico
     */
    public function ticketLogs(string $ticketNumber): View
    {
        $ticket = Ticket::findByNumber($ticketNumber);
        
        if (!$ticket) {
            abort(404, 'Ticket não encontrado.');
        }

        $logs = $this->auditService->getLogs([
            'auditable_type' => 'App\Models\Ticket',
            'auditable_id' => $ticket->id
        ])->paginate(20);

        return view('admin.audit.ticket', compact('ticket', 'logs'));
    }

    /**
     * Exibe estatísticas de auditoria
     */
    public function statistics(Request $request): View
    {
        $filters = $request->only([
            'date_from',
            'date_to',
            'user_id',
            'event_type'
        ]);

        $statistics = $this->auditService->getStatistics($filters);

        return view('admin.audit.statistics', compact('statistics', 'filters'));
    }

    /**
     * API para obter logs de auditoria (AJAX)
     */
    public function apiLogs(Request $request): JsonResponse
    {
        $filters = $request->only([
            'event_type',
            'user_id',
            'auditable_type',
            'auditable_id',
            'ip_address',
            'date_from',
            'date_to'
        ]);

        $query = $this->auditService->getLogs($filters);
        $logs = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $logs->items(),
            'pagination' => [
                'current_page' => $logs->currentPage(),
                'last_page' => $logs->lastPage(),
                'per_page' => $logs->perPage(),
                'total' => $logs->total(),
            ]
        ]);
    }

    /**
     * API para obter estatísticas (AJAX)
     */
    public function apiStatistics(Request $request): JsonResponse
    {
        $filters = $request->only([
            'date_from',
            'date_to',
            'user_id',
            'event_type'
        ]);

        $statistics = $this->auditService->getStatistics($filters);

        return response()->json([
            'success' => true,
            'data' => $statistics
        ]);
    }

    /**
     * Exporta logs de auditoria para CSV
     */
    public function export(Request $request)
    {
        $filters = $request->only([
            'event_type',
            'user_id',
            'auditable_type',
            'auditable_id',
            'ip_address',
            'date_from',
            'date_to'
        ]);

        $logs = $this->auditService->getLogs($filters)->get();

        $filename = 'audit_logs_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // Cabeçalho do CSV
            fputcsv($file, [
                'ID',
                'Tipo de Evento',
                'Modelo',
                'ID do Modelo',
                'Usuário',
                'Tipo de Usuário',
                'IP',
                'User Agent',
                'Valores Antigos',
                'Valores Novos',
                'Descrição',
                'URL',
                'Método',
                'Data/Hora'
            ]);

            // Dados
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->event_type,
                    $log->auditable_type,
                    $log->auditable_id,
                    $log->user_name,
                    $log->user_type,
                    $log->ip_address,
                    $log->user_agent,
                    json_encode($log->old_values),
                    json_encode($log->new_values),
                    $log->description,
                    $log->url,
                    $log->method,
                    $log->created_at->format('d/m/Y H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
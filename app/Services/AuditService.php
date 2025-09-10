<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditService
{
    /**
     * Registra uma ação de auditoria
     */
    public function log(
        string $eventType,
        Model $auditable,
        ?User $user = null,
        ?Request $request = null,
        array $oldValues = [],
        array $newValues = [],
        ?string $description = null
    ): AuditLog {
        // Obter usuário atual se não fornecido
        if (!$user && Auth::check()) {
            $user = Auth::user();
        }

        // Obter informações da requisição
        $ipAddress = null;
        $userAgent = null;
        $url = null;
        $method = null;

        if ($request) {
            $ipAddress = $request->get('audit_ip') ?? $request->ip();
            $userAgent = $request->get('audit_user_agent') ?? $request->userAgent();
            $url = $request->fullUrl();
            $method = $request->method();
        }

        // Criar log de auditoria
        return AuditLog::create([
            'event_type' => $eventType,
            'auditable_type' => get_class($auditable),
            'auditable_id' => $auditable->id ?? null,
            'user_id' => $user?->id,
            'user_type' => $user?->role,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'description' => $description ?? $this->generateDescription($eventType, $auditable, $user),
            'url' => $url,
            'method' => $method,
        ]);
    }

    /**
     * Registra criação de um modelo
     */
    public function logCreated(Model $auditable, ?User $user = null, ?Request $request = null): AuditLog
    {
        return $this->log('created', $auditable, $user, $request, [], $auditable->toArray());
    }

    /**
     * Registra atualização de um modelo
     */
    public function logUpdated(
        Model $auditable,
        array $oldValues,
        array $newValues,
        ?User $user = null,
        ?Request $request = null
    ): AuditLog {
        return $this->log('updated', $auditable, $user, $request, $oldValues, $newValues);
    }

    /**
     * Registra exclusão de um modelo
     */
    public function logDeleted(Model $auditable, ?User $user = null, ?Request $request = null): AuditLog
    {
        return $this->log('deleted', $auditable, $user, $request, $auditable->toArray(), []);
    }

    /**
     * Registra resposta em ticket
     */
    public function logTicketReply(
        Model $ticket,
        Model $message,
        ?User $user = null,
        ?Request $request = null
    ): AuditLog {
        return $this->log(
            'replied',
            $ticket,
            $user,
            $request,
            [],
            ['message_id' => $message->id, 'message' => substr($message->message, 0, 100) . '...']
        );
    }

    /**
     * Registra fechamento de ticket
     */
    public function logTicketClosed(
        Model $ticket,
        ?User $user = null,
        ?Request $request = null,
        ?string $reason = null
    ): AuditLog {
        return $this->log(
            'closed',
            $ticket,
            $user,
            $request,
            ['status' => 'open'],
            ['status' => 'closed', 'reason' => $reason]
        );
    }

    /**
     * Registra reabertura de ticket
     */
    public function logTicketReopened(
        Model $ticket,
        ?User $user = null,
        ?Request $request = null
    ): AuditLog {
        return $this->log(
            'reopened',
            $ticket,
            $user,
            $request,
            ['status' => 'closed'],
            ['status' => 'open']
        );
    }

    /**
     * Registra atribuição de ticket
     */
    public function logTicketAssigned(
        Model $ticket,
        ?User $assignedTo,
        ?User $assignedBy = null,
        ?Request $request = null
    ): AuditLog {
        return $this->log(
            'assigned',
            $ticket,
            $assignedBy,
            $request,
            ['assigned_to' => $ticket->assigned_to],
            ['assigned_to' => $assignedTo?->id, 'assigned_to_name' => $assignedTo?->name]
        );
    }

    /**
     * Registra mudança de status
     */
    public function logStatusChange(
        Model $auditable,
        string $oldStatus,
        string $newStatus,
        ?User $user = null,
        ?Request $request = null
    ): AuditLog {
        return $this->log(
            'status_changed',
            $auditable,
            $user,
            $request,
            ['status' => $oldStatus],
            ['status' => $newStatus]
        );
    }

    /**
     * Registra mudança de prioridade
     */
    public function logPriorityChange(
        Model $auditable,
        string $oldPriority,
        string $newPriority,
        ?User $user = null,
        ?Request $request = null
    ): AuditLog {
        return $this->log(
            'priority_changed',
            $auditable,
            $user,
            $request,
            ['priority' => $oldPriority],
            ['priority' => $newPriority]
        );
    }

    /**
     * Registra visualização de ticket
     */
    public function logTicketViewed(
        Model $ticket,
        ?User $user = null,
        ?Request $request = null
    ): AuditLog {
        // Verificar se auditoria de visualização está habilitada
        try {
            $auditViews = \App\Models\Setting::where('key', 'audit_views')->first();
            if ($auditViews && $auditViews->value === 'false') {
                return null; // Não registrar visualizações se desabilitado
            }
        } catch (\Exception $e) {
            // Se não conseguir acessar configurações, continua registrando
        }
        
        return $this->log('viewed', $ticket, $user, $request);
    }

    /**
     * Gera descrição automática para a ação
     */
    private function generateDescription(string $eventType, Model $auditable, ?User $user = null): string
    {
        $userName = $user ? $user->name : 'Sistema';
        $modelName = class_basename($auditable);
        
        $descriptions = [
            'created' => "{$modelName} criado por {$userName}",
            'updated' => "{$modelName} atualizado por {$userName}",
            'deleted' => "{$modelName} excluído por {$userName}",
            'replied' => "Resposta adicionada ao {$modelName} por {$userName}",
            'closed' => "{$modelName} fechado por {$userName}",
            'reopened' => "{$modelName} reaberto por {$userName}",
            'assigned' => "{$modelName} atribuído por {$userName}",
            'status_changed' => "Status do {$modelName} alterado por {$userName}",
            'priority_changed' => "Prioridade do {$modelName} alterada por {$userName}",
            'viewed' => "{$modelName} visualizado por {$userName}",
        ];

        return $descriptions[$eventType] ?? "Ação '{$eventType}' executada em {$modelName} por {$userName}";
    }

    /**
     * Obtém logs de auditoria com filtros
     */
    public function getLogs(array $filters = []): \Illuminate\Database\Eloquent\Builder
    {
        $query = AuditLog::with(['user', 'auditable']);

        // Filtros
        if (isset($filters['event_type'])) {
            $query->where('event_type', $filters['event_type']);
        }

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['auditable_type'])) {
            $query->where('auditable_type', $filters['auditable_type']);
        }

        if (isset($filters['auditable_id'])) {
            $query->where('auditable_id', $filters['auditable_id']);
        }

        if (isset($filters['ip_address'])) {
            $query->where('ip_address', $filters['ip_address']);
        }

        if (isset($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        // Ordenação
        $query->orderBy('created_at', 'desc');

        return $query;
    }

    /**
     * Obtém estatísticas de auditoria
     */
    public function getStatistics(array $filters = []): array
    {
        $query = $this->getLogs($filters);

        return [
            'total_logs' => $query->count(),
            'by_event_type' => $query->selectRaw('event_type, COUNT(*) as count')
                ->groupBy('event_type')
                ->pluck('count', 'event_type'),
            'by_user' => $query->selectRaw('user_id, COUNT(*) as count')
                ->whereNotNull('user_id')
                ->groupBy('user_id')
                ->with('user')
                ->get()
                ->pluck('count', 'user.name'),
            'by_ip' => $query->selectRaw('ip_address, COUNT(*) as count')
                ->whereNotNull('ip_address')
                ->groupBy('ip_address')
                ->pluck('count', 'ip_address'),
            'recent_activity' => $query->limit(10)->get(),
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_type',
        'auditable_type',
        'auditable_id',
        'user_id',
        'user_type',
        'ip_address',
        'user_agent',
        'old_values',
        'new_values',
        'description',
        'url',
        'method',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relacionamento com o usuário que executou a ação
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento polimórfico com o modelo auditado
     */
    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope para filtrar por tipo de evento
     */
    public function scopeEventType($query, $eventType)
    {
        return $query->where('event_type', $eventType);
    }

    /**
     * Scope para filtrar por usuário
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope para filtrar por modelo auditado
     */
    public function scopeAuditable($query, $type, $id = null)
    {
        $query = $query->where('auditable_type', $type);
        
        if ($id) {
            $query->where('auditable_id', $id);
        }
        
        return $query;
    }

    /**
     * Scope para filtrar por período
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope para filtrar por IP
     */
    public function scopeByIp($query, $ipAddress)
    {
        return $query->where('ip_address', $ipAddress);
    }

    /**
     * Obtém o nome do usuário de forma segura
     */
    public function getUserNameAttribute(): string
    {
        if ($this->user) {
            return $this->user->name;
        }
        
        return $this->user_type ? ucfirst($this->user_type) : 'Sistema';
    }

    /**
     * Obtém o email do usuário de forma segura
     */
    public function getUserEmailAttribute(): ?string
    {
        return $this->user ? $this->user->email : null;
    }

    /**
     * Obtém informações resumidas do User Agent
     */
    public function getBrowserInfoAttribute(): string
    {
        if (!$this->user_agent) {
            return 'N/A';
        }

        // Extrair informações básicas do User Agent
        $userAgent = $this->user_agent;
        
        // Detectar navegador
        if (strpos($userAgent, 'Chrome') !== false) {
            $browser = 'Chrome';
        } elseif (strpos($userAgent, 'Firefox') !== false) {
            $browser = 'Firefox';
        } elseif (strpos($userAgent, 'Safari') !== false) {
            $browser = 'Safari';
        } elseif (strpos($userAgent, 'Edge') !== false) {
            $browser = 'Edge';
        } else {
            $browser = 'Outro';
        }

        // Detectar sistema operacional
        if (strpos($userAgent, 'Windows') !== false) {
            $os = 'Windows';
        } elseif (strpos($userAgent, 'Mac') !== false) {
            $os = 'macOS';
        } elseif (strpos($userAgent, 'Linux') !== false) {
            $os = 'Linux';
        } elseif (strpos($userAgent, 'Android') !== false) {
            $os = 'Android';
        } elseif (strpos($userAgent, 'iOS') !== false) {
            $os = 'iOS';
        } else {
            $os = 'Outro';
        }

        return "{$browser} - {$os}";
    }

    /**
     * Obtém a descrição formatada da ação
     */
    public function getFormattedDescriptionAttribute(): string
    {
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

        $eventName = $eventTypes[$this->event_type] ?? ucfirst($this->event_type);
        
        return "{$eventName} por {$this->user_name} em " . $this->created_at->format('d/m/Y H:i:s');
    }
}
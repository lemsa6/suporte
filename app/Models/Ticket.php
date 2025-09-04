<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'title',
        'description',
        'status',
        'priority',
        'category_id',
        'client_id',
        'contact_id',
        'assigned_to',
        'opened_at',
        'resolved_at',
        'closed_at',
        'resolution_notes',
        'is_urgent',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
        'is_urgent' => 'boolean'
    ];

    // Relacionamentos
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(ClientContact::class, 'contact_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(TicketMessage::class);
    }

    public function attachments(): HasManyThrough
    {
        return $this->hasManyThrough(Attachment::class, TicketMessage::class);
    }

    public function latestMessage(): HasOne
    {
        return $this->hasOne(TicketMessage::class)->latestOfMany();
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->where('status', 'aberto');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'em_andamento');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolvido');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'fechado');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['aberto', 'em_andamento']);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    public function scopeByContact($query, $contactId)
    {
        return $query->where('contact_id', $contactId);
    }

    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeUnassigned($query)
    {
        return $query->whereNull('assigned_to');
    }

    public function scopeUrgent($query)
    {
        return $query->where('is_urgent', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('ticket_number', 'like', "%{$search}%");
        });
    }

    // Métodos úteis
    public function isOpen(): bool
    {
        return $this->status === 'aberto';
    }

    public function isInProgress(): bool
    {
        return $this->status === 'em_andamento';
    }

    public function isResolved(): bool
    {
        return $this->status === 'resolvido';
    }

    public function isClosed(): bool
    {
        return $this->status === 'fechado';
    }

    public function isAssigned(): bool
    {
        return !is_null($this->assigned_to);
    }

    public function canBeReopened(): bool
    {
        return $this->status === 'fechado';
    }

    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'baixa' => 'bg-green-100 text-green-800',
            'média' => 'bg-yellow-100 text-yellow-800',
            'alta' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'aberto' => 'bg-blue-100 text-blue-800',
            'em_andamento' => 'bg-yellow-100 text-yellow-800',
            'resolvido' => 'bg-green-100 text-green-800',
            'fechado' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getDaysOpenAttribute(): int
    {
        return $this->opened_at->diffInDays(now());
    }

    // Boot method para gerar número do ticket
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            // Gerar número de ticket se não existir
            if (empty($ticket->ticket_number)) {
                $ticket->ticket_number = self::generateTicketNumber();
            }
        });
    }

    /**
     * Gera um número de ticket único no formato TKT-ANO-SEQUENCIAL
     */
    public static function generateTicketNumber(string $prefix = 'TKT'): string
    {
        $year = date('Y');
        $lastTicket = self::where('ticket_number', 'like', "{$prefix}-{$year}-%")
            ->orderByRaw('CAST(SUBSTRING_INDEX(ticket_number, "-", -1) AS UNSIGNED) DESC')
            ->first();

        $sequence = 1;
        if ($lastTicket) {
            $parts = explode('-', $lastTicket->ticket_number);
            $sequence = (int)end($parts) + 1;
        }

        return sprintf('%s-%s-%04d', $prefix, $year, $sequence);
    }

    /**
     * Busca ticket por número
     */
    public static function findByNumber(string $ticketNumber): ?self
    {
        return static::where('ticket_number', $ticketNumber)->first();
    }
}

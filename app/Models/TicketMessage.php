<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'contact_id',
        'type',
        'message',
        'metadata',
        'is_internal'
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_internal' => 'boolean'
    ];

    // Relacionamentos
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(ClientContact::class, 'contact_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopePublic($query)
    {
        return $query->where('is_internal', false);
    }

    public function scopeInternal($query)
    {
        return $query->where('is_internal', true);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByContact($query, $contactId)
    {
        return $query->where('contact_id', $contactId);
    }

    // Boot method para atualizar updated_at do ticket pai
    protected static function boot()
    {
        parent::boot();

        // Atualizar updated_at do ticket quando uma mensagem é criada
        static::created(function ($ticketMessage) {
            $ticketMessage->ticket->touch();
        });

        // Atualizar updated_at do ticket quando uma mensagem é atualizada
        static::updated(function ($ticketMessage) {
            $ticketMessage->ticket->touch();
        });
    }

    // Métodos úteis
    public function isFromUser(): bool
    {
        return !is_null($this->user_id);
    }

    public function isFromContact(): bool
    {
        return !is_null($this->contact_id);
    }

    public function isInternal(): bool
    {
        return $this->is_internal;
    }

    public function getAuthorNameAttribute(): string
    {
        if ($this->isFromUser()) {
            return $this->user->name ?? 'Usuário';
        }
        
        if ($this->isFromContact()) {
            return $this->contact->name ?? 'Contato';
        }
        
        return 'Sistema';
    }

    public function getAuthorRoleAttribute(): string
    {
        if ($this->isFromUser()) {
            return $this->user->role ?? 'Usuário';
        }
        
        if ($this->isFromContact()) {
            return 'Cliente';
        }
        
        return 'Sistema';
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'note' => 'Nota Interna',
            'reply' => 'Resposta',
            'status_change' => 'Mudança de Status',
            'assignment' => 'Atribuição',
            default => 'Mensagem'
        };
    }

    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'note' => 'bg-purple-100 text-purple-800',
            'reply' => 'bg-blue-100 text-blue-800',
            'status_change' => 'bg-yellow-100 text-yellow-800',
            'assignment' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function hasAttachments(): bool
    {
        return $this->attachments()->exists();
    }

    public function getAttachmentCountAttribute(): int
    {
        return $this->attachments()->count();
    }
}

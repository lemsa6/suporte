<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClientContact extends Model
{
    use HasFactory;

    /**
     * Boot do modelo para eventos automáticos
     */
    protected static function boot()
    {
        parent::boot();

        // Sincronizar dados com User ao atualizar
        static::updating(function ($contact) {
            if ($contact->user && $contact->isDirty(['name', 'email', 'user_type', 'is_active'])) {
                $contact->user->update([
                    'name' => $contact->name,
                    'email' => $contact->email,
                    'role' => $contact->user_type,
                    'is_active' => $contact->is_active,
                ]);
            }
        });
    }

    protected $fillable = [
        'client_id',
        'user_id',
        'name',
        'email',
        'phone',
        'position',
        'department',
        'is_primary',
        'is_active',
        'user_type',
        'receive_notifications'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_active' => 'boolean',
        'receive_notifications' => 'boolean'
    ];

    // Relacionamentos
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'contact_id');
    }

    public function ticketMessages(): HasMany
    {
        return $this->hasMany(TicketMessage::class, 'contact_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    public function scopeWithUser($query)
    {
        return $query->with('user');
    }

    public function scopeManagers($query)
    {
        return $query->where('user_type', 'cliente_gestor');
    }

    public function scopeEmployees($query)
    {
        return $query->where('user_type', 'cliente_funcionario');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('position', 'like', "%{$search}%");
        });
    }

    // Métodos úteis
    public function getActiveTicketCountAttribute(): int
    {
        return $this->tickets()->whereIn('status', ['aberto', 'em_andamento'])->count();
    }

    public function getTotalTicketCountAttribute(): int
    {
        return $this->tickets()->count();
    }

    // Formatação
    public function getFullNameAttribute(): string
    {
        $parts = [];
        if ($this->position) $parts[] = $this->position;
        if ($this->department) $parts[] = $this->department;
        
        return $this->name . ($parts ? ' (' . implode(' - ', $parts) . ')' : '');
    }
}

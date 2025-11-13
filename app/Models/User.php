<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'notify_ticket_created',
        'notify_ticket_replied',
        'notify_ticket_status_changed',
        'notify_ticket_closed',
        'notify_ticket_priority_changed',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'is_active' => true,
        'notify_ticket_created' => true,
        'notify_ticket_replied' => true,
        'notify_ticket_status_changed' => true,
        'notify_ticket_closed' => true,
        'notify_ticket_priority_changed' => false,
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'notify_ticket_created' => 'boolean',
            'notify_ticket_replied' => 'boolean',
            'notify_ticket_status_changed' => 'boolean',
            'notify_ticket_closed' => 'boolean',
            'notify_ticket_priority_changed' => 'boolean',
        ];
    }

    // Relacionamentos
    public function assignedTickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }

    public function ticketMessages(): HasMany
    {
        return $this->hasMany(TicketMessage::class);
    }

    public function clientContacts(): HasMany
    {
        return $this->hasMany(ClientContact::class, 'user_id');
    }

    // Scopes
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeTecnicos($query)
    {
        return $query->where('role', 'tecnico');
    }

    public function scopeClientes($query)
    {
        return $query->whereIn('role', ['cliente_gestor', 'cliente_funcionario']);
    }

    public function scopeClientesGestores($query)
    {
        return $query->where('role', 'cliente_gestor');
    }

    public function scopeClientesFuncionarios($query)
    {
        return $query->where('role', 'cliente_funcionario');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Métodos úteis
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTecnico(): bool
    {
        return $this->role === 'tecnico';
    }

    public function isCliente(): bool
    {
        return $this->isClienteGestor() || $this->isClienteFuncionario();
    }

    public function isClienteGestor(): bool
    {
        return $this->role === 'cliente_gestor';
    }

    public function isClienteFuncionario(): bool
    {
        return $this->role === 'cliente_funcionario';
    }

    public function canManageTickets(): bool
    {
        return in_array($this->role, ['admin', 'tecnico']);
    }

    public function canManageClients(): bool
    {
        return in_array($this->role, ['admin', 'tecnico']);
    }

    public function canViewCompanyTickets(): bool
    {
        return $this->isClienteGestor() || $this->isAdmin() || $this->isTecnico();
    }

    public function canCreateTicketsForOthers(): bool
    {
        return $this->isClienteGestor() || $this->isAdmin() || $this->isTecnico();
    }

    public function canManageUsers(): bool
    {
        return $this->role === 'admin';
    }

    public function getActiveTicketCountAttribute(): int
    {
        return $this->assignedTickets()->whereIn('status', ['aberto', 'em_andamento'])->count();
    }

    public function getTotalTicketCountAttribute(): int
    {
        return $this->assignedTickets()->count();
    }

    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'admin' => 'Administrador',
            'tecnico' => 'Técnico',
            'cliente_gestor' => 'Gestor da Empresa',
            'cliente_funcionario' => 'Funcionário',
            default => 'Usuário'
        };
    }

    public function getRoleColorAttribute(): string
    {
        return match($this->role) {
            'admin' => 'bg-red-100 text-red-800',
            'tecnico' => 'bg-blue-100 text-blue-800',
            'cliente' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'cnpj',
        'company_name',
        'trade_name',
        'address',
        'phone',
        'email',
        'notes',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Relacionamentos
    public function contacts(): HasMany
    {
        return $this->hasMany(ClientContact::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('company_name', 'like', "%{$search}%")
              ->orWhere('trade_name', 'like', "%{$search}%")
              ->orWhere('cnpj', 'like', "%{$search}%");
        });
    }

    // Métodos úteis
    public function getPrimaryContactAttribute()
    {
        return $this->contacts()->where('is_primary', true)->first();
    }

    public function getActiveTicketCountAttribute(): int
    {
        return $this->tickets()->whereIn('status', ['aberto', 'em_andamento'])->count();
    }

    public function getTotalTicketCountAttribute(): int
    {
        return $this->tickets()->count();
    }

    // Formatação
    public function getFormattedCnpjAttribute(): string
    {
        $cnpj = $this->cnpj;
        return substr($cnpj, 0, 2) . '.' . 
               substr($cnpj, 2, 3) . '.' . 
               substr($cnpj, 5, 3) . '/' . 
               substr($cnpj, 8, 4) . '-' . 
               substr($cnpj, 12, 2);
    }

    /**
     * Obtém o nome de exibição do cliente (prioriza trade_name)
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->trade_name ?? $this->company_name ?? 'N/A';
    }

    /**
     * Obtém a inicial do nome de exibição
     */
    public function getDisplayInitialAttribute(): string
    {
        $name = $this->display_name;
        return strtoupper(substr($name, 0, 1));
    }
}

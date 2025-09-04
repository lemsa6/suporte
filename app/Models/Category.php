<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Relacionamentos
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Métodos úteis
    public function getTicketCountAttribute(): int
    {
        return $this->tickets()->count();
    }

    public function getActiveTicketCountAttribute(): int
    {
        return $this->tickets()->whereIn('status', ['aberto', 'em_andamento'])->count();
    }
}

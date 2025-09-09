<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class QueryOptimizationService
{
    /**
     * Cache duration in minutes
     */
    protected int $cacheDuration = 60;
    
    /**
     * Optimize query with eager loading and caching
     */
    public function optimizeQuery(Builder $query, array $relations = [], string $cacheKey = null, int $cacheMinutes = null): Builder
    {
        // Eager load relations
        if (!empty($relations)) {
            $query->with($relations);
        }
        
        // Apply caching if cache key is provided
        if ($cacheKey) {
            $cacheMinutes = $cacheMinutes ?? $this->cacheDuration;
            $query->remember($cacheMinutes);
        }
        
        return $query;
    }
    
    /**
     * Get paginated results with optimization
     */
    public function getPaginatedResults(Builder $query, int $perPage = 15, array $relations = [], string $cacheKey = null): \Illuminate\Pagination\LengthAwarePaginator
    {
        $query = $this->optimizeQuery($query, $relations, $cacheKey);
        
        return $query->paginate($perPage);
    }
    
    /**
     * Get cached statistics
     */
    public function getCachedStats(string $key, callable $callback, int $cacheMinutes = 60): mixed
    {
        return Cache::remember($key, now()->addMinutes($cacheMinutes), $callback);
    }
    
    /**
     * Clear related caches when model is updated
     */
    public function clearModelCaches(Model $model): void
    {
        $modelName = class_basename($model);
        $cachePattern = strtolower($modelName) . '_*';
        
        // Clear all caches matching the pattern
        $keys = Cache::getRedis()->keys($cachePattern);
        if (!empty($keys)) {
            Cache::getRedis()->del($keys);
        }
    }
    
    /**
     * Optimize ticket queries with proper relations
     */
    public function optimizeTicketQuery(): Builder
    {
        return \App\Models\Ticket::query()
            ->with([
                'client:id,company_name,trade_name,cnpj',
                'category:id,name,color',
                'contact:id,name,email,position',
                'assignedTo:id,name,email',
                'messages' => function ($query) {
                    $query->latest()->limit(5);
                }
            ])
            ->withCount(['messages', 'attachments']);
    }
    
    /**
     * Optimize client queries
     */
    public function optimizeClientQuery(): Builder
    {
        return \App\Models\Client::query()
            ->with([
                'contacts' => function ($query) {
                    $query->select('id', 'client_id', 'name', 'email', 'position', 'is_primary', 'is_active')
                          ->orderBy('is_primary', 'desc')
                          ->orderBy('name');
                }
            ])
            ->withCount([
                'tickets',
                'tickets as active_tickets_count' => function ($query) {
                    $query->whereIn('status', ['aberto', 'em_andamento']);
                }
            ]);
    }
    
    /**
     * Get dashboard statistics with caching
     */
    public function getDashboardStats(): array
    {
        return $this->getCachedStats('dashboard_stats', function () {
            return [
                'total_tickets' => \App\Models\Ticket::count(),
                'open_tickets' => \App\Models\Ticket::where('status', 'aberto')->count(),
                'in_progress_tickets' => \App\Models\Ticket::where('status', 'em_andamento')->count(),
                'resolved_tickets' => \App\Models\Ticket::where('status', 'resolvido')->count(),
                'closed_tickets' => \App\Models\Ticket::where('status', 'fechado')->count(),
                'urgent_tickets' => \App\Models\Ticket::where('is_urgent', true)->count(),
                'total_clients' => \App\Models\Client::count(),
                'active_clients' => \App\Models\Client::where('is_active', true)->count(),
                'total_users' => \App\Models\User::count(),
                'active_users' => \App\Models\User::where('is_active', true)->count(),
            ];
        }, 30); // Cache for 30 minutes
    }
    
    /**
     * Optimize search queries
     */
    public function optimizeSearchQuery(Builder $query, string $searchTerm, array $searchFields): Builder
    {
        return $query->where(function ($q) use ($searchTerm, $searchFields) {
            foreach ($searchFields as $field) {
                $q->orWhere($field, 'like', "%{$searchTerm}%");
            }
        });
    }
    
    /**
     * Get recent activity with optimization
     */
    public function getRecentActivity(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return \App\Models\AuditLog::query()
            ->with(['user:id,name,email'])
            ->latest()
            ->limit($limit)
            ->get();
    }
}

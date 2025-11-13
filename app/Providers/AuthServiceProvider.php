<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Ticket;
use App\Models\Client;
use App\Models\User;
use App\Models\Category;
use App\Models\ClientContact;
use App\Policies\TicketPolicy;
use App\Policies\ClientPolicy;
use App\Policies\UserPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\ClientUserPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Ticket::class => TicketPolicy::class,
        Client::class => ClientPolicy::class,
        User::class => UserPolicy::class,
        Category::class => CategoryPolicy::class,
        ClientContact::class => ClientUserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Gates para funcionalidades específicas
        Gate::define('manage-tickets', function (User $user) {
            return $user->canManageTickets();
        });
        
        // Gate para atribuir tickets (usa a Policy)
        Gate::define('assign-tickets', [TicketPolicy::class, 'assign']);

        Gate::define('manage-clients', function (User $user) {
            return $user->canManageClients();
        });

        Gate::define('manage-users', function (User $user) {
            return $user->canManageUsers();
        });

        Gate::define('manage-categories', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('view-dashboard', function (User $user) {
            return true; // Todos os usuários autenticados podem ver o dashboard
        });

        Gate::define('create-tickets', function (User $user) {
            return true; // Todos os usuários autenticados podem criar tickets
        });

        Gate::define('view-reports', function (User $user) {
            return $user->isAdmin() || $user->isTecnico();
        });

        Gate::define('export-data', function (User $user) {
            return $user->isAdmin() || $user->isTecnico();
        });

        Gate::define('manage-system', function (User $user) {
            return $user->isAdmin();
        });
    }
}

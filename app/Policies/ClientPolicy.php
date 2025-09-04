<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;

class ClientPolicy
{
    /**
     * Determina se o usuário pode ver a lista de clientes
     */
    public function viewAny(User $user): bool
    {
        // Apenas Admin e Técnico podem ver lista de clientes
        return $user->isAdmin() || $user->isTecnico();
    }

    /**
     * Determina se o usuário pode ver um cliente específico
     */
    public function view(User $user, Client $client): bool
    {
        // Admin pode ver qualquer cliente
        if ($user->isAdmin()) {
            return true;
        }
        
        // Técnico pode ver qualquer cliente
        if ($user->isTecnico()) {
            return true;
        }
        
        // Cliente pode ver apenas sua própria empresa
        if ($user->isCliente()) {
            return $client->contacts()->where('email', $user->email)->exists();
        }
        
        return false;
    }

    /**
     * Determina se o usuário pode criar clientes
     */
    public function create(User $user): bool
    {
        // Apenas Admin pode criar clientes
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode atualizar o cliente
     */
    public function update(User $user, Client $client): bool
    {
        // Apenas Admin pode atualizar clientes
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode deletar o cliente
     */
    public function delete(User $user, Client $client): bool
    {
        // Apenas Admin pode deletar clientes
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode gerenciar clientes
     */
    public function manage(User $user): bool
    {
        // Apenas Admin pode gerenciar clientes
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode ver estatísticas do cliente
     */
    public function viewStats(User $user, Client $client): bool
    {
        // Admin pode ver estatísticas de qualquer cliente
        if ($user->isAdmin()) {
            return true;
        }
        
        // Técnico pode ver estatísticas de qualquer cliente
        if ($user->isTecnico()) {
            return true;
        }
        
        // Cliente pode ver estatísticas da sua própria empresa
        if ($user->isCliente()) {
            return $client->contacts()->where('email', $user->email)->exists();
        }
        
        return false;
    }

    /**
     * Determina se o usuário pode ver histórico de tickets do cliente
     */
    public function viewTicketHistory(User $user, Client $client): bool
    {
        return $this->view($user, $client);
    }

    /**
     * Determina se o usuário pode ver contatos do cliente
     */
    public function viewContacts(User $user, Client $client): bool
    {
        return $this->view($user, $client);
    }

    /**
     * Determina se o usuário pode gerenciar contatos do cliente
     */
    public function manageContacts(User $user, Client $client): bool
    {
        // Apenas Admin pode gerenciar contatos
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode ativar/desativar cliente
     */
    public function toggleStatus(User $user, Client $client): bool
    {
        // Apenas Admin pode ativar/desativar clientes
        return $user->isAdmin();
    }
}

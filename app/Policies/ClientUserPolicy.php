<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Client;
use App\Models\ClientContact;

class ClientUserPolicy
{
    /**
     * Determine whether the user can view any client users.
     */
    public function viewAny(User $user, Client $client): bool
    {
        return $this->canManageClient($user, $client);
    }

    /**
     * Determine whether the user can view the client user.
     */
    public function view(User $user, ClientContact $contact): bool
    {
        return $this->canManageClient($user, $contact->client);
    }

    /**
     * Determine whether the user can create client users.
     */
    public function create(User $user, Client $client): bool
    {
        return $this->canManageClient($user, $client);
    }

    /**
     * Determine whether the user can update the client user.
     */
    public function update(User $user, ClientContact $contact): bool
    {
        return $this->canManageClient($user, $contact->client);
    }

    /**
     * Determine whether the user can delete the client user.
     */
    public function delete(User $user, ClientContact $contact): bool
    {
        // Não permitir deletar contato principal
        if ($contact->is_primary) {
            return false;
        }

        return $this->canManageClient($user, $contact->client);
    }

    /**
     * Determine whether the user can toggle status of the client user.
     */
    public function toggleStatus(User $user, ClientContact $contact): bool
    {
        // Não permitir desativar contato principal
        if ($contact->is_primary) {
            return false;
        }

        return $this->canManageClient($user, $contact->client);
    }

    /**
     * Verificar se o usuário pode gerenciar a empresa
     */
    private function canManageClient(User $user, Client $client): bool
    {
        // Debug temporário
        \Log::info('ClientUserPolicy@canManageClient - Debug', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'is_admin' => $user->isAdmin(),
            'is_tecnico' => $user->isTecnico(),
            'client_id' => $client->id
        ]);
        
        // Admin e técnico podem gerenciar qualquer empresa
        if ($user->isAdmin() || $user->isTecnico()) {
            \Log::info('ClientUserPolicy: Admin/Tecnico access granted');
            return true;
        }

        // Gestor pode gerenciar apenas sua própria empresa
        if ($user->isClienteGestor()) {
            // Verificar tanto por user_id (novo) quanto por email (fallback)
            $canManage = $client->contacts()
                ->where(function ($query) use ($user) {
                    $query->where('user_id', $user->id)
                          ->orWhere('email', $user->email);
                })
                ->where('user_type', 'cliente_gestor')
                ->exists();
                
            \Log::info('ClientUserPolicy: Gestor check result', ['can_manage' => $canManage]);
            return $canManage;
        }

        \Log::info('ClientUserPolicy: Access denied - no matching role');
        return false;
    }
}

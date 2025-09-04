<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determina se o usuário pode ver a lista de usuários
     */
    public function viewAny(User $user): bool
    {
        // Apenas Admin pode ver lista de usuários
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode ver outro usuário
     */
    public function view(User $user, User $model): bool
    {
        // Admin pode ver qualquer usuário
        if ($user->isAdmin()) {
            return true;
        }
        
        // Usuário pode ver a si mesmo
        if ($user->id === $model->id) {
            return true;
        }
        
        // Técnico pode ver outros técnicos e clientes
        if ($user->isTecnico()) {
            return $model->isTecnico() || $model->isCliente();
        }
        
        return false;
    }

    /**
     * Determina se o usuário pode criar usuários
     */
    public function create(User $user): bool
    {
        // Apenas Admin pode criar usuários
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode atualizar outro usuário
     */
    public function update(User $user, User $model): bool
    {
        // Admin pode atualizar qualquer usuário
        if ($user->isAdmin()) {
            return true;
        }
        
        // Usuário pode atualizar a si mesmo
        if ($user->id === $model->id) {
            return true;
        }
        
        // Técnico pode atualizar clientes
        if ($user->isTecnico() && $model->isCliente()) {
            return true;
        }
        
        return false;
    }

    /**
     * Determina se o usuário pode deletar outro usuário
     */
    public function delete(User $user, User $model): bool
    {
        // Apenas Admin pode deletar usuários
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode gerenciar usuários
     */
    public function manage(User $user): bool
    {
        // Apenas Admin pode gerenciar usuários
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode alterar role de outro usuário
     */
    public function changeRole(User $user, User $model): bool
    {
        // Apenas Admin pode alterar roles
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode ativar/desativar outro usuário
     */
    public function toggleStatus(User $user, User $model): bool
    {
        // Apenas Admin pode ativar/desativar usuários
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode ver estatísticas de outro usuário
     */
    public function viewStats(User $user, User $model): bool
    {
        // Admin pode ver estatísticas de qualquer usuário
        if ($user->isAdmin()) {
            return true;
        }
        
        // Técnico pode ver estatísticas de clientes
        if ($user->isTecnico() && $model->isCliente()) {
            return true;
        }
        
        // Usuário pode ver suas próprias estatísticas
        if ($user->id === $model->id) {
            return true;
        }
        
        return false;
    }

    /**
     * Determina se o usuário pode ver tickets de outro usuário
     */
    public function viewTickets(User $user, User $model): bool
    {
        // Admin pode ver tickets de qualquer usuário
        if ($user->isAdmin()) {
            return true;
        }
        
        // Técnico pode ver tickets de clientes
        if ($user->isTecnico() && $model->isCliente()) {
            return true;
        }
        
        // Usuário pode ver seus próprios tickets
        if ($user->id === $model->id) {
            return true;
        }
        
        return false;
    }

    /**
     * Determina se o usuário pode redefinir senha de outro usuário
     */
    public function resetPassword(User $user, User $model): bool
    {
        // Apenas Admin pode redefinir senhas
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode ver perfil completo de outro usuário
     */
    public function viewFullProfile(User $user, User $model): bool
    {
        // Admin pode ver perfil completo de qualquer usuário
        if ($user->isAdmin()) {
            return true;
        }
        
        // Usuário pode ver seu próprio perfil completo
        if ($user->id === $model->id) {
            return true;
        }
        
        return false;
    }
}

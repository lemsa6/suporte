<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    /**
     * Determina se o usuário pode ver a lista de categorias
     */
    public function viewAny(User $user): bool
    {
        // Todos os usuários autenticados podem ver categorias
        return true;
    }

    /**
     * Determina se o usuário pode ver uma categoria específica
     */
    public function view(User $user, Category $category): bool
    {
        // Todos os usuários autenticados podem ver categorias ativas
        return $category->is_active;
    }

    /**
     * Determina se o usuário pode criar categorias
     */
    public function create(User $user): bool
    {
        // Apenas Admin pode criar categorias
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode atualizar a categoria
     */
    public function update(User $user, Category $category): bool
    {
        // Apenas Admin pode atualizar categorias
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode deletar a categoria
     */
    public function delete(User $user, Category $category): bool
    {
        // Apenas Admin pode deletar categorias
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode gerenciar categorias
     */
    public function manage(User $user): bool
    {
        // Apenas Admin pode gerenciar categorias
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode ativar/desativar categoria
     */
    public function toggleStatus(User $user, Category $category): bool
    {
        // Apenas Admin pode ativar/desativar categorias
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode ver estatísticas da categoria
     */
    public function viewStats(User $user, Category $category): bool
    {
        // Admin pode ver estatísticas de qualquer categoria
        if ($user->isAdmin()) {
            return true;
        }
        
        // Técnico pode ver estatísticas de categorias
        if ($user->isTecnico()) {
            return true;
        }
        
        // Cliente pode ver estatísticas de categorias ativas
        if ($user->isCliente()) {
            return $category->is_active;
        }
        
        return false;
    }

    /**
     * Determina se o usuário pode ver tickets de uma categoria
     */
    public function viewTickets(User $user, Category $category): bool
    {
        // Todos os usuários autenticados podem ver tickets de categorias ativas
        return $category->is_active;
    }
}

<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    /**
     * Determina se o usuário pode ver o ticket
     */
    public function view(User $user, Ticket $ticket): bool
    {
        // Admin pode ver todos os tickets
        if ($user->isAdmin()) {
            return true;
        }
        
        // Técnico pode ver tickets atribuídos a ele ou não atribuídos
        if ($user->isTecnico()) {
            return $ticket->assigned_to === $user->id || is_null($ticket->assigned_to);
        }
        
        // Cliente gestor pode ver qualquer ticket da empresa
        if ($user->isClienteGestor()) {
            return $ticket->client->contacts->contains('email', $user->email);
        }
        
        // Cliente funcionário pode ver apenas seus próprios tickets
        if ($user->isClienteFuncionario()) {
            return $ticket->contact->email === $user->email;
        }
        
        return false;
    }

    /**
     * Determina se o usuário pode criar tickets
     */
    public function create(User $user): bool
    {
        // Cliente gestor e funcionário podem criar tickets
        if ($user->isClienteGestor() || $user->isClienteFuncionario()) {
            return true;
        }
        
        // Técnico e Admin podem criar tickets
        if ($user->isTecnico() || $user->isAdmin()) {
            return true;
        }
        
        return false;
    }

    /**
     * Determina se o usuário pode atualizar o ticket
     */
    public function update(User $user, Ticket $ticket): bool
    {
        // Admin pode atualizar qualquer ticket
        if ($user->isAdmin()) {
            return true;
        }
        
        // Técnico pode atualizar tickets atribuídos a ele
        if ($user->isTecnico()) {
            return $ticket->assigned_to === $user->id;
        }
        
        // Cliente gestor pode atualizar tickets abertos da empresa
        if ($user->isClienteGestor()) {
            return $ticket->client->contacts->contains('email', $user->email) && 
                   $ticket->status === 'aberto';
        }
        
        // Cliente funcionário pode atualizar apenas seus próprios tickets abertos
        if ($user->isClienteFuncionario()) {
            return $ticket->contact->email === $user->email && 
                   $ticket->status === 'aberto';
        }
        
        return false;
    }

    /**
     * Determina se o usuário pode deletar o ticket
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        // Apenas Admin pode deletar tickets
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode responder ao ticket
     */
    public function reply(User $user, Ticket $ticket): bool
    {
        // Admin pode responder a qualquer ticket
        if ($user->isAdmin()) {
            return true;
        }
        
        // Técnico pode responder a tickets atribuídos a ele
        if ($user->isTecnico()) {
            return $ticket->assigned_to === $user->id;
        }
        
        // Cliente gestor pode responder a qualquer ticket da empresa
        if ($user->isClienteGestor()) {
            return $ticket->client->contacts->contains('email', $user->email);
        }
        
        // Cliente funcionário pode responder apenas aos seus próprios tickets
        if ($user->isClienteFuncionario()) {
            return $ticket->contact->email === $user->email;
        }
        
        return false;
    }

    /**
     * Determina se o usuário pode atribuir o ticket
     */
    public function assign(User $user, Ticket $ticket): bool
    {
        // Apenas Admin e Técnico podem atribuir tickets
        if ($user->isAdmin() || $user->isTecnico()) {
            return true;
        }
        
        return false;
    }

    /**
     * Determina se o usuário pode reabrir o ticket
     */
    public function reopen(User $user, Ticket $ticket): bool
    {
        // Apenas Admin e Técnico podem reabrir tickets
        if ($user->isAdmin() || $user->isTecnico()) {
            return $ticket->canBeReopened();
        }
        
        return false;
    }

    /**
     * Determina se o usuário pode ver mensagens internas
     */
    public function viewInternalMessages(User $user, Ticket $ticket): bool
    {
        // Apenas Admin e Técnico podem ver mensagens internas
        return $user->isAdmin() || $user->isTecnico();
    }

    /**
     * Determina se o usuário pode criar mensagens internas
     */
    public function createInternalMessage(User $user, Ticket $ticket): bool
    {
        // Apenas Admin e Técnico podem criar mensagens internas
        return $user->isAdmin() || $user->isTecnico();
    }

    /**
     * Determina se o usuário pode ver anexos
     */
    public function viewAttachments(User $user, Ticket $ticket): bool
    {
        return $this->view($user, $ticket);
    }

    /**
     * Determina se o usuário pode fazer upload de anexos
     */
    public function uploadAttachments(User $user, Ticket $ticket): bool
    {
        return $this->reply($user, $ticket);
    }

    /**
     * Determina se o usuário pode ver histórico completo
     */
    public function viewHistory(User $user, Ticket $ticket): bool
    {
        return $this->view($user, $ticket);
    }

    /**
     * Determina se o usuário pode ver estatísticas do ticket
     */
    public function viewStats(User $user, Ticket $ticket): bool
    {
        // Apenas Admin e Técnico podem ver estatísticas
        return $user->isAdmin() || $user->isTecnico();
    }
}

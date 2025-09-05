<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\User;
use App\Models\TicketMessage;
use App\Notifications\TicketAssigned;
use App\Notifications\TicketClosed;
use App\Notifications\TicketUrgent;
use App\Notifications\TicketReplyNotification;
use App\Notifications\NewTicketCreated;
use App\Notifications\TicketStatusChange;
use App\Notifications\TicketPriorityChange;
use App\Mail\ClientTicketCreatedMail;
use App\Mail\ClientTicketCreatedForYouMail;
use App\Mail\ClientTicketRepliedMail;
use App\Mail\ClientTicketStatusChangedMail;
use App\Mail\ClientTicketClosedMail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * Notificar sobre novo ticket criado
     */
    public function notifyNewTicket(Ticket $ticket, $createdBy = null): void
    {
        // Notificar todos os administradores e técnicos (exceto quem criou)
        $users = User::whereIn('role', ['admin', 'tecnico'])
            ->where('is_active', true)
            ->where('notify_ticket_created', true)
            ->where('id', '!=', $createdBy?->id)
            ->get();

        if ($users->isNotEmpty()) {
            Notification::send($users, new NewTicketCreated($ticket));
        }

        // Notificar quem criou o ticket (se for usuário interno e tiver preferência ativada)
        if ($createdBy && $createdBy->notify_ticket_created) {
            $createdBy->notify(new NewTicketCreated($ticket));
        }

        // Notificar o cliente destinatário do ticket
        if ($ticket->contact && $ticket->contact->email) {
            // Se foi criado por usuário interno (admin/tecnico), usar notificação específica
            if ($createdBy && in_array($createdBy->role, ['admin', 'tecnico'])) {
                $this->notifyClientTicketCreatedForYou($ticket, $createdBy);
            } else {
                $this->notifyClientTicketCreated($ticket);
            }
        }
    }

    /**
     * Notificar cliente sobre ticket criado por ele mesmo
     */
    public function notifyClientTicketCreated(Ticket $ticket): void
    {
        if ($ticket->contact && $ticket->contact->email) {
            Mail::to($ticket->contact->email)->send(new ClientTicketCreatedMail($ticket));
        }
    }

    /**
     * Notificar cliente sobre ticket criado PARA ele (por admin/tecnico)
     */
    public function notifyClientTicketCreatedForYou(Ticket $ticket, $createdBy = null): void
    {
        if ($ticket->contact && $ticket->contact->email) {
            Mail::to($ticket->contact->email)->send(new ClientTicketCreatedForYouMail($ticket, $createdBy));
        }
    }

    /**
     * Notificar sobre nova resposta
     */
    public function notifyTicketReply(Ticket $ticket, TicketMessage $reply, $repliedBy = null): void
    {
        // Notificar usuários internos
        $users = User::whereIn('role', ['admin', 'tecnico'])
            ->where('is_active', true)
            ->where('notify_ticket_replied', true)
            ->where('id', '!=', $repliedBy?->id)
            ->get();

        if ($users->isNotEmpty()) {
            Notification::send($users, new TicketReplyNotification($ticket, $reply));
        }

        // Notificar cliente se a resposta não for interna
        if (!$reply->is_internal && $ticket->contact && $ticket->contact->email) {
            $this->notifyClientTicketReplied($ticket, $reply, $repliedBy);
        }
    }

    /**
     * Notificar cliente sobre nova resposta
     */
    public function notifyClientTicketReplied(Ticket $ticket, TicketMessage $reply, $repliedBy = null): void
    {
        if ($ticket->contact && $ticket->contact->email) {
            Mail::to($ticket->contact->email)->send(new ClientTicketRepliedMail($ticket, $reply, $repliedBy));
        }
    }

    /**
     * Notificar sobre mudança de status
     */
    public function notifyStatusChange(Ticket $ticket, string $oldStatus, string $newStatus, $changedBy = null): void
    {
        // Notificar usuários internos
        $users = User::whereIn('role', ['admin', 'tecnico'])
            ->where('is_active', true)
            ->where('notify_ticket_status_changed', true)
            ->where('id', '!=', $changedBy?->id)
            ->get();

        if ($users->isNotEmpty()) {
            Notification::send($users, new TicketStatusChange($ticket, $oldStatus, $newStatus, $changedBy));
        }

        // Notificar cliente
        if ($ticket->contact && $ticket->contact->email) {
            $this->notifyClientTicketStatusChanged($ticket, $oldStatus, $newStatus, $changedBy);
        }
    }

    /**
     * Notificar cliente sobre mudança de status
     */
    public function notifyClientTicketStatusChanged(Ticket $ticket, string $oldStatus, string $newStatus, $changedBy = null): void
    {
        if ($ticket->contact && $ticket->contact->email) {
            Mail::to($ticket->contact->email)->send(new ClientTicketStatusChangedMail($ticket, $oldStatus, $newStatus, $changedBy));
        }
    }

    /**
     * Notificar sobre ticket fechado
     */
    public function notifyTicketClosed(Ticket $ticket, $closedBy = null): void
    {
        // Notificar usuários internos
        $users = User::whereIn('role', ['admin', 'tecnico'])
            ->where('is_active', true)
            ->where('notify_ticket_closed', true)
            ->where('id', '!=', $closedBy?->id)
            ->get();

        if ($users->isNotEmpty()) {
            Notification::send($users, new TicketClosed($ticket, $closedBy));
        }

        // Notificar cliente
        if ($ticket->contact && $ticket->contact->email) {
            $this->notifyClientTicketClosed($ticket, $closedBy);
        }
    }

    /**
     * Notificar cliente sobre ticket fechado
     */
    public function notifyClientTicketClosed(Ticket $ticket, $closedBy = null): void
    {
        if ($ticket->contact && $ticket->contact->email) {
            Mail::to($ticket->contact->email)->send(new ClientTicketClosedMail($ticket, $closedBy));
        }
    }

    /**
     * Notificar sobre atribuição de ticket
     */
    public function notifyTicketAssigned(Ticket $ticket, User $assignedTo, $assignedBy = null): void
    {
        if ($assignedTo->notify_ticket_created) {
            $assignedTo->notify(new TicketAssigned($ticket, $assignedBy));
        }
    }

    /**
     * Notificar sobre mudança de prioridade
     */
    public function notifyPriorityChange(Ticket $ticket, string $oldPriority, string $newPriority, $changedBy = null): void
    {
        $users = User::whereIn('role', ['admin', 'tecnico'])
            ->where('is_active', true)
            ->where('notify_ticket_priority_changed', true)
            ->where('id', '!=', $changedBy?->id)
            ->get();

        if ($users->isNotEmpty()) {
            Notification::send($users, new TicketPriorityChange($ticket, $oldPriority, $newPriority, $changedBy));
        }
    }

    /**
     * Notificar sobre ticket urgente
     */
    public function notifyTicketUrgent(Ticket $ticket, $markedBy = null): void
    {
        $users = User::whereIn('role', ['admin', 'tecnico'])
            ->where('is_active', true)
            ->where('notify_ticket_urgent', true)
            ->where('id', '!=', $markedBy?->id)
            ->get();

        if ($users->isNotEmpty()) {
            Notification::send($users, new TicketUrgent($ticket, $markedBy));
        }
    }
}
<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\User;
use App\Models\TicketReply;
use App\Notifications\TicketAssigned;
use App\Notifications\TicketClosed;
use App\Notifications\TicketUrgent;
use App\Notifications\TicketReplyNotification;
use App\Notifications\NewTicketCreated;
use App\Notifications\TicketStatusChange;
use App\Notifications\TicketPriorityChange;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    /**
     * Notificar sobre novo ticket criado
     */
    public function notifyNewTicket(Ticket $ticket): void
    {
        // Notificar todos os administradores e técnicos
        $users = User::whereIn('role', ['admin', 'tecnico'])
            ->where('is_active', true)
            ->get();

        if ($users->isNotEmpty()) {
            Notification::send($users, new NewTicketCreated($ticket));
        }

        // Se for ticket urgente, notificar imediatamente
        if ($ticket->priority === 'alta') {
            $this->notifyUrgentTicket($ticket);
        }
    }

    /**
     * Notificar sobre ticket atribuído
     */
    public function notifyTicketAssigned(Ticket $ticket, User $assignedUser): void
    {
        if ($assignedUser && $assignedUser->is_active) {
            $assignedUser->notify(new TicketAssigned($ticket));
        }
    }

    /**
     * Notificar sobre ticket fechado
     */
    public function notifyTicketClosed(Ticket $ticket, User $closedBy = null): void
    {
        // Notificar o contato que abriu o ticket
        if ($ticket->contact && $ticket->contact->email) {
            // Aqui você pode implementar notificação para e-mails externos
            // Por enquanto, vamos notificar usuários internos
        }

        // Notificar o técnico responsável
        if ($ticket->assignedTo) {
            $ticket->assignedTo->notify(new TicketClosed($ticket, $closedBy));
        }

        // Notificar administradores
        $admins = User::where('role', 'admin')
            ->where('is_active', true)
            ->get();

        if ($admins->isNotEmpty()) {
            Notification::send($admins, new TicketClosed($ticket, $closedBy));
        }
    }

    /**
     * Notificar sobre ticket urgente
     */
    public function notifyUrgentTicket(Ticket $ticket): void
    {
        // Notificar todos os administradores e técnicos sobre ticket urgente
        $users = User::whereIn('role', ['admin', 'tecnico'])
            ->where('is_active', true)
            ->get();

        if ($users->isNotEmpty()) {
            Notification::send($users, new TicketUrgent($ticket));
        }
    }

    /**
     * Notificar sobre nova resposta ao ticket
     */
    public function notifyTicketReply(Ticket $ticket, TicketReply $reply, User $repliedBy = null): void
    {
        $recipients = collect();

        // Adicionar o técnico responsável (se houver)
        if ($ticket->assignedTo && $ticket->assignedTo->id !== $repliedBy?->id) {
            $recipients->push($ticket->assignedTo);
        }

        // Adicionar administradores
        $admins = User::where('role', 'admin')
            ->where('is_active', true)
            ->where('id', '!=', $repliedBy?->id)
            ->get();

        $recipients = $recipients->merge($admins);

        // Se a resposta não for interna, notificar o contato do cliente
        if (!$reply->is_internal && $ticket->contact && $ticket->contact->email) {
            // Aqui você pode implementar notificação para e-mails externos
        }

        // Enviar notificações
        if ($recipients->isNotEmpty()) {
            Notification::send($recipients, new TicketReplyNotification($ticket, $reply, $repliedBy));
        }
    }

    /**
     * Notificar sobre mudança de status do ticket
     */
    public function notifyStatusChange(Ticket $ticket, string $oldStatus, string $newStatus, User $changedBy = null): void
    {
        // Se o status mudou para "fechado", usar a notificação específica
        if ($newStatus === 'fechado') {
            $this->notifyTicketClosed($ticket, $changedBy);
            return;
        }

        // Se o status mudou para "em andamento" e foi atribuído, usar a notificação específica
        if ($newStatus === 'em andamento' && $ticket->assignedTo) {
            $this->notifyTicketAssigned($ticket, $ticket->assignedTo);
            return;
        }

        // Para outras mudanças de status, notificar administradores e técnicos
        $users = User::whereIn('role', ['admin', 'tecnico'])
            ->where('is_active', true)
            ->get();

        if ($users->isNotEmpty()) {
            Notification::send($users, new TicketStatusChange($ticket, $oldStatus, $newStatus, $changedBy));
        }

        // Notificar o contato do cliente se não for mudança para fechado
        if ($ticket->contact && $ticket->contact->email) {
            // Aqui você pode implementar notificação para e-mails externos
        }
    }

    /**
     * Notificar sobre mudança de prioridade do ticket
     */
    public function notifyPriorityChange(Ticket $ticket, string $oldPriority, string $newPriority, User $changedBy = null): void
    {
        // Se a prioridade mudou para alta, notificar como urgente
        if ($newPriority === 'alta') {
            $this->notifyUrgentTicket($ticket);
        }

        // Notificar administradores e técnicos sobre mudança de prioridade
        $users = User::whereIn('role', ['admin', 'tecnico'])
            ->where('is_active', true)
            ->get();

        if ($users->isNotEmpty()) {
            Notification::send($users, new TicketPriorityChange($ticket, $oldPriority, $newPriority, $changedBy));
        }

        // Notificar o contato do cliente sobre mudança de prioridade
        if ($ticket->contact && $ticket->contact->email) {
            // Aqui você pode implementar notificação para e-mails externos
        }
    }

    /**
     * Limpar notificações antigas (método de manutenção)
     */
    public function cleanOldNotifications(int $daysOld = 30): void
    {
        // Implementar limpeza de notificações antigas se necessário
        // Por padrão, o Laravel já gerencia isso automaticamente
    }
}

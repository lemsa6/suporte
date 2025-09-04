<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketStatusChange extends Notification implements ShouldQueue
{
    use Queueable;

    public $ticket;
    public $oldStatus;
    public $newStatus;
    public $changedBy;

    public function __construct(Ticket $ticket, string $oldStatus, string $newStatus, $changedBy = null)
    {
        $this->ticket = $ticket;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->changedBy = $changedBy;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $statusEmojis = [
            'aberto' => '🟡',
            'em_andamento' => '🔵',
            'resolvido' => '🟢',
            'fechado' => '⚫'
        ];

        $oldStatusEmoji = $statusEmojis[$this->oldStatus] ?? '⚪';
        $newStatusEmoji = $statusEmojis[$this->newStatus] ?? '⚪';

        $changedByText = $this->changedBy ? " por {$this->changedBy->name}" : "";

        return (new MailMessage)
            ->subject("🔄 Status do Ticket #{$this->ticket->ticket_number} alterado")
            ->greeting("Olá {$notifiable->name}!")
            ->line("O status de um ticket foi alterado{$changedByText}.")
            ->line("• **Status Anterior:** {$oldStatusEmoji} " . ucfirst($this->oldStatus))
            ->line("• **Novo Status:** {$newStatusEmoji} " . ucfirst($this->newStatus))
            ->line("• **Ticket:** {$this->ticket->title}")
            ->line("• **Cliente:** {$this->ticket->client->company_name}")
            ->action('Ver Ticket', url("/tickets/{$this->ticket->id}"))
            ->salutation("Atenciosamente,\nEquipe de Suporte Técnico - 8Bits Pro");
    }

    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'changed_by' => $this->changedBy?->id,
            'message' => "Status alterado de {$this->oldStatus} para {$this->newStatus}"
        ];
    }
}

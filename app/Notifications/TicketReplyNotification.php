<?php

namespace App\Notifications;

use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketReplyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $ticket;
    public $reply;
    public $repliedBy;

    /**
     * Create a new notification instance.
     */
    public function __construct(Ticket $ticket, TicketMessage $reply, $repliedBy = null)
    {
        $this->ticket = $ticket;
        $this->reply = $reply;
        $this->repliedBy = $repliedBy;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $repliedByText = $this->repliedBy ? " por {$this->repliedBy->name}" : "";
        $isInternal = $this->reply->is_internal ? " (INTERNO)" : "";

        return (new MailMessage)
            ->subject("💬 Nova resposta no Ticket #{$this->ticket->ticket_number}")
            ->greeting("Olá {$notifiable->name}!")
            ->line("Uma nova resposta foi adicionada ao ticket{$repliedByText}.")
            ->line("**Detalhes do Ticket:**")
            ->line("• **Número:** #{$this->ticket->ticket_number}")
            ->line("• **Título:** {$this->ticket->title}")
            ->line("• **Cliente:** {$this->ticket->client->company_name}")
            ->line("• **Categoria:** {$this->ticket->category->name}")
            ->line("• **Status:** {$this->ticket->status}")
            ->line("• **Prioridade:** {$this->ticket->priority}")
            ->line("**Nova Resposta{$isInternal}:**")
            ->line($this->reply->content)
            ->line("**Respondido em:** " . $this->reply->created_at->format('d/m/Y H:i'))
            ->action('Ver Ticket', route('tickets.show', $this->ticket->ticket_number))
            ->line("Clique no botão acima para visualizar o ticket completo e responder se necessário.")
            ->salutation("Atenciosamente,\nEquipe de Suporte Técnico - 8Bits Pro");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'title' => $this->ticket->title,
            'client' => $this->ticket->client->company_name,
            'category' => $this->ticket->category->name,
            'status' => $this->ticket->status,
            'priority' => $this->ticket->priority,
            'reply_id' => $this->reply->id,
            'reply_content' => $this->reply->content,
            'replied_by' => $this->repliedBy ? $this->repliedBy->name : null,
            'is_internal' => $this->reply->is_internal,
            'replied_at' => $this->reply->created_at,
        ];
    }
}

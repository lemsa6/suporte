<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketAssigned extends Notification implements ShouldQueue
{
    use Queueable;

    public $ticket;

    /**
     * Create a new notification instance.
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
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
        $priorityColors = [
            'baixa' => '#10B981',
            'média' => '#F59E0B',
            'alta' => '#EF4444',
        ];

        $priorityColor = $priorityColors[$this->ticket->priority] ?? '#6B7280';

        return (new MailMessage)
            ->subject("🎯 Ticket #{$this->ticket->ticket_number} atribuído a você")
            ->greeting("Olá {$notifiable->name}!")
            ->line("Um novo ticket foi atribuído a você.")
            ->line("**Detalhes do Ticket:**")
            ->line("• **Número:** #{$this->ticket->ticket_number}")
            ->line("• **Título:** {$this->ticket->title}")
            ->line("• **Cliente:** {$this->ticket->client->company_name}")
            ->line("• **Categoria:** {$this->ticket->category->name}")
            ->line("• **Prioridade:** {$this->ticket->priority}")
            ->line("• **Status:** {$this->ticket->status}")
            ->line("• **Aberto por:** {$this->ticket->contact->name}")
            ->line("• **Data de abertura:** " . $this->ticket->created_at->format('d/m/Y H:i'))
            ->action('Ver Ticket', url("/tickets/{$this->ticket->id}"))
            ->line("Por favor, analise o ticket e atualize o status conforme necessário.")
            ->salutation("Atenciosamente,\nEquipe de Suporte Técnico");
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
            'priority' => $this->ticket->priority,
            'status' => $this->ticket->status,
            'assigned_at' => now(),
        ];
    }
}

<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketUrgent extends Notification implements ShouldQueue
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
        return (new MailMessage)
            ->subject("🚨 URGENTE: Ticket #{$this->ticket->ticket_number} requer atenção imediata!")
            ->greeting("ALERTA URGENTE!")
            ->line("Um ticket de alta prioridade foi criado e requer atenção imediata.")
            ->line("**Detalhes do Ticket:**")
            ->line("• **Número:** #{$this->ticket->ticket_number}")
            ->line("• **Título:** {$this->ticket->title}")
            ->line("• **Cliente:** {$this->ticket->client->company_name}")
            ->line("• **Categoria:** {$this->ticket->category->name}")
            ->line("• **Prioridade:** {$this->ticket->priority}")
            ->line("• **Status:** {$this->ticket->status}")
            ->line("• **Aberto por:** {$this->ticket->contact->name}")
            ->line("• **Telefone:** {$this->ticket->contact->phone}")
            ->line("• **E-mail:** {$this->ticket->contact->email}")
            ->line("• **Data de abertura:** " . $this->ticket->created_at->format('d/m/Y H:i'))
            ->action('Ver Ticket Urgente', url("/tickets/{$this->ticket->id}"))
            ->line("⚠️ Este ticket requer resposta imediata devido à sua alta prioridade.")
            ->salutation("Equipe de Suporte Técnico - 8Bits Pro");
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
            'contact_phone' => $this->ticket->contact->phone,
            'contact_email' => $this->ticket->contact->email,
            'created_at' => $this->ticket->created_at,
        ];
    }
}

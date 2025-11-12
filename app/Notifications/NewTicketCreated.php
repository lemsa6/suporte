<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewTicketCreated extends Notification implements ShouldQueue
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
        $priorityEmoji = [
            'baixa' => '🟢',
            'média' => '🟡',
            'alta' => '🔴',
        ];

        $priorityEmojiText = $priorityEmoji[$this->ticket->priority] ?? '⚪';

        return (new MailMessage)
            ->subject("🎫 Novo Ticket #{$this->ticket->ticket_number} criado")
            ->greeting("Olá {$notifiable->name}!")
            ->line("Um novo ticket foi criado no sistema.")
            ->line("**Detalhes do Ticket:**")
            ->line("• **Número:** #{$this->ticket->ticket_number}")
            ->line("• **Título:** {$this->ticket->title}")
            ->line("• **Cliente:** {$this->ticket->client->company_name}")
            ->line("• **Categoria:** {$this->ticket->category->name}")
            ->line("• **Prioridade:** {$priorityEmojiText} {$this->ticket->priority}")
            ->line("• **Status:** {$this->ticket->status}")
            ->line("• **Aberto por:** {$this->ticket->contact->name}")
            ->line("• **Telefone:** {$this->ticket->contact->phone}")
            ->line("• **E-mail:** {$this->ticket->contact->email}")
            ->line("• **Data de abertura:** " . $this->ticket->created_at->format('d/m/Y H:i'))
            ->line("• **Descrição:** " . substr($this->ticket->description, 0, 200) . (strlen($this->ticket->description) > 200 ? '...' : ''))
            ->action('Ver Ticket', route('tickets.show', $this->ticket->ticket_number))
            ->line("Este ticket está aguardando análise e atribuição.")
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
            'priority' => $this->ticket->priority,
            'status' => $this->ticket->status,
            'contact_name' => $this->ticket->contact->name,
            'contact_phone' => $this->ticket->contact->phone,
            'contact_email' => $this->ticket->contact->email,
            'description' => $this->ticket->description,
            'created_at' => $this->ticket->created_at,
        ];
    }
}

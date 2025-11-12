<?php

namespace App\Notifications;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClientTicketCreatedForYou extends Notification implements ShouldQueue
{
    use Queueable;

    public $ticket;
    public $createdBy;

    /**
     * Create a new notification instance.
     */
    public function __construct(Ticket $ticket, User $createdBy = null)
    {
        $this->ticket = $ticket;
        $this->createdBy = $createdBy;
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

        $message = (new MailMessage)
            ->subject("🎫 Novo Ticket #{$this->ticket->ticket_number} criado para você")
            ->greeting("Olá {$notifiable->name}!")
            ->line("Nossa equipe criou um novo ticket especialmente para você.")
            ->line("**Detalhes do Ticket:**")
            ->line("• **Número:** #{$this->ticket->ticket_number}")
            ->line("• **Título:** {$this->ticket->title}")
            ->line("• **Categoria:** {$this->ticket->category->name}")
            ->line("• **Prioridade:** {$priorityEmojiText} {$this->ticket->priority}")
            ->line("• **Status:** {$this->ticket->status}")
            ->line("• **Data de abertura:** " . $this->ticket->created_at->format('d/m/Y H:i'));

        if ($this->createdBy) {
            $message->line("• **Criado por:** {$this->createdBy->name}");
        }

        $message->line("• **Descrição:** " . substr($this->ticket->description, 0, 200) . (strlen($this->ticket->description) > 200 ? '...' : ''))
            ->action('Ver Ticket', route('tickets.show', $this->ticket->ticket_number))
            ->line("Este ticket foi criado especificamente para você. Nossa equipe técnica já está ciente e trabalhará para resolver sua solicitação.")
            ->salutation("Atenciosamente,\nEquipe de Suporte Técnico - 8Bits Pro");

        return $message;
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
            'description' => $this->ticket->description,
            'created_by' => $this->createdBy?->name,
            'created_at' => $this->ticket->created_at,
        ];
    }
}

<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketClosed extends Notification implements ShouldQueue
{
    use Queueable;

    public $ticket;
    public $closedBy;

    /**
     * Create a new notification instance.
     */
    public function __construct(Ticket $ticket, $closedBy = null)
    {
        $this->ticket = $ticket;
        $this->closedBy = $closedBy;
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
        $closedByText = $this->closedBy ? " por {$this->closedBy->name}" : "";
        $resolutionNotes = $this->ticket->resolution_notes ? "\n\n**Notas da Resolução:**\n{$this->ticket->resolution_notes}" : "";

        return (new MailMessage)
            ->subject("✅ Ticket #{$this->ticket->ticket_number} foi fechado")
            ->greeting("Olá {$notifiable->name}!")
            ->line("O ticket que você abriu foi fechado{$closedByText}.")
            ->line("**Detalhes do Ticket:**")
            ->line("• **Número:** #{$this->ticket->ticket_number}")
            ->line("• **Título:** {$this->ticket->title}")
            ->line("• **Cliente:** {$this->ticket->client->company_name}")
            ->line("• **Categoria:** {$this->ticket->category->name}")
            ->line("• **Status:** {$this->ticket->status}")
            ->line("• **Data de fechamento:** " . $this->ticket->closed_at->format('d/m/Y H:i'))
            ->line("• **Tempo total de resolução:** " . $this->getResolutionTime())
            ->line($resolutionNotes)
            ->action('Ver Ticket', url("/tickets/{$this->ticket->id}"))
            ->line("Se você tiver alguma dúvida sobre a resolução, entre em contato conosco.")
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
            'status' => $this->ticket->status,
            'closed_at' => $this->ticket->closed_at,
            'closed_by' => $this->closedBy ? $this->closedBy->name : null,
        ];
    }

    /**
     * Calculate resolution time
     */
    private function getResolutionTime(): string
    {
        if (!$this->ticket->closed_at || !$this->ticket->created_at) {
            return 'N/A';
        }

        $diff = $this->ticket->created_at->diff($this->ticket->closed_at);
        
        if ($diff->days > 0) {
            return $diff->days . ' dia(s) e ' . $diff->h . ' hora(s)';
        } elseif ($diff->h > 0) {
            return $diff->h . ' hora(s) e ' . $diff->i . ' minuto(s)';
        } else {
            return $diff->i . ' minuto(s)';
        }
    }
}

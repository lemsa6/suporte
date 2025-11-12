<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketPriorityChange extends Notification implements ShouldQueue
{
    use Queueable;

    public $ticket;
    public $oldPriority;
    public $newPriority;
    public $changedBy;

    public function __construct(Ticket $ticket, string $oldPriority, string $newPriority, $changedBy = null)
    {
        $this->ticket = $ticket;
        $this->oldPriority = $oldPriority;
        $this->newPriority = $newPriority;
        $this->changedBy = $changedBy;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $priorityEmojis = [
            'baixa' => '🟢',
            'média' => '🟡',
            'alta' => '🔴'
        ];

        $oldPriorityEmoji = $priorityEmojis[$this->oldPriority] ?? '⚪';
        $newPriorityEmoji = $priorityEmojis[$this->newPriority] ?? '⚪';

        $changedByText = $this->changedBy ? " por {$this->changedBy->name}" : "";

        $urgencyMessage = "";
        if ($this->newPriority === 'alta') {
            $urgencyMessage = "\n\n🚨 **ATENÇÃO:** Este ticket agora tem prioridade ALTA e requer atenção imediata!";
        } elseif ($this->oldPriority === 'alta' && $this->newPriority !== 'alta') {
            $urgencyMessage = "\n\n✅ **ALÍVIO:** A prioridade deste ticket foi reduzida.";
        }

        return (new MailMessage)
            ->subject("📊 Prioridade do Ticket #{$this->ticket->ticket_number} alterada")
            ->greeting("Olá {$notifiable->name}!")
            ->line("A prioridade de um ticket foi alterada{$changedByText}.")
            ->line("• **Prioridade Anterior:** {$oldPriorityEmoji} " . ucfirst($this->oldPriority))
            ->line("• **Nova Prioridade:** {$newPriorityEmoji} " . ucfirst($this->newPriority))
            ->line("• **Ticket:** {$this->ticket->title}")
            ->line("• **Cliente:** {$this->ticket->client->company_name}")
            ->line($urgencyMessage)
            ->action('Ver Ticket', route('tickets.show', $this->ticket->ticket_number))
            ->salutation("Atenciosamente,\nEquipe de Suporte Técnico - 8Bits Pro");
    }

    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'old_priority' => $this->oldPriority,
            'new_priority' => $this->newPriority,
            'changed_by' => $this->changedBy?->id,
            'message' => "Prioridade alterada de {$this->oldPriority} para {$this->newPriority}"
        ];
    }
}

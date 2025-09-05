<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClientTicketStatusChangedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

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

    public function build()
    {
        return $this->subject("🔄 Status do Ticket #{$this->ticket->ticket_number} alterado - 8Bits Pro")
                    ->view('emails.tickets.status-changed', [
                        'ticket' => $this->ticket,
                        'oldStatus' => $this->oldStatus,
                        'newStatus' => $this->newStatus,
                        'changedBy' => $this->changedBy
                    ]);
    }
}

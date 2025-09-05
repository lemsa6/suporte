<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClientTicketClosedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $ticket;
    public $closedBy;

    public function __construct(Ticket $ticket, $closedBy = null)
    {
        $this->ticket = $ticket;
        $this->closedBy = $closedBy;
    }

    public function build()
    {
        return $this->subject("✅ Ticket #{$this->ticket->ticket_number} fechado - 8Bits Pro")
                    ->view('emails.tickets.closed', [
                        'ticket' => $this->ticket,
                        'closedBy' => $this->closedBy
                    ]);
    }
}

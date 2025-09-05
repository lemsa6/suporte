<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClientTicketCreatedForYouMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $ticket;
    public $createdBy;

    public function __construct(Ticket $ticket, $createdBy = null)
    {
        $this->ticket = $ticket;
        $this->createdBy = $createdBy;
    }

    public function build()
    {
        return $this->subject("🎫 Novo Ticket #{$this->ticket->ticket_number} criado para você - 8Bits Pro")
                    ->view('emails.tickets.created-for-you', [
                        'ticket' => $this->ticket,
                        'createdBy' => $this->createdBy
                    ]);
    }
}

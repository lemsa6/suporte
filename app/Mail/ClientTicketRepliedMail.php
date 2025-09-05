<?php

namespace App\Mail;

use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClientTicketRepliedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $ticket;
    public $reply;
    public $repliedBy;

    public function __construct(Ticket $ticket, TicketMessage $reply, $repliedBy = null)
    {
        $this->ticket = $ticket;
        $this->reply = $reply;
        $this->repliedBy = $repliedBy;
    }

    public function build()
    {
        return $this->subject("💬 Nova Resposta no Ticket #{$this->ticket->ticket_number} - 8Bits Pro")
                    ->view('emails.tickets.replied', [
                        'ticket' => $this->ticket,
                        'reply' => $this->reply,
                        'repliedBy' => $this->repliedBy
                    ]);
    }
}

<?php

namespace App\Mail;

use App\Models\SupportMessage;
use App\Models\SupportTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SupportTicketCreatedAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public SupportTicket $ticket,
        public SupportMessage $message,
        public string $ticketUrl,
    ) {
        $this->ticket->loadMissing('customer');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Yeni destek talebi: ' . $this->ticket->ticket_no,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.support.ticket-created-admin',
        );
    }
}

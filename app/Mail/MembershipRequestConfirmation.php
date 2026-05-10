<?php

namespace App\Mail;

use App\Models\MembershipRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MembershipRequestConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public MembershipRequest $membership)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmación de solicitud de membresía',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.membership-confirmation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MembershipApprovalNotification extends Mailable
{
    use SerializesModels; // SIN Queueable para evitar serializar token

    public function __construct(
        public User $user,
        #[\SensitiveParameter]
        public string $resetToken
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu solicitud de membresía ha sido aprobada',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.membership-approval',
            with: [
                'resetLink' => route('password.reset', [
                    'token' => $this->resetToken,
                    'email' => $this->user->email,
                ]),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

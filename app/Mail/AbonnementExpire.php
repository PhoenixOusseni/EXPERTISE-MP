<?php

namespace App\Mail;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AbonnementExpire extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public Subscription $subscription) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Votre abonnement E-Formation a expiré');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.apprenant.abonnement_expire');
    }

    public function attachments(): array
    {
        return [];
    }
}

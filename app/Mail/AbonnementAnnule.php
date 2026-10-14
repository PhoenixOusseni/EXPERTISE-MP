<?php

namespace App\Mail;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AbonnementAnnule extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public Subscription $subscription) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Votre demande d\'abonnement n\'a pas pu être validée');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.apprenant.abonnement_annule');
    }

    public function attachments(): array
    {
        return [];
    }
}

<?php

namespace App\Mail;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AbonnementSoumis extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public Subscription $subscription) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Votre demande d\'abonnement a été enregistrée');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.apprenant.abonnement_soumis');
    }

    public function attachments(): array
    {
        return [];
    }
}

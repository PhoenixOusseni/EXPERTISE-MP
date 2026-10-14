<?php

namespace App\Mail\Admin;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NouvelAbonnement extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Subscription $subscription) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: '[Admin] Nouvelle demande d\'abonnement');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.admin.nouvel_abonnement');
    }

    public function attachments(): array
    {
        return [];
    }
}

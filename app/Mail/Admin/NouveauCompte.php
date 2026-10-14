<?php

namespace App\Mail\Admin;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NouveauCompte extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: '[Admin] Nouveau compte apprenant');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.admin.nouveau_compte');
    }

    public function attachments(): array
    {
        return [];
    }
}

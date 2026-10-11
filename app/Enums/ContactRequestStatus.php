<?php

namespace App\Enums;

enum ContactRequestStatus: string
{
    case EnAttente = 'en_attente';
    case EnCours   = 'en_cours';
    case Resolu    = 'resolu';
    case Ferme     = 'ferme';

    public function label(): string
    {
        return match($this) {
            self::EnAttente => 'En attente',
            self::EnCours   => 'En cours',
            self::Resolu    => 'Résolu',
            self::Ferme     => 'Fermé',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::EnAttente => 'badge-yellow',
            self::EnCours   => 'badge-blue',
            self::Resolu    => 'badge-green',
            self::Ferme     => 'badge-gray',
        };
    }
}

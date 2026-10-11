<?php

namespace App\Enums;

enum ContactRequestType: string
{
    case Demande = 'demande';
    case Rdv     = 'rdv';
    case Autre   = 'autre';

    public function label(): string
    {
        return match($this) {
            self::Demande => 'Demande d\'information',
            self::Rdv     => 'Prise de RDV',
            self::Autre   => 'Autre',
        };
    }
}

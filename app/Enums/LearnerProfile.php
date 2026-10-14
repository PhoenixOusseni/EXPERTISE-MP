<?php

namespace App\Enums;

enum LearnerProfile: string
{
    case Entreprise    = 'entreprise';
    case Administration = 'administration';
    case Autre         = 'autre';

    public function label(): string
    {
        return match($this) {
            self::Entreprise    => 'Entreprise',
            self::Administration => 'Administration',
            self::Autre         => 'Autre',
        };
    }
}

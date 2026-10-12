<?php

namespace App\Enums;

enum LearnerProfile: string
{
    case Entreprise    = 'entreprise';
    case Administration = 'administration';

    public function label(): string
    {
        return match($this) {
            self::Entreprise    => 'Entreprise',
            self::Administration => 'Administration',
        };
    }
}

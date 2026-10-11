<?php

namespace App\Enums;

enum VideoSourceType: string
{
    /** Fichier vidéo hébergé sur la plateforme. */
    case Upload = 'upload';
    case Youtube = 'youtube';
    case Vimeo = 'vimeo';
    /** Tout autre lien externe (CDN, Drive, etc.). */
    case External = 'external';
}

<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Taille maximale d'une vidéo uploadée (en kilo-octets)
    |--------------------------------------------------------------------------
    |
    | Penser à ajuster aussi upload_max_filesize et post_max_size dans php.ini.
    |
    */

    'max_video_size_kb' => (int) env('FORMATION_MAX_VIDEO_SIZE_KB', 2 * 1024 * 1024), // 2 Go

    /*
    |--------------------------------------------------------------------------
    | Compte administrateur créé par le seeder
    |--------------------------------------------------------------------------
    */

    'admin' => [
        'name' => env('FORMATION_ADMIN_NAME', 'Administrateur'),
        'email' => env('FORMATION_ADMIN_EMAIL', 'admin@e-formation.test'),
        'password' => env('FORMATION_ADMIN_PASSWORD', 'password'),
    ],

];

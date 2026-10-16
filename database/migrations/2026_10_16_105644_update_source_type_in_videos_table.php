<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Toute valeur qui n'est ni 'facebook' ni 'tiktok' devient 'facebook' par défaut.
        DB::table('videos')
            ->whereNotIn('source_type', ['facebook', 'tiktok'])
            ->update(['source_type' => 'facebook']);
    }

    public function down(): void
    {
        // Rien à inverser : les anciennes valeurs sont inconnues.
    }
};

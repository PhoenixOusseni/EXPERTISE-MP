<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => config('formation.admin.email')],
            [
                'name' => config('formation.admin.name'),
                'password' => config('formation.admin.password'),
                'role' => UserRole::Admin,
                'email_verified_at' => now(),
            ],
        );

        $plans = [
            ['name' => 'Mensuel', 'price' => 5000, 'duration_days' => 30, 'features' => ['Accès à toutes les vidéos pendant 1 mois']],
            ['name' => 'Trimestriel', 'price' => 13500, 'duration_days' => 90, 'features' => ['Accès à toutes les vidéos pendant 3 mois', '10 % d\'économie']],
            ['name' => 'Annuel', 'price' => 48000, 'duration_days' => 365, 'features' => ['Accès à toutes les vidéos pendant 1 an', '20 % d\'économie']],
        ];

        foreach ($plans as $plan) {
            Plan::firstOrCreate(['name' => $plan['name']], $plan);
        }

        foreach (['Développement web', 'Bureautique', 'Marketing digital', 'Design graphique'] as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}

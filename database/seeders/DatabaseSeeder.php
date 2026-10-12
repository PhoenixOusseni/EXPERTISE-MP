<?php

namespace Database\Seeders;

use App\Enums\LearnerProfile;
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

        // 1 USD = 655,957 FCFA (taux officiel)
        $plans = [
            // ── Contenu Entreprise ──
            [
                'name' => 'Mensuel Entreprise', 'profile_type' => LearnerProfile::Entreprise,
                'price' => 15.00, 'currency' => 'USD', 'duration_days' => 30,
                'features' => ['Accès aux vidéos Entreprise pendant 1 mois'],
            ],
            [
                'name' => 'Trimestriel Entreprise', 'profile_type' => LearnerProfile::Entreprise,
                'price' => 41.00, 'currency' => 'USD', 'duration_days' => 90,
                'features' => ['Accès aux vidéos Entreprise pendant 3 mois', '10 % d\'économie'],
            ],
            [
                'name' => 'Semestriel Entreprise', 'profile_type' => LearnerProfile::Entreprise,
                'price' => 80.00, 'currency' => 'USD', 'duration_days' => 180,
                'features' => ['Accès aux vidéos Entreprise pendant 6 mois', '12 % d\'économie'],
            ],
            [
                'name' => 'Annuel Entreprise', 'profile_type' => LearnerProfile::Entreprise,
                'price' => 156.00, 'currency' => 'USD', 'duration_days' => 365,
                'features' => ['Accès aux vidéos Entreprise pendant 1 an', '15 % d\'économie'],
            ],
            // ── Contenu Administration ──
            [
                'name' => 'Mensuel Administration', 'profile_type' => LearnerProfile::Administration,
                'price' => 8.00, 'currency' => 'USD', 'duration_days' => 30,
                'features' => ['Accès aux vidéos Administration pendant 1 mois'],
            ],
            [
                'name' => 'Trimestriel Administration', 'profile_type' => LearnerProfile::Administration,
                'price' => 21.00, 'currency' => 'USD', 'duration_days' => 90,
                'features' => ['Accès aux vidéos Administration pendant 3 mois', '10 % d\'économie'],
            ],
            [
                'name' => 'Semestriel Administration', 'profile_type' => LearnerProfile::Administration,
                'price' => 40.00, 'currency' => 'USD', 'duration_days' => 180,
                'features' => ['Accès aux vidéos Administration pendant 6 mois', '12 % d\'économie'],
            ],
            [
                'name' => 'Annuel Administration', 'profile_type' => LearnerProfile::Administration,
                'price' => 78.00, 'currency' => 'USD', 'duration_days' => 365,
                'features' => ['Accès aux vidéos Administration pendant 1 an', '15 % d\'économie'],
            ],
        ];

        foreach ($plans as $plan) {
            Plan::firstOrCreate(['name' => $plan['name']], $plan);
        }

        foreach (['Développement web', 'Bureautique', 'Marketing digital', 'Design graphique'] as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}

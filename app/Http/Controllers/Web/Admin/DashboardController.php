<?php

namespace App\Http\Controllers\Web\Admin;

use App\Enums\LearnerProfile;
use App\Enums\SubscriptionStatus;
use App\Http\Controllers\Controller;
use App\Models\Publication;
use App\Models\Publicite;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Video;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = [
            'apprenants' => [
                'total' => User::apprenants()->count(),
                'abonnes' => User::apprenants()->withActiveSubscription()->count(),
            ],
            'videos' => [
                'total'   => Video::count(),
                'publiees' => Video::published()->count(),
                'vues'    => (int) Video::sum('views_count'),
            ],
            'videos_profil' => [
                'entreprise' => [
                    'total'        => Video::where('audience', LearnerProfile::Entreprise)->count(),
                    'publiees'     => Video::published()->where('audience', LearnerProfile::Entreprise)->count(),
                    'vues'         => (int) Video::where('audience', LearnerProfile::Entreprise)->sum('views_count'),
                    'revenus_mois' => (float) Subscription::join('plans', 'subscriptions.plan_id', '=', 'plans.id')
                        ->whereIn('subscriptions.status', [SubscriptionStatus::Active, SubscriptionStatus::Expired])
                        ->where('subscriptions.starts_at', '>=', now()->startOfMonth())
                        ->where('plans.profile_type', LearnerProfile::Entreprise->value)
                        ->sum('subscriptions.amount'),
                    'revenus_total' => (float) Subscription::join('plans', 'subscriptions.plan_id', '=', 'plans.id')
                        ->whereIn('subscriptions.status', [SubscriptionStatus::Active, SubscriptionStatus::Expired])
                        ->where('plans.profile_type', LearnerProfile::Entreprise->value)
                        ->sum('subscriptions.amount'),
                ],
                'administration' => [
                    'total'        => Video::where('audience', LearnerProfile::Administration)->count(),
                    'publiees'     => Video::published()->where('audience', LearnerProfile::Administration)->count(),
                    'vues'         => (int) Video::where('audience', LearnerProfile::Administration)->sum('views_count'),
                    'revenus_mois' => (float) Subscription::join('plans', 'subscriptions.plan_id', '=', 'plans.id')
                        ->whereIn('subscriptions.status', [SubscriptionStatus::Active, SubscriptionStatus::Expired])
                        ->where('subscriptions.starts_at', '>=', now()->startOfMonth())
                        ->where('plans.profile_type', LearnerProfile::Administration->value)
                        ->sum('subscriptions.amount'),
                    'revenus_total' => (float) Subscription::join('plans', 'subscriptions.plan_id', '=', 'plans.id')
                        ->whereIn('subscriptions.status', [SubscriptionStatus::Active, SubscriptionStatus::Expired])
                        ->where('plans.profile_type', LearnerProfile::Administration->value)
                        ->sum('subscriptions.amount'),
                ],
                'autre' => [
                    'total'   => Video::where('audience', LearnerProfile::Autre)->count(),
                    'publiees' => Video::published()->where('audience', LearnerProfile::Autre)->count(),
                    'vues'    => (int) Video::where('audience', LearnerProfile::Autre)->sum('views_count'),
                ],
            ],
            'abonnements' => [
                'actifs' => Subscription::currentlyActive()->count(),
                'en_attente' => Subscription::where('status', SubscriptionStatus::Pending)->count(),
                'expirent_sous_7_jours' => Subscription::currentlyActive()
                    ->where('ends_at', '<=', now()->addDays(7))
                    ->count(),
                'revenus_total' => (float) Subscription::whereIn('status', [
                    SubscriptionStatus::Active, SubscriptionStatus::Expired,
                ])->sum('amount'),
                'revenus_mois' => (float) Subscription::whereIn('status', [
                    SubscriptionStatus::Active, SubscriptionStatus::Expired,
                ])->where('starts_at', '>=', now()->startOfMonth())->sum('amount'),
            ],
            'publications' => [
                'total'     => Publication::count(),
                'publiees'  => Publication::published()->count(),
                'vues'      => (int) Publication::sum('views_count'),
                'moy_vues'  => Publication::published()->count() > 0
                    ? round(Publication::published()->avg('views_count'), 1)
                    : 0,
            ],
            'publicites' => [
                'total'   => Publicite::count(),
                'actives' => Publicite::active()->count(),
                'expirees' => Publicite::where('end_date', '<', now()->toDateString())->count(),
            ],
        ];

        $recentSubscriptions = Subscription::with(['user', 'plan'])->latest()->limit(8)->get();

        return view('admin.dashboard', compact('stats', 'recentSubscriptions'));
    }
}

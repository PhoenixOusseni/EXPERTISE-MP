<?php

namespace App\Http\Controllers\Web\Admin;

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
                'total' => Video::count(),
                'publiees' => Video::published()->count(),
                'vues' => (int) Video::sum('views_count'),
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

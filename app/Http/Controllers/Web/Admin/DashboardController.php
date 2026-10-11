<?php

namespace App\Http\Controllers\Web\Admin;

use App\Enums\SubscriptionStatus;
use App\Http\Controllers\Controller;
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
        ];

        $recentSubscriptions = Subscription::with(['user', 'plan'])->latest()->limit(8)->get();

        return view('admin.dashboard', compact('stats', 'recentSubscriptions'));
    }
}

<?php

namespace App\Http\Controllers\Web\Apprenant;

use App\Enums\SubscriptionStatus;
use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AbonnementController extends Controller
{
    public function plans(Request $request): View
    {
        $plans = Plan::active()->orderBy('price')->get();
        $user = $request->user();
        $activeSubscription = $user->activeSubscription()->with('plan')->first();
        $pendingSubscription = $user->subscriptions()->where('status', SubscriptionStatus::Pending)->first();

        return view('apprenant.formules', compact('plans', 'activeSubscription', 'pendingSubscription'));
    }

    public function index(Request $request): View
    {
        $user = $request->user();
        $activeSubscription = $user->activeSubscription()->with('plan')->first();
        $subscriptions = $user->subscriptions()->with('plan')->latest()->get();

        return view('apprenant.abonnement', compact('activeSubscription', 'subscriptions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'plan_id' => ['required', 'integer', 'exists:plans,id'],
            'payment_method' => ['required', 'string', 'max:50'],
            'payment_reference' => ['nullable', 'string', 'max:255'],
        ]);

        $user = $request->user();

        if ($user->hasActiveSubscription()) {
            return redirect()->route('mon-abonnement')
                ->with('warning', 'Vous possédez déjà un abonnement actif.');
        }

        $pending = $user->subscriptions()->where('status', SubscriptionStatus::Pending)->first();
        if ($pending) {
            return back()->with('warning', 'Vous avez déjà une demande en attente de validation.');
        }

        $plan = Plan::active()->findOrFail($data['plan_id']);

        $user->subscriptions()->create([
            'plan_id' => $plan->id,
            'status' => SubscriptionStatus::Pending,
            'amount' => $plan->price,
            'currency' => $plan->currency,
            'payment_method' => $data['payment_method'],
            'payment_reference' => $data['payment_reference'] ?? null,
        ]);

        return redirect()->route('mon-abonnement')
            ->with('success', 'Votre demande d\'abonnement a été enregistrée. Elle sera activée après vérification du paiement.');
    }

    public function cancel(Request $request, Subscription $subscription): RedirectResponse
    {
        abort_unless($subscription->user()->is($request->user()), 404);

        if ($subscription->status !== SubscriptionStatus::Pending) {
            return back()->with('error', 'Seule une demande en attente peut être annulée.');
        }

        $subscription->cancel();

        return back()->with('success', 'Votre demande d\'abonnement a été annulée.');
    }
}

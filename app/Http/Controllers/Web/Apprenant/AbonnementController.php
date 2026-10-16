<?php

namespace App\Http\Controllers\Web\Apprenant;

use App\Enums\LearnerProfile;
use App\Enums\SubscriptionStatus;
use App\Http\Controllers\Controller;
use App\Mail\AbonnementSoumis;
use App\Mail\Admin\NouvelAbonnement;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AbonnementController extends Controller
{
    public function checkout(Request $request, Plan $plan): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->hasActiveSubscription()) {
            return redirect()->route('mon-abonnement')
                ->with('warning', 'Vous avez déjà un abonnement actif.');
        }

        if ($user->subscriptions()->where('status', SubscriptionStatus::Pending)->exists()) {
            return redirect()->route('formules')
                ->with('warning', 'Vous avez déjà une demande en attente de validation.');
        }

        abort_unless($plan->is_active, 404);

        return view('apprenant.souscrire', compact('plan'));
    }

    public function plans(Request $request): View
    {
        $user    = $request->user();
        $profile = $user->profile_type instanceof LearnerProfile ? $user->profile_type : null;

        // Le profil 'Autre' peut choisir parmi tous les plans (Entreprise + Administration).
        $isAutre = $profile === LearnerProfile::Autre;

        $plans = Plan::active()
            ->when(! $isAutre && $profile, fn ($q) => $q->forProfile($profile))
            ->orderBy('profile_type')
            ->orderBy('price')
            ->get();

        $activeSubscription  = $user->activeSubscription()->with('plan')->first();
        $pendingSubscription = $user->subscriptions()->where('status', SubscriptionStatus::Pending)->first();

        return view('apprenant.formules', compact('plans', 'activeSubscription', 'pendingSubscription', 'isAutre'));
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
            'plan_id'  => ['required', 'integer', 'exists:plans,id'],
            'operator' => ['required', 'string', 'in:orange_money,moov_money,telecel_money,sank_money,coris_money,paypal'],
            'phone'    => ['required', 'string', 'max:20'],
            'otp'      => ['required', 'string', 'max:10'],
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

        $subscription = $user->subscriptions()->create([
            'plan_id'           => $plan->id,
            'status'            => SubscriptionStatus::Pending,
            'amount'            => $plan->price,
            'currency'          => $plan->currency,
            'payment_method'    => $data['operator'],
            'payment_reference' => '+226 ' . $data['phone'] . ' | OTP: ' . $data['otp'],
        ]);

        try {
            $subscription->load('plan');
            Mail::to($user->email)->send(new AbonnementSoumis($user, $subscription));
            Mail::to(config('formation.admin.email'))->send(new NouvelAbonnement($subscription));
        } catch (\Exception) {
            // L'envoi d'email ne bloque pas la demande
        }

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

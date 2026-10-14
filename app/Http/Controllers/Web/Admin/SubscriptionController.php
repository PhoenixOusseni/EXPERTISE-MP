<?php

namespace App\Http\Controllers\Web\Admin;

use App\Enums\SubscriptionStatus;
use App\Http\Controllers\Controller;
use App\Mail\AbonnementActive;
use App\Mail\AbonnementAnnule;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function index(Request $request): View
    {
        $subscriptions = Subscription::query()
            ->with(['user', 'plan'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('plan_id'), fn ($q) => $q->where('plan_id', $request->plan_id))
            ->when($request->filled('search'), fn ($q) => $q->whereHas('user', fn ($u) => $u->where(fn ($u) => $u
                ->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('email', 'like', '%'.$request->search.'%'))))
            ->latest()
            ->paginate(20);

        $plans = Plan::orderBy('name')->get();

        return view('admin.subscriptions.index', compact('subscriptions', 'plans'));
    }

    public function show(Subscription $subscription): View
    {
        $subscription->load(['user', 'plan']);

        return view('admin.subscriptions.show', compact('subscription'));
    }

    public function create(Request $request): View
    {
        $plans = Plan::active()->orderBy('price')->get();
        $users = User::apprenants()->orderBy('name')->get();

        return view('admin.subscriptions.create', compact('plans', 'users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'plan_id' => ['required', 'integer', 'exists:plans,id'],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'payment_reference' => ['nullable', 'string', 'max:255'],
            'starts_at' => ['nullable', 'date'],
            'activate' => ['sometimes', 'boolean'],
        ]);

        $plan = Plan::findOrFail($data['plan_id']);

        $subscription = Subscription::create([
            'user_id' => $data['user_id'],
            'plan_id' => $plan->id,
            'status' => SubscriptionStatus::Pending,
            'amount' => $plan->price,
            'currency' => $plan->currency,
            'payment_method' => $data['payment_method'] ?? null,
            'payment_reference' => $data['payment_reference'] ?? null,
        ]);

        if ($request->boolean('activate', true)) {
            $subscription->activate(isset($data['starts_at']) ? Carbon::parse($data['starts_at']) : null);
        }

        return redirect()->route('admin.subscriptions.show', $subscription)
            ->with('success', 'Abonnement créé avec succès.');
    }

    public function activate(Subscription $subscription): RedirectResponse
    {
        if ($subscription->isCurrentlyActive()) {
            return back()->with('warning', 'Cet abonnement est déjà actif.');
        }

        $subscription->activate();
        $subscription->load(['user', 'plan']);

        try {
            Mail::to($subscription->user->email)->send(new AbonnementActive($subscription->user, $subscription));
        } catch (\Exception) {}

        return back()->with('success', 'Abonnement activé.');
    }

    public function cancel(Subscription $subscription): RedirectResponse
    {
        $subscription->load(['user', 'plan']);
        $subscription->cancel();

        try {
            Mail::to($subscription->user->email)->send(new AbonnementAnnule($subscription->user, $subscription));
        } catch (\Exception) {}

        return back()->with('success', 'Abonnement annulé.');
    }

    public function extend(Request $request, Subscription $subscription): RedirectResponse
    {
        $data = $request->validate(['days' => ['required', 'integer', 'min:1']]);

        $base = $subscription->ends_at && $subscription->ends_at->isFuture() ? $subscription->ends_at : now();
        $subscription->update([
            'status' => SubscriptionStatus::Active,
            'ends_at' => $base->copy()->addDays($data['days']),
        ]);

        return back()->with('success', "Abonnement prolongé de {$data['days']} jour(s).");
    }

    public function destroy(Subscription $subscription): RedirectResponse
    {
        $subscription->delete();

        return redirect()->route('admin.subscriptions.index')->with('success', 'Abonnement supprimé.');
    }
}

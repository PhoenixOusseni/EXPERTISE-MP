<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SubscriptionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSubscriptionRequest;
use App\Http\Resources\SubscriptionResource;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class SubscriptionController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $request->validate([
            'status' => ['nullable', Rule::enum(SubscriptionStatus::class)],
        ]);

        $subscriptions = Subscription::query()
            ->with(['user', 'plan'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('user_id'), fn ($q) => $q->where('user_id', $request->user_id))
            ->when($request->filled('plan_id'), fn ($q) => $q->where('plan_id', $request->plan_id))
            ->when($request->filled('search'), fn ($q) => $q->whereHas('user', fn ($u) => $u->where(fn ($u) => $u
                ->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('email', 'like', '%'.$request->search.'%'))))
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return SubscriptionResource::collection($subscriptions);
    }

    /**
     * Création manuelle d'un abonnement (ex. paiement en espèces / mobile money reçu hors ligne).
     */
    public function store(StoreSubscriptionRequest $request): JsonResponse
    {
        $plan = Plan::findOrFail($request->plan_id);

        $subscription = Subscription::create([
            'user_id' => $request->user_id,
            'plan_id' => $plan->id,
            'status' => SubscriptionStatus::Pending,
            'amount' => $request->input('amount', $plan->price),
            'currency' => $plan->currency,
            'payment_method' => $request->payment_method,
            'payment_reference' => $request->payment_reference,
        ]);

        if ($request->boolean('activate', true)) {
            $subscription->activate($request->filled('starts_at') ? Carbon::parse($request->starts_at) : null);
        }

        return (new SubscriptionResource($subscription->load(['user', 'plan'])))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Subscription $subscription): SubscriptionResource
    {
        return new SubscriptionResource($subscription->load(['user', 'plan']));
    }

    /**
     * Valide un abonnement en attente (après vérification du paiement).
     */
    public function activate(Request $request, Subscription $subscription): SubscriptionResource|JsonResponse
    {
        $data = $request->validate([
            'starts_at' => ['nullable', 'date'],
            'payment_reference' => ['nullable', 'string', 'max:255'],
        ]);

        if ($subscription->isCurrentlyActive()) {
            return response()->json(['message' => 'Cet abonnement est déjà actif.'], 422);
        }

        if (isset($data['payment_reference'])) {
            $subscription->payment_reference = $data['payment_reference'];
        }

        $subscription->activate(isset($data['starts_at']) ? Carbon::parse($data['starts_at']) : null);

        return new SubscriptionResource($subscription->load(['user', 'plan']));
    }

    public function cancel(Subscription $subscription): SubscriptionResource
    {
        $subscription->cancel();

        return new SubscriptionResource($subscription->load(['user', 'plan']));
    }

    /**
     * Prolonge un abonnement d'un nombre de jours donné.
     */
    public function extend(Request $request, Subscription $subscription): SubscriptionResource|JsonResponse
    {
        $data = $request->validate(['days' => ['required', 'integer', 'min:1']]);

        if ($subscription->status !== SubscriptionStatus::Active && $subscription->status !== SubscriptionStatus::Expired) {
            return response()->json(['message' => 'Seul un abonnement actif ou expiré peut être prolongé.'], 422);
        }

        $base = $subscription->ends_at && $subscription->ends_at->isFuture() ? $subscription->ends_at : now();

        $subscription->update([
            'status' => SubscriptionStatus::Active,
            'ends_at' => $base->copy()->addDays($data['days']),
        ]);

        return new SubscriptionResource($subscription->load(['user', 'plan']));
    }

    public function destroy(Subscription $subscription): JsonResponse
    {
        $subscription->delete();

        return response()->json(null, 204);
    }
}

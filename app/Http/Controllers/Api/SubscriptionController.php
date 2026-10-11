<?php

namespace App\Http\Controllers\Api;

use App\Enums\SubscriptionStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionResource;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Abonnements côté apprenant.
 *
 * Flux : l'apprenant choisit une formule et renseigne son paiement -> abonnement "pending"
 * -> l'administrateur vérifie le paiement et l'active (Admin\SubscriptionController@activate).
 * Pour un paiement en ligne automatique, appeler $subscription->activate() depuis le webhook du prestataire.
 */
class SubscriptionController extends Controller
{
    public function plans(): JsonResponse
    {
        return response()->json(Plan::active()->orderBy('price')->get());
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        return SubscriptionResource::collection(
            $request->user()->subscriptions()->with('plan')->latest()->get()
        );
    }

    public function current(Request $request): JsonResponse
    {
        $subscription = $request->user()->activeSubscription()->with('plan')->first();

        return response()->json([
            'has_active_subscription' => $subscription !== null,
            'subscription' => $subscription ? new SubscriptionResource($subscription) : null,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'plan_id' => ['required', 'integer', 'exists:plans,id'],
            'payment_method' => ['required', 'string', 'max:50'],
            'payment_reference' => ['nullable', 'string', 'max:255'],
        ]);

        $user = $request->user();
        $plan = Plan::active()->find($data['plan_id']);

        if (! $plan) {
            return response()->json(['message' => 'Cette formule n\'est plus disponible.'], 422);
        }

        $pending = $user->subscriptions()->where('status', SubscriptionStatus::Pending)->first();

        if ($pending) {
            return response()->json([
                'message' => 'Vous avez déjà une demande d\'abonnement en attente de validation.',
                'subscription' => new SubscriptionResource($pending->load('plan')),
            ], 422);
        }

        $subscription = $user->subscriptions()->create([
            'plan_id' => $plan->id,
            'status' => SubscriptionStatus::Pending,
            'amount' => $plan->price,
            'currency' => $plan->currency,
            'payment_method' => $data['payment_method'],
            'payment_reference' => $data['payment_reference'] ?? null,
        ]);

        return response()->json([
            'message' => 'Votre demande d\'abonnement a été enregistrée. Elle sera activée après validation du paiement.',
            'subscription' => new SubscriptionResource($subscription->load('plan')),
        ], 201);
    }

    /**
     * L'apprenant peut annuler une demande encore en attente.
     */
    public function cancel(Request $request, Subscription $subscription): JsonResponse
    {
        abort_unless($subscription->user()->is($request->user()), 404);

        if ($subscription->status !== SubscriptionStatus::Pending) {
            return response()->json(['message' => 'Seule une demande en attente peut être annulée.'], 422);
        }

        $subscription->cancel();

        return response()->json(new SubscriptionResource($subscription->load('plan')));
    }
}

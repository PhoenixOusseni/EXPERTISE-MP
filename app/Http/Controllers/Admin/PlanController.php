<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PlanRequest;
use App\Models\Plan;
use Illuminate\Http\JsonResponse;

class PlanController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Plan::withCount([
                'subscriptions',
                'subscriptions as active_subscriptions_count' => fn ($q) => $q->currentlyActive(),
            ])->orderBy('price')->get()
        );
    }

    public function store(PlanRequest $request): JsonResponse
    {
        return response()->json(Plan::create($request->validated()), 201);
    }

    public function show(Plan $plan): JsonResponse
    {
        return response()->json($plan->loadCount('subscriptions'));
    }

    public function update(PlanRequest $request, Plan $plan): JsonResponse
    {
        $plan->update($request->validated());

        return response()->json($plan);
    }

    /**
     * Une formule ayant déjà des abonnements est désactivée plutôt que supprimée,
     * afin de conserver l'historique.
     */
    public function destroy(Plan $plan): JsonResponse
    {
        if ($plan->subscriptions()->exists()) {
            $plan->update(['is_active' => false]);

            return response()->json([
                'message' => 'Cette formule possède des abonnements : elle a été désactivée au lieu d\'être supprimée.',
                'plan' => $plan,
            ]);
        }

        $plan->delete();

        return response()->json(null, 204);
    }
}

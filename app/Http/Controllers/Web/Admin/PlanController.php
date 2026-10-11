<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PlanController extends Controller
{
    public function index(): View
    {
        $plans = Plan::withCount('subscriptions')->orderBy('price')->get();

        return view('admin.plans.index', compact('plans'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validate($request);
        Plan::create($data);

        return back()->with('success', 'Formule "' . $data['name'] . '" créée.');
    }

    public function edit(Plan $plan): View
    {
        $plans = Plan::withCount('subscriptions')->orderBy('price')->get();

        return view('admin.plans.index', [
            'plans' => $plans,
            'editPlan' => $plan,
        ]);
    }

    public function update(Request $request, Plan $plan): RedirectResponse
    {
        $data = $this->validate($request, $plan);
        $plan->update($data);

        return redirect()->route('admin.plans.index')->with('success', 'Formule mise à jour.');
    }

    public function destroy(Plan $plan): RedirectResponse
    {
        if ($plan->subscriptions()->exists()) {
            $plan->update(['is_active' => false]);

            return back()->with('warning', 'Cette formule possède des abonnements : elle a été désactivée.');
        }

        $plan->delete();

        return back()->with('success', 'Formule supprimée.');
    }

    protected function validate(Request $request, ?Plan $plan = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'currency' => ['sometimes', 'string', 'max:10'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'features' => ['nullable', 'array'],
            'features.*' => ['string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', Rule::unique('plans')->ignore($plan)],
        ]);
    }
}

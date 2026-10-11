<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::apprenants()
            ->with('activeSubscription.plan')
            ->when($request->filled('search'), fn ($q) => $q->where(fn ($q) => $q
                ->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('email', 'like', '%'.$request->search.'%')))
            ->when($request->boolean('subscribed'), fn ($q) => $q->withActiveSubscription())
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user): View
    {
        $subscriptions = $user->subscriptions()->with('plan')->latest()->get();

        return view('admin.users.show', compact('user', 'subscriptions'));
    }

    /**
     * Toggle user active status.
     */
    public function toggle(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'Vous ne pouvez pas modifier votre propre statut.');
        }

        $user->update(['is_active' => ! $user->is_active]);

        if (! $user->is_active) {
            // Revoke all tokens and sessions on deactivation.
            $user->tokens()->delete();
        }

        return back()->with('success', 'Compte ' . ($user->is_active ? 'activé' : 'désactivé') . '.');
    }
}

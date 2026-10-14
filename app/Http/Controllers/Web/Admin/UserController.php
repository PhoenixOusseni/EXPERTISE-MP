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
            ->when($request->filled('search'), fn ($q) => $q->where(fn ($u) => $u
                ->where('name',  'like', '%'.$request->search.'%')
                ->orWhere('email', 'like', '%'.$request->search.'%')
                ->orWhere('phone', 'like', '%'.$request->search.'%')))
            ->when($request->filled('subscribed'), function ($q) use ($request) {
                $request->subscribed === '1'
                    ? $q->withActiveSubscription()
                    : $q->whereDoesntHave('subscriptions', fn ($s) => $s->currentlyActive());
            })
            ->when($request->filled('gender'),       fn ($q) => $q->where('gender',       $request->gender))
            ->when($request->filled('profile_type'), fn ($q) => $q->where('profile_type', $request->profile_type))
            ->when($request->filled('continent'),    fn ($q) => $q->where('continent',    $request->continent))
            ->when($request->filled('country'),      fn ($q) => $q->where('country',      $request->country))
            ->when($request->filled('city'),         fn ($q) => $q->where('city',         $request->city))
            ->latest()
            ->paginate(20);

        $continents = User::apprenants()->whereNotNull('continent')->distinct()->orderBy('continent')->pluck('continent');
        $countries  = User::apprenants()->whereNotNull('country')->distinct()->orderBy('country')->pluck('country');
        $cities     = User::apprenants()->whereNotNull('city')->distinct()->orderBy('city')->pluck('city');

        return view('admin.users.index', compact('users', 'continents', 'countries', 'cities'));
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

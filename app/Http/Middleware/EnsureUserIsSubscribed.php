<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsSubscribed
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user?->isAdmin() && ! $user?->hasActiveSubscription()) {
            abort(402, 'Un abonnement actif est requis pour accéder à ce contenu.');
        }

        return $next($request);
    }
}

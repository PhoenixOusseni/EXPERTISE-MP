@extends('layouts.app')
@section('title', 'Formules d\'abonnement')

@section('content')
<div class="max-w-5xl mx-auto">

    <!-- Hero banner -->
    <div class="mb-10 bg-gradient-to-r from-blue-900 to-indigo-800 rounded-2xl p-8 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                <defs><pattern id="dots" width="25" height="25" patternUnits="userSpaceOnUse"><circle cx="12" cy="12" r="1.5" fill="white"/></pattern></defs>
                <rect width="100%" height="100%" fill="url(#dots)" />
            </svg>
        </div>
        <div class="relative text-center">
            <span class="inline-flex items-center gap-2 bg-white/10 border border-white/20 text-blue-200 text-sm font-medium px-4 py-1.5 rounded-full mb-4">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                Accès illimité à tout le catalogue
            </span>
            <h1 class="text-3xl sm:text-4xl font-bold text-white mb-3">Formules d'abonnement</h1>
            <p class="text-blue-200 max-w-xl mx-auto">Choisissez la formule qui vous convient et accédez à toutes nos formations en marchés publics sans restriction.</p>
            <div class="flex flex-wrap justify-center gap-4 mt-6">
                @foreach(['Vidéos HD illimitées', 'Accès mobile & desktop', 'Nouveaux contenus chaque mois', 'Support dédié'] as $feat)
                <div class="flex items-center gap-1.5 bg-white/10 text-blue-100 text-xs font-medium px-3 py-1.5 rounded-full">
                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    {{ $feat }}
                </div>
                @endforeach
            </div>
        </div>
    </div>

    @if($activeSubscription)
    <div class="mb-8 bg-emerald-50 border border-emerald-200 rounded-2xl p-5 flex items-center gap-4">
        <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="font-semibold text-emerald-800">Abonnement actif — {{ $activeSubscription->plan->name }}</p>
            <p class="text-sm text-emerald-700">Expire le {{ $activeSubscription->ends_at->format('d/m/Y') }} ({{ $activeSubscription->daysRemaining() }} jours restants)</p>
        </div>
        <a href="{{ route('mon-abonnement') }}" class="ml-auto text-sm font-medium text-emerald-700 hover:text-emerald-900 transition">
            Voir mon abonnement →
        </a>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($plans as $plan)
        <div class="bg-white rounded-2xl border-2 {{ $loop->iteration === 2 ? 'border-indigo-600 shadow-xl shadow-indigo-100' : 'border-gray-200' }} p-7 relative flex flex-col">
            @if($loop->iteration === 2)
                <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-indigo-600 text-white text-xs font-bold px-4 py-1 rounded-full">Le plus populaire</div>
            @endif
            <h3 class="text-lg font-bold text-gray-900">{{ $plan->name }}</h3>
            @if($plan->description)
            <p class="text-sm text-gray-500 mt-1">{{ $plan->description }}</p>
            @endif
            <div class="mt-4 mb-6">
                <span class="text-4xl font-extrabold text-gray-900">{{ number_format($plan->price, 0, '.', ' ') }}</span>
                <span class="text-gray-500 ml-1 text-sm">{{ $plan->currency }}</span>
                @if ($plan->price_xof)
                    <span class="text-gray-400 text-xs block mt-0.5">≈ {{ number_format($plan->price_xof, 0, '.', ' ') }} XOF</span>
                @endif
                <span class="text-gray-400 text-xs block mt-0.5">pour {{ $plan->duration_days }} jours</span>
            </div>
            @if($plan->features)
            <ul class="space-y-2.5 flex-1 mb-7">
                @foreach($plan->features as $feature)
                <li class="flex items-start gap-2 text-sm text-gray-600">
                    <svg class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ $feature }}
                </li>
                @endforeach
            </ul>
            @endif

            @if($pendingSubscription)
                <button disabled class="btn btn-secondary w-full justify-center opacity-60 cursor-not-allowed">
                    Demande en attente
                </button>
            @elseif($activeSubscription)
                <button disabled class="btn btn-success w-full justify-center opacity-60 cursor-not-allowed">
                    ✓ Abonnement actif
                </button>
            @else
                <a href="{{ route('subscriptions.checkout', $plan) }}"
                   class="{{ $loop->iteration === 2 ? 'btn-primary' : 'btn-secondary' }} btn w-full justify-center">
                    Souscrire
                </a>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endsection

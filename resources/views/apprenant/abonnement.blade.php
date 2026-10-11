@extends('layouts.app')
@section('title', 'Mon abonnement')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Page header -->
    <div class="mb-8 flex items-center gap-4">
        <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Mon abonnement</h1>
            <p class="text-gray-500 text-sm mt-0.5">Gérez votre accès aux formations Expertise Marchés Publics</p>
        </div>
    </div>

    <!-- Current status card -->
    @if($activeSubscription)
    <div class="card mb-6">
        <div class="p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <span class="badge-green mb-2">Actif</span>
                    <h2 class="text-xl font-bold text-gray-900 mt-1">{{ $activeSubscription->plan->name }}</h2>
                    <p class="text-gray-500 text-sm mt-0.5">
                        Du {{ $activeSubscription->starts_at->format('d/m/Y') }} au {{ $activeSubscription->ends_at->format('d/m/Y') }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-bold text-gray-900">{{ $activeSubscription->daysRemaining() }}</p>
                    <p class="text-sm text-gray-500">jours restants</p>
                </div>
            </div>
            <!-- Progress bar -->
            <div class="mt-5">
                @php
                    $totalDays = $activeSubscription->plan->duration_days;
                    $remaining = $activeSubscription->daysRemaining();
                    $elapsed = max(0, $totalDays - $remaining);
                    $percent = $totalDays > 0 ? round(($elapsed / $totalDays) * 100) : 0;
                @endphp
                <div class="flex items-center justify-between text-xs text-gray-500 mb-1.5">
                    <span>{{ $elapsed }} jour{{ $elapsed > 1 ? 's' : '' }} utilisé{{ $elapsed > 1 ? 's' : '' }}</span>
                    <span>{{ $remaining }} restant{{ $remaining > 1 ? 's' : '' }}</span>
                </div>
                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-indigo-600 rounded-full transition-all" style="width: {{ $percent }}%"></div>
                </div>
            </div>
        </div>
        <div class="px-6 py-4 bg-gray-50 rounded-b-xl flex items-center justify-between">
            <p class="text-sm text-gray-600">
                Montant payé : <span class="font-semibold text-gray-900">{{ number_format($activeSubscription->amount, 0, '.', ' ') }} {{ $activeSubscription->currency }}</span>
                @if($activeSubscription->payment_method)
                    via {{ ucfirst(str_replace('_', ' ', $activeSubscription->payment_method)) }}
                @endif
            </p>
            <a href="{{ route('formules') }}" class="btn-primary btn-sm">Renouveler</a>
        </div>
    </div>
    @else
    <div class="card mb-6 p-8 text-center">
        <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        </div>
        <h2 class="text-lg font-bold text-gray-900 mb-2">Aucun abonnement actif</h2>
        <p class="text-gray-500 text-sm mb-5">Souscrivez à une formule pour accéder à tout le catalogue de vidéos.</p>
        <a href="{{ route('formules') }}" class="btn-primary inline-flex">Voir les formules</a>
    </div>
    @endif

    <!-- Subscription history -->
    @if($subscriptions->isNotEmpty())
    <div class="card">
        <div class="card-header">
            <h3 class="font-semibold text-gray-800">Historique des abonnements</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="table-auto w-full">
                <thead>
                    <tr>
                        <th>Formule</th>
                        <th>Statut</th>
                        <th>Période</th>
                        <th>Montant</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subscriptions as $sub)
                    <tr>
                        <td class="font-medium text-gray-900">{{ $sub->plan->name }}</td>
                        <td>
                            @switch($sub->status->value)
                                @case('active')
                                    <span class="badge-green">Actif</span>
                                    @break
                                @case('expired')
                                    <span class="badge-gray">Expiré</span>
                                    @break
                                @case('pending')
                                    <span class="badge-yellow">En attente</span>
                                    @break
                                @case('cancelled')
                                    <span class="badge-red">Annulé</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="text-gray-500">
                            @if($sub->starts_at && $sub->ends_at)
                                {{ $sub->starts_at->format('d/m/Y') }} → {{ $sub->ends_at->format('d/m/Y') }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="font-medium">{{ number_format($sub->amount, 0, '.', ' ') }} {{ $sub->currency }}</td>
                        <td>
                            @if($sub->status->value === 'pending')
                            <form method="POST" action="{{ route('subscriptions.cancel', $sub) }}" onsubmit="return confirm('Annuler cette demande ?')">
                                @csrf
                                <button type="submit" class="text-xs text-red-600 hover:text-red-800 font-medium transition">Annuler</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection

@extends('layouts.admin')
@section('title', 'Abonnement #' . $subscription->id)
@section('page-title', 'Détail de l\'abonnement')
@section('breadcrumb')
    <a href="{{ route('admin.subscriptions.index') }}" class="hover:text-gray-600">Abonnements</a> / #{{ $subscription->id }}
@endsection

@section('header-actions')
    <a href="{{ route('admin.subscriptions.index') }}" class="btn-secondary btn-sm">← Retour</a>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main info -->
    <div class="lg:col-span-2 space-y-5">
        <div class="card">
            <div class="card-header flex items-center justify-between">
                <h3 class="font-semibold text-gray-800">Informations de l'abonnement</h3>
                @switch($subscription->status->value)
                    @case('active') <span class="badge-green">Actif</span> @break
                    @case('expired') <span class="badge-gray">Expiré</span> @break
                    @case('pending') <span class="badge-yellow">En attente</span> @break
                    @case('cancelled') <span class="badge-red">Annulé</span> @break
                @endswitch
            </div>
            <div class="card-body grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Formule</p>
                    <p class="font-semibold text-gray-900">{{ $subscription->plan->name }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Montant</p>
                    <p class="font-semibold text-gray-900">{{ number_format($subscription->amount, 0, '.', ' ') }} {{ $subscription->currency }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Mode de paiement</p>
                    <p class="font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $subscription->payment_method ?? '—')) }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Référence de paiement</p>
                    <p class="font-semibold text-gray-900">{{ $subscription->payment_reference ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Date de début</p>
                    <p class="font-semibold text-gray-900">{{ $subscription->starts_at?->format('d/m/Y H:i') ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Date de fin</p>
                    <p class="font-semibold text-gray-900">{{ $subscription->ends_at?->format('d/m/Y H:i') ?? '—' }}</p>
                </div>
                @if($subscription->cancelled_at)
                <div>
                    <p class="text-gray-500">Annulé le</p>
                    <p class="font-semibold text-red-600">{{ $subscription->cancelled_at->format('d/m/Y H:i') }}</p>
                </div>
                @endif
                <div>
                    <p class="text-gray-500">Créé le</p>
                    <p class="font-semibold text-gray-900">{{ $subscription->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="card card-body">
            <h3 class="font-semibold text-gray-800 mb-4">Actions</h3>
            <div class="flex flex-wrap gap-3">
                @if($subscription->status->value === 'pending')
                <form method="POST" action="{{ route('admin.subscriptions.activate', $subscription) }}">
                    @csrf
                    <button type="submit" class="btn-success">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Valider et activer
                    </button>
                </form>
                @endif

                @if(in_array($subscription->status->value, ['active', 'expired']))
                <form method="POST" action="{{ route('admin.subscriptions.extend', $subscription) }}" class="flex items-center gap-2">
                    @csrf
                    <input type="number" name="days" min="1" value="30" class="form-input w-24" placeholder="Jours">
                    <button type="submit" class="btn-primary">Prolonger</button>
                </form>
                @endif

                @if(in_array($subscription->status->value, ['active', 'pending']))
                <form method="POST" action="{{ route('admin.subscriptions.cancel', $subscription) }}" onsubmit="return confirm('Annuler cet abonnement ?')">
                    @csrf
                    <button type="submit" class="btn-danger">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Annuler l'abonnement
                    </button>
                </form>
                @endif

                <form method="POST" action="{{ route('admin.subscriptions.destroy', $subscription) }}" onsubmit="return confirm('Supprimer définitivement ?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-secondary text-red-600 border-red-200 hover:bg-red-50">
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Apprenant info -->
    <div class="card card-body h-fit">
        <h3 class="font-semibold text-gray-800 mb-4">Apprenant</h3>
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-sm font-bold text-indigo-600">{{ strtoupper(substr($subscription->user->name, 0, 1)) }}</span>
            </div>
            <div class="min-w-0">
                <p class="font-semibold text-gray-900 truncate">{{ $subscription->user->name }}</p>
                <p class="text-xs text-gray-500 truncate">{{ $subscription->user->email }}</p>
            </div>
        </div>
        <div class="space-y-3 text-sm">
            @if($subscription->user->phone)
            <div class="flex items-center gap-2 text-gray-600">
                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                {{ $subscription->user->phone }}
            </div>
            @endif
            <div class="flex items-center gap-2 text-gray-600">
                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Inscrit le {{ $subscription->user->created_at->format('d/m/Y') }}
            </div>
        </div>
        <div class="mt-5 pt-4 border-t border-gray-100">
            <a href="{{ route('admin.users.show', $subscription->user) }}" class="btn-secondary btn-sm w-full justify-center">
                Voir le profil complet
            </a>
        </div>
    </div>
</div>
@endsection

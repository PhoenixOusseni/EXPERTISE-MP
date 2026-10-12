@extends('layouts.admin')
@section('title', $user->name)
@section('page-title', 'Profil apprenant')
@section('breadcrumb')
    <a href="{{ route('admin.users.index') }}" class="hover:text-gray-600">Apprenants</a> / {{ $user->name }}
@endsection
@section('header-actions')
    <a href="{{ route('admin.users.index') }}" class="btn-secondary btn-sm">← Retour</a>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- User card -->
    <div class="card card-body h-fit">
        <div class="text-center mb-5">
            @if($user->avatarUrl())
                <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}"
                     class="w-16 h-16 rounded-full object-cover mx-auto mb-3 ring-2 ring-indigo-100">
            @else
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="text-2xl font-bold text-indigo-600">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                </div>
            @endif
            <h2 class="text-lg font-bold text-gray-900">{{ $user->name }}</h2>
            @if($user->intitule)
            <p class="text-xs font-semibold text-gray-700 mt-0.5">{{ $user->intitule }}</p>
            @endif
            <p class="text-sm text-gray-500">{{ $user->email }}</p>
            @if($user->profession)
            <p class="text-xs text-indigo-600 font-medium mt-0.5">{{ $user->profession }}</p>
            @endif
            <div class="flex justify-center gap-2 mt-2">
                <span class="badge-blue capitalize">{{ $user->role->value }}</span>
                <span class="{{ $user->is_active ? 'badge-green' : 'badge-red' }}">
                    {{ $user->is_active ? 'Actif' : 'Désactivé' }}
                </span>
            </div>
        </div>
        <div class="space-y-2 text-sm text-gray-600">
            @if($user->phone)
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                {{ $user->phone }}
            </div>
            @endif
            @if($user->address || $user->city || $user->country)
            <div class="flex items-start gap-2">
                <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>{{ implode(', ', array_filter([$user->address, $user->city, $user->country])) }}</span>
            </div>
            @endif
            @if($user->bio)
            <div class="flex items-start gap-2 pt-1 border-t border-gray-100 mt-2">
                <p class="text-xs text-gray-500 italic leading-relaxed">{{ $user->bio }}</p>
            </div>
            @endif
            <div class="flex items-center gap-2 pt-1">
                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Inscrit le {{ $user->created_at->format('d/m/Y') }}
            </div>
        </div>
        <!-- Actions -->
        <div class="mt-5 pt-4 border-t border-gray-100 space-y-2">
            <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                @csrf
                <button type="submit" class="{{ $user->is_active ? 'btn-danger' : 'btn-success' }} btn-sm w-full justify-center">
                    {{ $user->is_active ? 'Désactiver le compte' : 'Activer le compte' }}
                </button>
            </form>
            <a href="{{ route('admin.subscriptions.create', ['user_id' => $user->id]) }}" class="btn-primary btn-sm w-full justify-center">
                + Créer un abonnement
            </a>
        </div>
    </div>

    <!-- Subscriptions history -->
    <div class="lg:col-span-2 card">
        <div class="card-header">
            <h3 class="font-semibold text-gray-800">Historique des abonnements</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="table-auto w-full">
                <thead><tr>
                    <th>Formule</th>
                    <th>Statut</th>
                    <th>Période</th>
                    <th>Montant</th>
                    <th></th>
                </tr></thead>
                <tbody>
                    @forelse($subscriptions as $sub)
                    <tr>
                        <td class="font-medium text-gray-900 text-sm">{{ $sub->plan->name }}</td>
                        <td>
                            @switch($sub->status->value)
                                @case('active') <span class="badge-green">Actif</span> @break
                                @case('expired') <span class="badge-gray">Expiré</span> @break
                                @case('pending') <span class="badge-yellow">En attente</span> @break
                                @case('cancelled') <span class="badge-red">Annulé</span> @break
                            @endswitch
                        </td>
                        <td class="text-xs text-gray-500">
                            @if($sub->starts_at && $sub->ends_at)
                                {{ $sub->starts_at->format('d/m/Y') }} → {{ $sub->ends_at->format('d/m/Y') }}
                            @else —
                            @endif
                        </td>
                        <td class="text-sm font-semibold">{{ number_format($sub->amount, 0, '.', ' ') }} {{ $sub->currency }}</td>
                        <td>
                            <a href="{{ route('admin.subscriptions.show', $sub) }}" class="text-xs text-indigo-600 font-medium hover:text-indigo-800">Voir →</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-8 text-gray-400">Aucun abonnement</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

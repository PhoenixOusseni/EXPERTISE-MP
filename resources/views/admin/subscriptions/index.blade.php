@extends('layouts.admin')
@section('title', 'Abonnements')
@section('page-title', 'Gestion des abonnements')

@section('header-actions')
    <a href="{{ route('admin.subscriptions.create') }}" class="btn-primary btn-sm">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nouvel abonnement
    </a>
@endsection

@section('content')
<!-- Filter tabs -->
<div class="flex flex-wrap gap-2 mb-5">
    @php $currentStatus = request('status', ''); @endphp
    @foreach(['' => 'Tous', 'active' => 'Actifs', 'pending' => 'En attente', 'expired' => 'Expirés', 'cancelled' => 'Annulés'] as $status => $label)
    <a href="{{ route('admin.subscriptions.index', array_merge(request()->except('page'), ['status' => $status ?: null])) }}"
        class="{{ $currentStatus === $status ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }} px-4 py-1.5 rounded-full text-sm font-medium transition">
        {{ $label }}
    </a>
    @endforeach
</div>

<!-- Search -->
<div class="card mb-5">
    <div class="p-4">
        <form method="GET" action="{{ route('admin.subscriptions.index') }}" class="flex flex-wrap gap-3 items-end">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <div class="flex-1 min-w-44">
                <label class="form-label text-xs">Rechercher un apprenant</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-input" placeholder="Nom ou email...">
            </div>
            <div class="w-44">
                <label class="form-label text-xs">Formule</label>
                <select name="plan_id" class="form-input">
                    <option value="">Toutes</option>
                    @foreach($plans as $p)
                    <option value="{{ $p->id }}" {{ request('plan_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-primary btn-sm">Filtrer</button>
            @if(request()->hasAny(['search','plan_id']))
                <a href="{{ route('admin.subscriptions.index', request()->only('status')) }}" class="btn-secondary btn-sm">Réinitialiser</a>
            @endif
        </form>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="overflow-x-auto">
        <table class="table-auto w-full">
            <thead><tr>
                <th>Apprenant</th>
                <th>Formule</th>
                <th>Statut</th>
                <th>Montant</th>
                <th>Paiement</th>
                <th>Période</th>
                <th class="text-right pr-4">Actions</th>
            </tr></thead>
            <tbody>
                @forelse($subscriptions as $sub)
                <tr>
                    <td>
                        <p class="font-medium text-gray-900 text-sm">{{ $sub->user->name }}</p>
                        <p class="text-xs text-gray-400">{{ $sub->user->email }}</p>
                    </td>
                    <td class="text-sm text-gray-700 font-medium">{{ $sub->plan->name }}</td>
                    <td>
                        @switch($sub->status->value)
                            @case('active') <span class="badge-green">Actif</span> @break
                            @case('expired') <span class="badge-gray">Expiré</span> @break
                            @case('pending') <span class="badge-yellow">En attente</span> @break
                            @case('cancelled') <span class="badge-red">Annulé</span> @break
                        @endswitch
                    </td>
                    <td class="text-sm font-semibold text-gray-900">{{ number_format($sub->amount, 0, '.', ' ') }} {{ $sub->currency }}</td>
                    <td class="text-sm text-gray-600">
                        {{ ucfirst(str_replace('_', ' ', $sub->payment_method ?? '—')) }}
                        @if($sub->payment_reference)
                        <p class="text-xs text-gray-400">{{ $sub->payment_reference }}</p>
                        @endif
                    </td>
                    <td class="text-xs text-gray-500">
                        @if($sub->starts_at && $sub->ends_at)
                            {{ $sub->starts_at->format('d/m/Y') }}<br>→ {{ $sub->ends_at->format('d/m/Y') }}
                        @else —
                        @endif
                    </td>
                    <td>
                        <div class="flex items-center justify-end gap-1 pr-2">
                            <a href="{{ route('admin.subscriptions.show', $sub) }}"
                                class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Détails">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            @if($sub->status->value === 'pending')
                            <form method="POST" action="{{ route('admin.subscriptions.activate', $sub) }}">
                                @csrf
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition" title="Activer">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </button>
                            </form>
                            @endif
                            @if(in_array($sub->status->value, ['active', 'pending']))
                            <form method="POST" action="{{ route('admin.subscriptions.cancel', $sub) }}" onsubmit="return confirm('Annuler cet abonnement ?')">
                                @csrf
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Annuler">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-10 text-gray-400">Aucun abonnement trouvé</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($subscriptions->hasPages())
    <div class="px-4 py-4 border-t border-gray-100">{{ $subscriptions->withQueryString()->links() }}</div>
    @endif
</div>
@endsection

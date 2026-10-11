@extends('layouts.admin')
@section('title', 'Messages')
@section('page-title', 'Messages & demandes de contact')

@section('content')
<!-- Filter tabs -->
<div class="flex flex-wrap gap-2 mb-5">
    @php $currentStatus = request('status', ''); @endphp
    @foreach(['' => 'Tous', 'en_attente' => 'En attente', 'en_cours' => 'En cours', 'resolu' => 'Résolu', 'ferme' => 'Fermé'] as $status => $label)
    <a href="{{ route('admin.contact.index', array_merge(request()->except('page'), ['status' => $status ?: null])) }}"
        class="{{ $currentStatus === $status ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }} px-4 py-1.5 rounded-full text-sm font-medium transition flex items-center gap-1.5">
        {{ $label }}
        @if($status === 'en_attente' && $pendingCount > 0)
        <span class="{{ $currentStatus === 'en_attente' ? 'bg-white/20 text-white' : 'bg-red-100 text-red-600' }} text-xs font-bold px-1.5 py-0.5 rounded-full">{{ $pendingCount }}</span>
        @endif
    </a>
    @endforeach
</div>

<!-- Search -->
<div class="card mb-5">
    <div class="p-4">
        <form method="GET" action="{{ route('admin.contact.index') }}" class="flex flex-wrap gap-3 items-end">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <div class="flex-1 min-w-52">
                <label class="form-label text-xs">Rechercher</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-input" placeholder="Nom, email ou sujet...">
            </div>
            <button type="submit" class="btn-primary btn-sm">Filtrer</button>
            @if(request()->filled('search'))
                <a href="{{ route('admin.contact.index', request()->only('status')) }}" class="btn-secondary btn-sm">Réinitialiser</a>
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
                <th>Sujet</th>
                <th>Type</th>
                <th>Statut</th>
                <th>Reçu le</th>
                <th class="text-right pr-4">Actions</th>
            </tr></thead>
            <tbody>
                @forelse($requests as $req)
                <tr class="{{ $req->status->value === 'en_attente' ? 'bg-amber-50/40' : '' }}">
                    <td>
                        <p class="font-medium text-gray-900 text-sm">{{ $req->name }}</p>
                        <p class="text-xs text-gray-400">{{ $req->email }}</p>
                    </td>
                    <td>
                        <p class="text-sm text-gray-800 font-medium max-w-xs truncate">{{ $req->subject }}</p>
                        <p class="text-xs text-gray-400 max-w-xs truncate">{{ $req->message }}</p>
                    </td>
                    <td>
                        @php
                            $typeBadge = match($req->type->value) {
                                'demande' => 'bg-blue-100 text-blue-700',
                                'rdv'     => 'bg-purple-100 text-purple-700',
                                default   => 'bg-gray-100 text-gray-600',
                            };
                        @endphp
                        <span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $typeBadge }}">{{ $req->type->label() }}</span>
                    </td>
                    <td>
                        <span class="{{ $req->status->badgeClass() }} text-xs px-2.5 py-1 rounded-full font-medium">{{ $req->status->label() }}</span>
                        @if($req->isReplied())
                        <p class="text-xs text-gray-400 mt-0.5">Répondu {{ $req->replied_at->diffForHumans() }}</p>
                        @endif
                    </td>
                    <td class="text-xs text-gray-500">{{ $req->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <div class="flex items-center justify-end gap-1 pr-2">
                            <a href="{{ route('admin.contact.show', $req) }}"
                                class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Voir & répondre">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-10 text-gray-400">Aucun message trouvé</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($requests->hasPages())
    <div class="px-4 py-4 border-t border-gray-100">{{ $requests->withQueryString()->links() }}</div>
    @endif
</div>
@endsection

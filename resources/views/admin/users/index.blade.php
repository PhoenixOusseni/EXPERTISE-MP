@extends('layouts.admin')
@section('title', 'Apprenants')
@section('page-title', 'Gestion des apprenants')

@section('content')
<!-- Filters -->
<div class="card mb-5">
    <div class="p-4">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-44">
                <label class="form-label text-xs">Recherche</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-input" placeholder="Nom ou email...">
            </div>
            <div class="w-40">
                <label class="form-label text-xs">Statut</label>
                <select name="subscribed" class="form-input">
                    <option value="">Tous</option>
                    <option value="1" {{ request('subscribed') === '1' ? 'selected' : '' }}>Abonnés actifs</option>
                    <option value="0" {{ request('subscribed') === '0' ? 'selected' : '' }}>Sans abonnement</option>
                </select>
            </div>
            <button type="submit" class="btn-primary btn-sm">Filtrer</button>
            @if(request()->hasAny(['search', 'subscribed']))
                <a href="{{ route('admin.users.index') }}" class="btn-secondary btn-sm">Réinitialiser</a>
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
                <th>Téléphone</th>
                <th>Abonnement</th>
                <th>Compte</th>
                <th>Inscrit le</th>
                <th class="text-right pr-4">Actions</th>
            </tr></thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-xs font-bold text-indigo-600">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                            <div class="min-w-0">
                                <p class="font-medium text-gray-900 text-sm truncate">{{ $user->name }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="text-sm text-gray-600">{{ $user->phone ?? '—' }}</td>
                    <td>
                        @if($user->activeSubscription)
                            <span class="badge-green">Actif</span>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $user->activeSubscription->plan->name }} — expire {{ $user->activeSubscription->ends_at->format('d/m/Y') }}</p>
                        @else
                            <span class="badge-gray">Inactif</span>
                        @endif
                    </td>
                    <td>
                        @if($user->is_active)
                            <span class="badge-green">Actif</span>
                        @else
                            <span class="badge-red">Désactivé</span>
                        @endif
                    </td>
                    <td class="text-sm text-gray-500">{{ $user->created_at->format('d/m/Y') }}</td>
                    <td>
                        <div class="flex items-center justify-end gap-1 pr-2">
                            <a href="{{ route('admin.users.show', $user) }}"
                                class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Voir">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                                @csrf
                                <button type="submit"
                                    class="p-1.5 rounded-lg transition {{ $user->is_active ? 'text-gray-400 hover:text-amber-600 hover:bg-amber-50' : 'text-gray-400 hover:text-emerald-600 hover:bg-emerald-50' }}"
                                    title="{{ $user->is_active ? 'Désactiver' : 'Activer' }}">
                                    @if($user->is_active)
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @endif
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-10 text-gray-400">Aucun apprenant trouvé</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="px-4 py-4 border-t border-gray-100">{{ $users->withQueryString()->links() }}</div>
    @endif
</div>
@endsection

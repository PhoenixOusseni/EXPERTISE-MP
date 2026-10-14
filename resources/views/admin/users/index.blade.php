@extends('layouts.admin')
@section('title', 'Apprenants')
@section('page-title', 'Gestion des apprenants')

@section('content')

@php
    $activeFilters = collect([
        'search'       => request('search'),
        'subscribed'   => request('subscribed'),
        'gender'       => request('gender'),
        'profile_type' => request('profile_type'),
        'country'      => request('country'),
        'city'         => request('city'),
    ])->filter()->count();

    $filterLabels = [
        'subscribed'   => ['1' => 'Abonnés actifs', '0' => 'Sans abonnement'],
        'gender'       => ['homme' => 'Homme', 'femme' => 'Femme', 'autre' => 'Autre'],
        'profile_type' => ['entreprise' => 'Entreprise', 'administration' => 'Administration', 'autre' => 'Autre'],
    ];
@endphp

{{-- ── Barre de filtres ── --}}
<div class="card mb-5" x-data="{ open: {{ $activeFilters > 0 ? 'true' : 'false' }} }">

    {{-- En-tête --}}
    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
        <button type="button" @click="open = !open"
            class="flex items-center gap-2 text-sm font-semibold text-gray-700 hover:text-gray-900 transition">
            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
            </svg>
            Filtres
            @if($activeFilters > 0)
                <span class="inline-flex items-center justify-center w-5 h-5 text-[11px] font-bold rounded-full bg-[#1B4FD8] text-white">
                    {{ $activeFilters }}
                </span>
            @endif
            <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        @if($activeFilters > 0)
            <a href="{{ route('admin.users.index') }}" class="text-xs text-red-400 hover:text-red-600 transition flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Effacer tout
            </a>
        @endif
    </div>

    {{-- Panneau de filtres --}}
    <div x-show="open" x-collapse x-cloak>
        <form method="GET" action="{{ route('admin.users.index') }}" class="p-4">

            {{-- Ligne 1 : Recherche + Abonnement --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-3">
                <div class="lg:col-span-2">
                    <label class="form-label text-xs">Recherche</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="form-input pl-9"
                            placeholder="Nom, email ou téléphone...">
                    </div>
                </div>
                <div>
                    <label class="form-label text-xs">Abonnement</label>
                    <select name="subscribed" class="form-input">
                        <option value="">Tous</option>
                        <option value="1" {{ request('subscribed') === '1' ? 'selected' : '' }}>Abonnés actifs</option>
                        <option value="0" {{ request('subscribed') === '0' ? 'selected' : '' }}>Sans abonnement</option>
                    </select>
                </div>
            </div>

            {{-- Ligne 2 : Genre + Profil + Pays + Ville --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
                <div>
                    <label class="form-label text-xs">Genre</label>
                    <select name="gender" class="form-input">
                        <option value="">Tous</option>
                        <option value="homme" {{ request('gender') === 'homme' ? 'selected' : '' }}>Homme</option>
                        <option value="femme" {{ request('gender') === 'femme' ? 'selected' : '' }}>Femme</option>
                        <option value="autre" {{ request('gender') === 'autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                </div>
                <div>
                    <label class="form-label text-xs">Type de profil</label>
                    <select name="profile_type" class="form-input">
                        <option value="">Tous</option>
                        @foreach(\App\Enums\LearnerProfile::cases() as $pt)
                            <option value="{{ $pt->value }}" {{ request('profile_type') === $pt->value ? 'selected' : '' }}>
                                {{ $pt->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label text-xs">Pays</label>
                    <select name="country" class="form-input">
                        <option value="">Tous</option>
                        @foreach($countries as $c)
                            <option value="{{ $c }}" {{ request('country') === $c ? 'selected' : '' }}>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label text-xs">Ville</label>
                    <select name="city" class="form-input">
                        <option value="">Toutes</option>
                        @foreach($cities as $v)
                            <option value="{{ $v }}" {{ request('city') === $v ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn-primary btn-sm flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Appliquer
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn-secondary btn-sm">Réinitialiser</a>
            </div>
        </form>
    </div>

    {{-- Chips des filtres actifs --}}
    @if($activeFilters > 0)
    <div class="flex flex-wrap gap-2 px-4 pb-3 pt-1">
        @if(request('search'))
            <span class="inline-flex items-center gap-1.5 text-xs bg-blue-50 text-blue-700 border border-blue-200 rounded-full px-2.5 py-1 font-medium">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                « {{ request('search') }} »
                <a href="{{ route('admin.users.index', array_merge(request()->except('search', 'page'))) }}" class="hover:text-blue-900">×</a>
            </span>
        @endif
        @foreach(['subscribed', 'gender', 'profile_type'] as $key)
            @if(request($key))
                <span class="inline-flex items-center gap-1.5 text-xs bg-blue-50 text-blue-700 border border-blue-200 rounded-full px-2.5 py-1 font-medium">
                    {{ $filterLabels[$key][request($key)] ?? request($key) }}
                    <a href="{{ route('admin.users.index', array_merge(request()->except($key, 'page'))) }}" class="hover:text-blue-900">×</a>
                </span>
            @endif
        @endforeach
        @if(request('country'))
            <span class="inline-flex items-center gap-1.5 text-xs bg-blue-50 text-blue-700 border border-blue-200 rounded-full px-2.5 py-1 font-medium">
                🌍 {{ request('country') }}
                <a href="{{ route('admin.users.index', array_merge(request()->except('country', 'page'))) }}" class="hover:text-blue-900">×</a>
            </span>
        @endif
        @if(request('city'))
            <span class="inline-flex items-center gap-1.5 text-xs bg-blue-50 text-blue-700 border border-blue-200 rounded-full px-2.5 py-1 font-medium">
                📍 {{ request('city') }}
                <a href="{{ route('admin.users.index', array_merge(request()->except('city', 'page'))) }}" class="hover:text-blue-900">×</a>
            </span>
        @endif
    </div>
    @endif
</div>

{{-- ── Résultats ── --}}
<div class="card">

    {{-- Compteur --}}
    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
        <p class="text-sm text-gray-500">
            <span class="font-semibold text-gray-800">{{ $users->total() }}</span>
            apprenant{{ $users->total() > 1 ? 's' : '' }} trouvé{{ $users->total() > 1 ? 's' : '' }}
        </p>
    </div>

    <div class="overflow-x-auto">
        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th>Apprenant</th>
                    <th>Profil</th>
                    <th>Localisation</th>
                    <th>Abonnement</th>
                    <th>Compte</th>
                    <th>Inscrit le</th>
                    <th class="text-right pr-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        {{-- Identité --}}
                        <td>
                            <div class="flex items-center gap-3">
                                @php
                                    $avatarBg = match($user->profile_type?->value) {
                                        'entreprise'    => 'bg-blue-100',
                                        'administration'=> 'bg-emerald-100',
                                        default         => 'bg-gray-100',
                                    };
                                    $avatarText = match($user->profile_type?->value) {
                                        'entreprise'    => 'text-blue-600',
                                        'administration'=> 'text-emerald-600',
                                        default         => 'text-gray-500',
                                    };
                                @endphp
                                <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 {{ $avatarBg }}">
                                    <span class="text-xs font-bold {{ $avatarText }}">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-medium text-gray-900 text-sm truncate">{{ $user->name }}</p>
                                    @if ($user->intitule)
                                        <p class="text-xs text-gray-500 truncate">{{ $user->intitule }}</p>
                                    @endif
                                    <p class="text-xs text-gray-400 truncate">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Profil --}}
                        <td>
                            <div class="space-y-1">
                                @if($user->profile_type)
                                    @php
                                        $badgeClass = match($user->profile_type->value) {
                                            'entreprise'    => 'bg-blue-50 text-blue-700',
                                            'administration'=> 'bg-emerald-50 text-emerald-700',
                                            default         => 'bg-gray-100 text-gray-600',
                                        };
                                    @endphp
                                    <span class="inline-block text-xs font-semibold px-2 py-0.5 rounded-full {{ $badgeClass }}">
                                        {{ $user->profile_type->label() }}
                                    </span>
                                @endif
                                @if($user->gender)
                                    <p class="text-xs text-gray-400 capitalize">{{ $user->gender }}</p>
                                @endif
                                @if($user->phone)
                                    <p class="text-xs text-gray-400">{{ $user->phone }}</p>
                                @endif
                            </div>
                        </td>

                        {{-- Localisation --}}
                        <td class="text-sm text-gray-500">
                            @if($user->city || $user->country)
                                <p class="text-sm text-gray-700">{{ $user->city ?? '—' }}</p>
                                <p class="text-xs text-gray-400">{{ $user->country ?? '' }}</p>
                            @else
                                <span class="text-gray-300">—</span>
                            @endif
                        </td>

                        {{-- Abonnement --}}
                        <td>
                            @if ($user->activeSubscription)
                                <span class="badge-green">Actif</span>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    {{ $user->activeSubscription->plan->name }}<br>
                                    expire {{ $user->activeSubscription->ends_at->format('d/m/Y') }}
                                </p>
                            @else
                                <span class="badge-gray">Inactif</span>
                            @endif
                        </td>

                        {{-- Compte --}}
                        <td>
                            @if ($user->is_active)
                                <span class="badge-green">Actif</span>
                            @else
                                <span class="badge-red">Désactivé</span>
                            @endif
                        </td>

                        {{-- Date --}}
                        <td class="text-sm text-gray-500">{{ $user->created_at->format('d/m/Y') }}</td>

                        {{-- Actions --}}
                        <td>
                            <div class="flex items-center justify-end gap-1 pr-2">
                                <a href="{{ route('admin.users.show', $user) }}"
                                    class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition"
                                    title="Voir">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                                    @csrf
                                    <button type="submit"
                                        class="p-1.5 rounded-lg transition {{ $user->is_active ? 'text-gray-400 hover:text-amber-600 hover:bg-amber-50' : 'text-gray-400 hover:text-emerald-600 hover:bg-emerald-50' }}"
                                        title="{{ $user->is_active ? 'Désactiver' : 'Activer' }}">
                                        @if ($user->is_active)
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @endif
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-12">
                            <svg class="w-10 h-10 text-gray-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <p class="text-sm text-gray-400">Aucun apprenant ne correspond aux filtres sélectionnés</p>
                            <a href="{{ route('admin.users.index') }}" class="mt-2 inline-block text-xs text-[#1B4FD8] hover:underline">Effacer les filtres</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($users->hasPages())
        <div class="px-4 py-4 border-t border-gray-100">
            {{ $users->withQueryString()->links() }}
        </div>
    @endif
</div>

@endsection

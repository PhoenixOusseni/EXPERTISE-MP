@extends('layouts.app')
@section('title', 'Mon profil')

@section('content')
<div class="max-w-4xl mx-auto" x-data="{ tab: '{{ session('tab', 'profil') }}' }">

    {{-- ── En-tête profil ── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">

        {{-- Bandeau de couleur --}}
        <div class="h-24 bg-gradient-to-r from-[#1a3a6e] to-[#1B4FD8] relative">
            @if($user->profile_type)
                <span class="absolute bottom-3 right-4 text-xs font-bold px-3 py-1 rounded-full bg-white/20 text-white backdrop-blur-sm">
                    {{ $user->profile_type->label() }}
                </span>
            @endif
        </div>

        <div class="px-6 pb-6">
            <div class="flex flex-col sm:flex-row sm:items-end gap-4 -mt-10 mb-4">

                {{-- Avatar --}}
                <div class="relative group flex-shrink-0">
                    @if ($user->avatarUrl())
                        <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}"
                            class="w-20 h-20 rounded-full object-cover ring-4 ring-white shadow-md">
                    @else
                        <div class="w-20 h-20 bg-[#1B4FD8]/10 rounded-full flex items-center justify-center ring-4 ring-white shadow-md">
                            <span class="text-2xl font-bold text-[#1B4FD8]">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                    @endif
                    <label for="avatar-input"
                        class="absolute inset-0 rounded-full bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center cursor-pointer">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </label>
                    <form id="avatar-form" method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data">
                        @csrf
                        <input id="avatar-input" type="file" name="avatar" accept="image/jpeg,image/png,image/webp"
                            class="hidden" onchange="document.getElementById('avatar-form').submit()">
                    </form>
                </div>

                {{-- Identité --}}
                <div class="flex-1 min-w-0 pt-2 sm:pt-0">
                    <h1 class="text-xl font-bold text-gray-900">{{ $user->fullName() }}</h1>
                    @if ($user->intitule)
                        <p class="text-sm font-medium text-[#1B4FD8]">{{ $user->intitule }}</p>
                    @endif
                    @if ($user->profession)
                        <p class="text-sm text-gray-500">{{ $user->profession }}</p>
                    @endif
                </div>

                {{-- Actions rapides --}}
                <div class="flex items-center gap-2 flex-shrink-0">
                    @if ($user->avatarUrl())
                        <form method="POST" action="{{ route('profile.avatar.remove') }}"
                            onsubmit="return confirm('Supprimer la photo ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-400 hover:text-red-600 transition">Supprimer la photo</button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Badges info --}}
            <div class="flex flex-wrap gap-3 text-xs text-gray-500">
                <span class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    {{ $user->email }}
                </span>
                @if($user->phone)
                <span class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    {{ $user->phone }}
                </span>
                @endif
                @if($user->city || $user->country)
                <span class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ implode(', ', array_filter([$user->city, $user->country])) }}
                </span>
                @endif
                <span class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Membre depuis {{ $user->created_at->translatedFormat('F Y') }}
                </span>
            </div>

            @if($user->bio)
            <p class="mt-4 text-sm text-gray-600 leading-relaxed border-t border-gray-100 pt-4">{{ $user->bio }}</p>
            @endif
        </div>
    </div>

    {{-- ── Onglets ── --}}
    <div class="flex border-b border-gray-200 mb-6 gap-1">
        <button @click="tab = 'profil'"
            :class="tab === 'profil' ? 'border-b-2 border-[#1B4FD8] text-[#1B4FD8]' : 'text-gray-500 hover:text-gray-700'"
            class="px-4 py-2.5 text-sm font-medium transition">
            Informations personnelles
        </button>
        <button @click="tab = 'securite'"
            :class="tab === 'securite' ? 'border-b-2 border-[#1B4FD8] text-[#1B4FD8]' : 'text-gray-500 hover:text-gray-700'"
            class="px-4 py-2.5 text-sm font-medium transition">
            Sécurité
        </button>
    </div>

    {{-- ── Onglet : Informations ── --}}
    <div x-show="tab === 'profil'" x-cloak>

        @if(session('success') && session('tab') !== 'securite')
            <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-xl px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
            @csrf @method('PUT')

            {{-- Section 1 : Identité --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-5">Identité</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    <div>
                        <label class="form-label">Nom <span class="text-red-500">*</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                            class="form-input mt-1 @error('last_name') border-red-300 @enderror"
                            placeholder="Votre nom de famille" required>
                        @error('last_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label">Prénom <span class="text-red-500">*</span></label>
                        <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}"
                            class="form-input mt-1 @error('first_name') border-red-300 @enderror"
                            placeholder="Votre prénom" required>
                        @error('first_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label">Date de naissance</label>
                        <input type="date" name="birth_date"
                            value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}"
                            max="{{ now()->subDay()->format('Y-m-d') }}"
                            class="form-input mt-1 @error('birth_date') border-red-300 @enderror">
                        @error('birth_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label">Genre</label>
                        <select name="gender" class="form-input mt-1">
                            <option value="">— Non renseigné —</option>
                            <option value="homme"  {{ old('gender', $user->gender) === 'homme'  ? 'selected' : '' }}>Homme</option>
                            <option value="femme"  {{ old('gender', $user->gender) === 'femme'  ? 'selected' : '' }}>Femme</option>
                            <option value="autre"  {{ old('gender', $user->gender) === 'autre'  ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="form-label">Type de profil</label>
                        <div class="mt-1 flex items-center gap-2 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-600">
                            <svg class="w-4 h-4 text-[#1B4FD8]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span class="font-medium">{{ $user->profile_type?->label() ?? '—' }}</span>
                            <span class="text-gray-400 text-xs ml-1">(non modifiable)</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 2 : Coordonnées --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-5">Coordonnées</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    <div>
                        <label class="form-label">Adresse e-mail <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                            class="form-input mt-1 @error('email') border-red-300 @enderror" required>
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label">Téléphone</label>
                        <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                            class="form-input mt-1" placeholder="+226 XX XX XX XX">
                    </div>

                    <div>
                        <label class="form-label">Pays</label>
                        <input type="text" name="country" value="{{ old('country', $user->country) }}"
                            class="form-input mt-1" placeholder="Ex : Burkina Faso">
                    </div>

                    <div>
                        <label class="form-label">Ville</label>
                        <input type="text" name="city" value="{{ old('city', $user->city) }}"
                            class="form-input mt-1" placeholder="Ex : Ouagadougou">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="form-label">Adresse</label>
                        <input type="text" name="address" value="{{ old('address', $user->address) }}"
                            class="form-input mt-1" placeholder="Rue, quartier, secteur...">
                    </div>
                </div>
            </div>

            {{-- Section 3 : Profil professionnel --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-5">Profil professionnel</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    <div>
                        <label class="form-label">Intitulé du poste</label>
                        <input type="text" name="intitule" value="{{ old('intitule', $user->intitule) }}"
                            class="form-input mt-1 @error('intitule') border-red-300 @enderror"
                            placeholder="Ex : Directeur des marchés publics">
                        @error('intitule')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label">Profession</label>
                        <input type="text" name="profession" value="{{ old('profession', $user->profession) }}"
                            class="form-input mt-1" placeholder="Ex : Responsable marchés publics">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="form-label">Bio <span class="text-gray-400 font-normal text-xs">(max 500 caractères)</span></label>
                        <textarea name="bio" rows="3" maxlength="500"
                            class="form-input mt-1 resize-none @error('bio') border-red-300 @enderror"
                            placeholder="Quelques mots sur vous, votre parcours, vos objectifs...">{{ old('bio', $user->bio) }}</textarea>
                        @error('bio')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="btn btn-primary gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>

    {{-- ── Onglet : Sécurité ── --}}
    <div x-show="tab === 'securite'" x-cloak>

        @if(session('success') && session('tab') === 'securite')
            <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-xl px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="text-base font-semibold text-gray-800 mb-5">Changer le mot de passe</h2>
            <form method="POST" action="{{ route('profile.password') }}" class="space-y-5 max-w-md">
                @csrf @method('PUT')
                <div>
                    <label class="form-label">Mot de passe actuel <span class="text-red-500">*</span></label>
                    <input type="password" name="current_password"
                        class="form-input mt-1 @error('current_password') border-red-300 @enderror"
                        placeholder="Votre mot de passe actuel" autocomplete="current-password">
                    @error('current_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Nouveau mot de passe <span class="text-red-500">*</span></label>
                    <input type="password" name="password"
                        class="form-input mt-1 @error('password') border-red-300 @enderror"
                        placeholder="Au moins 8 caractères" autocomplete="new-password">
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Confirmer le nouveau mot de passe <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" class="form-input mt-1"
                        placeholder="Répéter le nouveau mot de passe" autocomplete="new-password">
                </div>
                <div class="pt-2">
                    <button type="submit" class="btn btn-primary gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Mettre à jour le mot de passe
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

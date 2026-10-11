@extends('layouts.app')
@section('title', 'Mon profil')

@section('content')
<div class="max-w-4xl mx-auto" x-data="{ tab: '{{ session('tab', 'profil') }}' }">

    <div class="flex items-center gap-4 mb-6">
        {{-- Avatar --}}
        <div class="relative group flex-shrink-0">
            @if($user->avatarUrl())
                <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}"
                     class="w-20 h-20 rounded-full object-cover ring-4 ring-white shadow-md">
            @else
                <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center ring-4 ring-white shadow-md">
                    <span class="text-3xl font-bold text-indigo-600">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                </div>
            @endif
            {{-- Bouton changer photo --}}
            <label for="avatar-input"
                   class="absolute inset-0 rounded-full bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center cursor-pointer">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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

        <div class="flex-1 min-w-0">
            <h1 class="text-2xl font-bold text-gray-900 truncate">{{ $user->name }}</h1>
            <p class="text-sm text-gray-500">{{ $user->email }}</p>
            @if($user->profession)
            <p class="text-sm text-indigo-600 font-medium mt-0.5">{{ $user->profession }}</p>
            @endif
        </div>

        @if($user->avatarUrl())
        <form method="POST" action="{{ route('profile.avatar.remove') }}" onsubmit="return confirm('Supprimer la photo ?')">
            @csrf @method('DELETE')
            <button type="submit" class="text-xs text-red-500 hover:text-red-700 transition">Supprimer la photo</button>
        </form>
        @endif
    </div>

    {{-- Onglets --}}
    <div class="flex border-b border-gray-200 mb-6 gap-1">
        <button @click="tab = 'profil'"
            :class="tab === 'profil' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-700'"
            class="px-4 py-2.5 text-sm font-medium transition">
            Informations personnelles
        </button>
        <button @click="tab = 'securite'"
            :class="tab === 'securite' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-700'"
            class="px-4 py-2.5 text-sm font-medium transition">
            Sécurité
        </button>
    </div>

    {{-- ── Onglet : Informations ── --}}
    <div x-show="tab === 'profil'" x-cloak>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
                @csrf @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Nom complet <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                               class="form-input mt-1 @error('name') border-red-300 @enderror" required>
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label">Adresse e-mail <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                               class="form-input mt-1 @error('email') border-red-300 @enderror" required>
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label">Téléphone</label>
                        <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                               class="form-input mt-1" placeholder="+226 xx xx xx xx">
                    </div>

                    <div>
                        <label class="form-label">Profession / Poste</label>
                        <input type="text" name="profession" value="{{ old('profession', $user->profession) }}"
                               class="form-input mt-1" placeholder="Ex: Responsable marchés publics">
                    </div>

                    <div>
                        <label class="form-label">Adresse</label>
                        <input type="text" name="address" value="{{ old('address', $user->address) }}"
                               class="form-input mt-1" placeholder="Rue, quartier...">
                    </div>

                    <div>
                        <label class="form-label">Ville</label>
                        <input type="text" name="city" value="{{ old('city', $user->city) }}"
                               class="form-input mt-1" placeholder="Ex: Ouagadougou">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="form-label">Pays</label>
                        <input type="text" name="country" value="{{ old('country', $user->country) }}"
                               class="form-input mt-1" placeholder="Ex: Burkina Faso">
                    </div>
                </div>

                <div>
                    <label class="form-label">Bio <span class="text-gray-400 font-normal text-xs">(max 500 caractères)</span></label>
                    <textarea name="bio" rows="3"
                              class="form-input mt-1 resize-none @error('bio') border-red-300 @enderror"
                              maxlength="500"
                              placeholder="Quelques mots sur vous, votre parcours...">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="pt-2 flex justify-end">
                    <button type="submit" class="btn btn-primary gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Onglet : Sécurité ── --}}
    <div x-show="tab === 'securite'" x-cloak>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
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
                    <input type="password" name="password_confirmation"
                           class="form-input mt-1"
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

@extends('layouts.guest')
@section('title', 'Inscription')
@section('card-max-width', 'max-w-[640px]')

@section('content')
    <div class="card">
        <div class="card-body">
            <h2 class="text-2xl font-bold text-center text-gray-900 mb-1">Créer un compte</h2>
            <p class="text-sm text-center text-gray-500 mb-8">Rejoignez la plateforme Expertise Marchés Publics</p>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                {{-- Type de profil --}}
                <div>
                    <label class="form-label">Type de profil <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-3 gap-3 mt-1">
                        @foreach (\App\Enums\LearnerProfile::cases() as $profile)
                            <label
                                class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer transition
                            {{ old('profile_type') === $profile->value ? 'border-indigo-500 bg-indigo-50' : 'border-gray-300 hover:border-indigo-300' }}">
                                <input type="radio" name="profile_type" value="{{ $profile->value }}"
                                    {{ old('profile_type') === $profile->value ? 'checked' : '' }}
                                    class="text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm font-medium text-gray-700">{{ $profile->label() }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('profile_type')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Intitulé --}}
                <div>
                    <label for="intitule" class="form-label">
                        Intitulé
                        <span class="text-gray-400 font-normal">(facultatif)</span>
                    </label>
                    <input id="intitule" type="text" name="intitule" value="{{ old('intitule') }}"
                        class="form-input {{ $errors->has('intitule') ? 'border-red-400' : '' }}"
                        placeholder="Ex : Directeur des marchés publics">
                    @error('intitule')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                {{-- Nom / Prénom --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="last_name" class="form-label">Nom <span class="text-red-500">*</span></label>
                        <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required
                            autofocus class="form-input {{ $errors->has('last_name') ? 'border-red-400' : '' }}"
                            placeholder="Nom de famille">
                        @error('last_name')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="first_name" class="form-label">Prénom <span class="text-red-500">*</span></label>
                        <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required
                            class="form-input {{ $errors->has('first_name') ? 'border-red-400' : '' }}"
                            placeholder="Prénom">
                        @error('first_name')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Continent --}}
                @php
                    $continents = ['Afrique','Amérique du Nord','Amérique du Sud','Asie','Europe','Océanie'];
                @endphp
                <div>
                    <label for="continent" class="form-label">Continent <span class="text-red-500">*</span></label>
                    <select id="continent" name="continent" required
                        class="form-input {{ $errors->has('continent') ? 'border-red-400' : '' }}">
                        <option value="">— Sélectionner un continent —</option>
                        @foreach($continents as $c)
                            <option value="{{ $c }}" {{ old('continent') === $c ? 'selected' : '' }}>{{ $c }}</option>
                        @endforeach
                    </select>
                    @error('continent')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                {{-- Pays / Ville --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="country" class="form-label">Pays <span class="text-red-500">*</span></label>
                        <input id="country" type="text" name="country" value="{{ old('country') }}" required
                            class="form-input {{ $errors->has('country') ? 'border-red-400' : '' }}"
                            placeholder="Ex : Burkina Faso">
                        @error('country')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="city" class="form-label">Ville <span class="text-red-500">*</span></label>
                        <input id="city" type="text" name="city" value="{{ old('city') }}" required
                            class="form-input {{ $errors->has('city') ? 'border-red-400' : '' }}"
                            placeholder="Ex : Ouagadougou">
                        @error('city')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="form-label">Adresse e-mail <span class="text-red-500">*</span></label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        class="form-input {{ $errors->has('email') ? 'border-red-400' : '' }}"
                        placeholder="votre@email.com">
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Téléphone --}}
                <div>
                    <label for="phone" class="form-label">Numéro de téléphone <span class="text-red-500">*</span></label>
                    <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" required
                        class="form-input {{ $errors->has('phone') ? 'border-red-400' : '' }}"
                        placeholder="+226 xx xx xx xx">
                    @error('phone')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Date de naissance / Genre (facultatifs) --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="birth_date" class="form-label">
                            Date de naissance
                            <span class="text-gray-400 font-normal">(facultatif)</span>
                        </label>
                        <input id="birth_date" type="date" name="birth_date" value="{{ old('birth_date') }}"
                            class="form-input {{ $errors->has('birth_date') ? 'border-red-400' : '' }}">
                        @error('birth_date')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="gender" class="form-label">
                            Genre
                            <span class="text-gray-400 font-normal">(facultatif)</span>
                        </label>
                        <select id="gender" name="gender"
                            class="form-input {{ $errors->has('gender') ? 'border-red-400' : '' }}">
                            <option value="">— Sélectionner —</option>
                            <option value="homme" {{ old('gender') === 'homme' ? 'selected' : '' }}>Homme</option>
                            <option value="femme" {{ old('gender') === 'femme' ? 'selected' : '' }}>Femme</option>
                        </select>
                        @error('gender')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Mot de passe --}}
                <div>
                    <label for="password" class="form-label">Mot de passe <span class="text-red-500">*</span></label>
                    <div class="relative" x-data="{ show: false }">
                        <input id="password" :type="show ? 'text' : 'password'" name="password" required
                            class="form-input pr-10 {{ $errors->has('password') ? 'border-red-400' : '' }}"
                            placeholder="Au moins 8 caractères">
                        <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg x-show="!show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <svg x-show="show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="display:none">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="form-label">Confirmer le mot de passe <span
                            class="text-red-500">*</span></label>
                    <div class="relative" x-data="{ show: false }">
                        <input id="password_confirmation" :type="show ? 'text' : 'password'" name="password_confirmation" required
                            class="form-input pr-10" placeholder="Répéter le mot de passe">
                        <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg x-show="!show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <svg x-show="show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="display:none">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-primary w-full py-2.5 text-base">
                    Créer mon compte
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-500">
                Déjà inscrit ?
                <a href="{{ route('login') }}" class="text-indigo-600 font-semibold hover:text-indigo-700">Se
                    connecter</a>
            </p>
        </div>
    </div>
@endsection

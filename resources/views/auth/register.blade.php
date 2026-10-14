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
                    <div class="grid grid-cols-2 gap-3 mt-1">
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
                    <input id="password" type="password" name="password" required
                        class="form-input {{ $errors->has('password') ? 'border-red-400' : '' }}"
                        placeholder="Au moins 8 caractères">
                    @error('password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="form-label">Confirmer le mot de passe <span
                            class="text-red-500">*</span></label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="form-input" placeholder="Répéter le mot de passe">
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

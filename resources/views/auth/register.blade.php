@extends('layouts.guest')
@section('title', 'Inscription')

@section('content')
<div class="card">
    <div class="card-body">
        <h2 class="text-2xl font-bold text-center text-gray-900 mb-1">Créer un compte</h2>
        <p class="text-sm text-center text-gray-500 mb-8">Rejoignez la plateforme Expertise Marchés Publics</p>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="form-label">Nom complet</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                    class="form-input {{ $errors->has('name') ? 'border-red-400' : '' }}"
                    placeholder="Prénom et nom">
                @error('name')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="email" class="form-label">Adresse e-mail</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    class="form-input {{ $errors->has('email') ? 'border-red-400' : '' }}"
                    placeholder="votre@email.com">
                @error('email')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="phone" class="form-label">Téléphone <span class="text-gray-400 font-normal">(optionnel)</span></label>
                <input id="phone" type="tel" name="phone" value="{{ old('phone') }}"
                    class="form-input"
                    placeholder="+221 xx xxx xx xx">
            </div>

            <div>
                <label for="password" class="form-label">Mot de passe</label>
                <input id="password" type="password" name="password" required
                    class="form-input {{ $errors->has('password') ? 'border-red-400' : '' }}"
                    placeholder="Au moins 8 caractères">
                @error('password')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    class="form-input"
                    placeholder="Répéter le mot de passe">
            </div>

            <button type="submit" class="btn-primary w-full py-2.5 text-base">
                Créer mon compte
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-500">
            Déjà inscrit ?
            <a href="{{ route('login') }}" class="text-indigo-600 font-semibold hover:text-indigo-700">Se connecter</a>
        </p>
    </div>
</div>
@endsection

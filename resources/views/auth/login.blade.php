@extends('layouts.guest')
@section('title', 'Connexion')

@section('content')
<div class="card">
    <div class="card-body">
        <h2 class="text-2xl font-bold text-center text-gray-900 mb-1">Bienvenue !</h2>
        <p class="text-sm text-center text-gray-500 mb-8">Connectez-vous à votre espace Expertise Marchés Publics</p>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="form-label">Adresse e-mail</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="form-input {{ $errors->has('email') ? 'border-red-400 focus:ring-red-400' : '' }}"
                    placeholder="votre@email.com">
                @error('email')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="form-label mb-0">Mot de passe</label>
                </div>
                <input id="password" type="password" name="password" required
                    class="form-input {{ $errors->has('password') ? 'border-red-400 focus:ring-red-400' : '' }}"
                    placeholder="••••••••">
                @error('password')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-2">
                <input id="remember" type="checkbox" name="remember" class="w-4 h-4 text-indigo-600 border-gray-300 rounded">
                <label for="remember" class="text-sm text-gray-600">Se souvenir de moi</label>
            </div>

            <button type="submit" class="btn-primary w-full py-2.5 text-base">
                Se connecter
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-500">
            Pas encore de compte ?
            <a href="{{ route('register') }}" class="text-indigo-600 font-semibold hover:text-indigo-700">S'inscrire gratuitement</a>
        </p>
    </div>
</div>
@endsection

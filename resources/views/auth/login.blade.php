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
                    <label for="login" class="form-label">Email ou numéro de téléphone</label>
                    <input id="login" type="text" name="login" value="{{ old('login') }}" required autofocus
                        class="form-input {{ $errors->has('login') ? 'border-red-400 focus:ring-red-400' : '' }}"
                        placeholder="votre@email.com ou +226 xx xx xx xx">
                    @error('login')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="form-label mb-0">Mot de passe</label>
                    </div>
                    <div class="relative" x-data="{ show: false }">
                        <input id="password" :type="show ? 'text' : 'password'" name="password" required
                            class="form-input pr-10 {{ $errors->has('password') ? 'border-red-400 focus:ring-red-400' : '' }}"
                            placeholder="••••••••">
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

                <div class="flex items-center gap-2">
                    <input id="remember" type="checkbox" name="remember"
                        class="w-4 h-4 text-indigo-600 border-gray-300 rounded">
                    <label for="remember" class="text-sm text-gray-600">Se souvenir de moi</label>
                </div>

                <button type="submit" class="btn-primary w-full py-2.5 text-base">
                    Se connecter
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-500">
                Pas encore de compte ?
                <a href="{{ route('register') }}" class="text-indigo-600 font-semibold hover:text-indigo-700">S'inscrire
                    gratuitement</a>
            </p>
        </div>
    </div>
@endsection
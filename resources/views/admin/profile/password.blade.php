@extends('layouts.admin')
@section('title', 'Changer le mot de passe')
@section('page-title', 'Mon compte')
@section('breadcrumb', 'Compte · Mot de passe')

@section('content')
<div class="max-w-lg">

    {{-- Alerte succès --}}
    @if(session('success'))
        <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-4 py-3 mb-6 text-sm">
            <svg class="w-4 h-4 flex-shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">

            <div class="flex items-center gap-3 mb-6 pb-5 border-b border-gray-100">
                <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-gray-900">Modifier le mot de passe</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Choisissez un mot de passe fort d'au moins 8 caractères</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.profile.password.update') }}" class="space-y-5">
                @csrf
                @method('PUT')

                {{-- Mot de passe actuel --}}
                <div>
                    <label for="current_password" class="form-label">
                        Mot de passe actuel <span class="text-red-500">*</span>
                    </label>
                    <input id="current_password" type="password" name="current_password" required
                        class="form-input {{ $errors->has('current_password') ? 'border-red-400 focus:border-red-400 focus:ring-red-200' : '' }}"
                        placeholder="Votre mot de passe actuel">
                    @error('current_password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <hr class="border-gray-100">

                {{-- Nouveau mot de passe --}}
                <div>
                    <label for="password" class="form-label">
                        Nouveau mot de passe <span class="text-red-500">*</span>
                    </label>
                    <input id="password" type="password" name="password" required
                        class="form-input {{ $errors->has('password') ? 'border-red-400 focus:border-red-400 focus:ring-red-200' : '' }}"
                        placeholder="Au moins 8 caractères">
                    @error('password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirmation --}}
                <div>
                    <label for="password_confirmation" class="form-label">
                        Confirmer le nouveau mot de passe <span class="text-red-500">*</span>
                    </label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="form-input"
                        placeholder="Répéter le nouveau mot de passe">
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Enregistrer
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection

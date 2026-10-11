@extends('layouts.app')
@section('title', 'Nous contacter')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Nous contacter</h1>
            <p class="text-sm text-gray-500 mt-1">Envoyez-nous votre demande, un RDV ou toute autre question.</p>
        </div>
        <a href="{{ route('contact.index') }}" class="btn btn-secondary btn-sm gap-1.5">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Mes demandes
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <form method="POST" action="{{ route('contact.store') }}" class="space-y-5">
            @csrf

            <!-- Type -->
            <div>
                <label class="form-label">Type de demande <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-3 gap-3 mt-1">
                    @foreach($types as $type)
                    <label class="relative cursor-pointer">
                        <input type="radio" name="type" value="{{ $type->value }}"
                            {{ old('type', 'autre') === $type->value ? 'checked' : '' }}
                            class="peer sr-only">
                        <div class="flex flex-col items-center gap-2 p-3 rounded-xl border-2 border-gray-200 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 hover:border-gray-300 transition text-center">
                            @if($type->value === 'demande')
                                <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @elseif($type->value === 'rdv')
                                <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            @else
                                <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                            @endif
                            <span class="text-xs font-medium text-gray-700 peer-checked:text-indigo-700">{{ $type->label() }}</span>
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Sujet -->
            <div>
                <label for="subject" class="form-label">Sujet <span class="text-red-500">*</span></label>
                <input id="subject" type="text" name="subject" value="{{ old('subject') }}"
                    class="form-input mt-1 @error('subject') border-red-300 @enderror"
                    placeholder="Ex: Question sur ma formule d'abonnement">
                @error('subject') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Message -->
            <div>
                <label for="message" class="form-label">Message <span class="text-red-500">*</span></label>
                <textarea id="message" name="message" rows="6"
                    class="form-input mt-1 resize-none @error('message') border-red-300 @enderror"
                    placeholder="Décrivez votre demande en détail...">{{ old('message') }}</textarea>
                @error('message') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Infos auto-remplies -->
            <div class="bg-gray-50 rounded-xl p-3 flex items-center gap-3">
                <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-xs font-semibold text-indigo-700">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                </div>
                <div class="text-sm">
                    <p class="font-medium text-gray-800">{{ auth()->user()->name }}</p>
                    <p class="text-gray-500">{{ auth()->user()->email }}</p>
                </div>
            </div>

            <div class="pt-2 flex items-center justify-between">
                <a href="{{ route('catalogue') }}" class="text-sm text-gray-500 hover:text-gray-700 transition">Annuler</a>
                <button type="submit" class="btn btn-primary gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Envoyer le message
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

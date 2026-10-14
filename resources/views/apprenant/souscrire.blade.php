@extends('layouts.app')
@section('title', 'Souscrire — ' . $plan->name)

@section('content')
    <div class="max-w-2xl mx-auto" x-data="{
        operator: 'orange_money',
        phone: '',
        otp: '',
        get ussd() {
            const amount = {{ $plan->price_xof ?? 0 }};
            if (this.operator === 'orange_money') return '*144*2*1*76535063*' + amount + '#';
            return '*555*1*76535063*' + amount + '#';
        }
    }">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-sm text-gray-400 mb-6">
            <a href="{{ route('formules') }}" class="hover:text-[#1B4FD8] transition">Formules</a>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-gray-600 font-medium">{{ $plan->name }}</span>
        </div>

        {{-- Plan recap card --}}
        <div class="bg-gradient-to-br from-[#1e3a6e] to-[#4d8de8] rounded-2xl p-6 text-white mb-6 relative overflow-hidden">
            <div class="absolute -top-8 -right-8 w-32 h-32 rounded-full bg-white/5"></div>
            <div class="absolute -bottom-6 -left-6 w-24 h-24 rounded-full bg-white/5"></div>
            <div class="relative">
                <p class="text-blue-300 text-xs font-semibold uppercase tracking-widest mb-1">Formule choisie</p>
                <h1 class="text-2xl font-bold mb-1">{{ $plan->name }}</h1>
                <p class="text-blue-200 text-sm">{{ $plan->duration_days }} jours d'accès complet</p>

                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach ($plan->features ?? [] as $feature)
                        <span
                            class="inline-flex items-center gap-1 bg-white/10 text-blue-100 text-xs px-3 py-1 rounded-full">
                            <svg class="w-3 h-3 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            {{ $feature }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Payment form --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

            {{-- Section header --}}
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-lg font-bold text-[#0B1E3E]">Paiement Mobile Money</h2>
                <p class="text-sm text-gray-400 mt-0.5">Réglez directement depuis votre téléphone</p>
            </div>

            <form method="POST" action="{{ route('subscriptions.store') }}" class="p-6 space-y-7">
                @csrf
                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                <input type="hidden" name="operator" :value="operator">

                {{-- Montant --}}
                <div>
                    <p class="text-sm font-medium text-gray-400 mb-1">Montant</p>
                    <p class="text-2xl font-extrabold text-[#0B1E3E]">
                        {{ number_format($plan->price_xof ?? 0, 0, ',', ' ') }}<span
                            class="text-base font-semibold text-gray-500 ml-1">FCFA</span>
                    </p>
                    <p class="text-xs text-gray-400 mt-0.5">≈ {{ number_format($plan->price, 0, '.', ' ') }}
                        {{ $plan->currency }}</p>
                </div>

                {{-- Opérateur --}}
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-3">Opérateur</p>
                    <div class="grid grid-cols-2 gap-3">

                        {{-- Orange Money --}}
                        <label class="relative cursor-pointer">
                            <input type="radio" value="orange_money" x-model="operator" class="sr-only">
                            <div class="border-2 rounded-xl p-4 flex flex-col items-center gap-2 transition-all"
                                :class="operator === 'orange_money'
                                    ?
                                    'border-[#1B4FD8] bg-blue-50' :
                                    'border-gray-200 hover:border-gray-300'">
                                {{-- Orange Money logo placeholder --}}
                                <div class="w-16 h-10 rounded-lg flex items-center justify-center"
                                    style="background:#FF6B00;">
                                    <span
                                        class="text-white font-black text-xs leading-tight text-center">Orange<br>Money</span>
                                </div>
                                <span class="text-sm font-semibold text-gray-700">Orange</span>
                                <div class="absolute top-2 right-2 w-5 h-5 rounded-full bg-[#1B4FD8] flex items-center justify-center transition-opacity"
                                    :class="operator === 'orange_money' ? 'opacity-100' : 'opacity-0'">
                                    <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </div>
                        </label>

                        {{-- Moov Africa --}}
                        <label class="relative cursor-pointer">
                            <input type="radio" value="moov_africa" x-model="operator" class="sr-only">
                            <div class="border-2 rounded-xl p-4 flex flex-col items-center gap-2 transition-all"
                                :class="operator === 'moov_africa'
                                    ?
                                    'border-[#1B4FD8] bg-blue-50' :
                                    'border-gray-200 hover:border-gray-300'">
                                {{-- Moov logo placeholder --}}
                                <div class="w-16 h-10 rounded-lg flex items-center justify-center"
                                    style="background:#0066CC;">
                                    <span
                                        class="text-white font-black text-xs leading-tight text-center">Moov<br>Africa</span>
                                </div>
                                <span class="text-sm font-semibold text-gray-700">Moov</span>
                                <div class="absolute top-2 right-2 w-5 h-5 rounded-full bg-[#1B4FD8] flex items-center justify-center transition-opacity"
                                    :class="operator === 'moov_africa' ? 'opacity-100' : 'opacity-0'">
                                    <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </div>
                        </label>

                    </div>
                </div>

                {{-- Numéro de téléphone --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Numéro de téléphone</label>
                    <div
                        class="flex rounded-xl border border-gray-200 overflow-hidden focus-within:border-[#1B4FD8] focus-within:ring-2 focus-within:ring-[#1B4FD8]/20 transition-all">
                        <div class="flex items-center gap-2 px-3 bg-gray-50 border-r border-gray-200 flex-shrink-0">
                            <span class="text-lg">🇧🇫</span>
                            <span class="text-sm font-semibold text-gray-600">+226</span>
                        </div>
                        <input type="tel" name="phone" x-model="phone"
                            class="flex-1 px-4 py-3 text-sm outline-none bg-transparent placeholder-gray-400"
                            placeholder="XX XX XX XX" maxlength="11" required>
                    </div>
                    @error('phone')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- OTP --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Réf paiement</label>
                    <input type="text" name="otp" x-model="otp"
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm outline-none
                              focus:border-[#1B4FD8] focus:ring-2 focus:ring-[#1B4FD8]/20 transition-all
                              placeholder-gray-400 tracking-widest font-mono"
                        placeholder="Entrez votre référence de paiement" maxlength="10" required>
                    <p class="text-xs text-gray-500 mt-2">
                        Composez
                        <span class="font-semibold text-[#1B4FD8]" x-text="ussd"></span>
                        pour pour effectuer le paiement.
                    </p>
                    @error('otp')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Errors généraux --}}
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                        @foreach ($errors->all() as $error)
                            <p class="text-sm text-red-600">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                {{-- Actions --}}
                <div class="flex gap-3 pt-2">
                    <a href="{{ route('formules') }}"
                        class="flex-1 flex items-center justify-center px-4 py-3 rounded-xl border-2 border-gray-200
                          text-sm font-semibold text-gray-600 hover:border-gray-300 transition">
                        Retour
                    </a>
                    <button type="submit"
                        class="flex-1 flex items-center justify-center gap-2 px-4 py-3 rounded-xl
                               bg-[#1B4FD8] hover:bg-[#1740b0] text-white font-bold text-sm transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Valider ma souscription
                    </button>
                </div>

            </form>
        </div>

        {{-- Security note --}}
        <p class="text-center text-xs text-gray-400 mt-4 flex items-center justify-center gap-1.5">
            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            Vos informations sont sécurisées. L'abonnement est activé après vérification par notre équipe.
        </p>

    </div>
@endsection

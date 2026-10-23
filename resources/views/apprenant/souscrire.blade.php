@extends('layouts.app')
@section('title', 'Souscrire — ' . $plan->name)

@section('content')
<div class="max-w-xl mx-auto" x-data="{
    phone: '',
    txn: '',
    step: 1,
    copied: false,
    ussd: '*144*2*1*46788578*{{ $plan->price_xof ?? 0 }}#',
    copyUssd() {
        navigator.clipboard.writeText(this.ussd).then(() => { this.copied = true; setTimeout(() => this.copied = false, 2000); });
    }
}">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <a href="{{ route('formules') }}" class="hover:text-[#1B4FD8] transition">Formules</a>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
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
                    <span class="inline-flex items-center gap-1 bg-white/10 text-blue-100 text-xs px-3 py-1 rounded-full">
                        <svg class="w-3 h-3 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        {{ $feature }}
                    </span>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Payment card --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

        {{-- Header Orange Money --}}
        <div class="px-6 py-5 border-b border-gray-100 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#FF6B00;">
                <span class="text-white font-black text-[10px] leading-tight text-center">OM</span>
            </div>
            <div>
                <h2 class="text-base font-bold text-[#0B1E3E]">Paiement Orange Money</h2>
                <p class="text-xs text-gray-400">Suivez les 3 étapes ci-dessous</p>
            </div>
            <div class="ml-auto text-right">
                <p class="text-xl font-extrabold text-[#0B1E3E]">{{ number_format($plan->price_xof ?? 0, 0, ',', ' ') }} <span class="text-sm font-semibold text-gray-400">FCFA</span></p>
            </div>
        </div>

        <div class="p-6 space-y-5">

            {{-- Étape 1 --}}
            <div class="rounded-xl border border-orange-200 bg-orange-50/60 p-4">
                <div class="flex items-start gap-3">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0 text-white text-xs font-bold" style="background:#FF6B00;">1</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 mb-2">Composez le code USSD</p>
                        <div class="flex items-center gap-2">
                            <code class="flex-1 block bg-white border border-orange-200 rounded-lg px-4 py-2.5 text-base font-mono font-bold text-[#FF6B00] tracking-wider text-center select-all" x-text="ussd"></code>
                            <button type="button" @click="copyUssd()"
                                class="flex-shrink-0 flex items-center gap-1.5 px-3 py-2.5 rounded-lg text-xs font-semibold transition-all"
                                :class="copied ? 'bg-emerald-100 text-emerald-700' : 'bg-white border border-orange-200 text-orange-600 hover:bg-orange-50'">
                                <svg x-show="!copied" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <svg x-show="copied" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span x-text="copied ? 'Copié !' : 'Copier'"></span>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Ce code envoie <strong>{{ number_format($plan->price_xof ?? 0, 0, ',', ' ') }} FCFA</strong> au numéro <strong>+226 46 78 85 78</strong> (Expertise Marchés Publics).</p>
                    </div>
                </div>
            </div>

            {{-- Étape 2 --}}
            <div class="rounded-xl border border-gray-200 bg-gray-50/50 p-4">
                <div class="flex items-start gap-3">
                    <div class="w-7 h-7 rounded-full bg-gray-400 flex items-center justify-center flex-shrink-0 text-white text-xs font-bold">2</div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800 mb-1">Validez avec votre code PIN Orange Money</p>
                        <p class="text-xs text-gray-500">Composez le code, confirmez le montant et le destinataire, puis saisissez votre PIN pour finaliser le transfert.</p>
                    </div>
                </div>
            </div>

            {{-- Étape 3 --}}
            <div class="rounded-xl border border-blue-200 bg-blue-50/40 p-4">
                <div class="flex items-start gap-3">
                    <div class="w-7 h-7 rounded-full bg-[#1B4FD8] flex items-center justify-center flex-shrink-0 text-white text-xs font-bold">3</div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-800 mb-0.5">Notez l'ID de transaction reçu par SMS</p>
                        <p class="text-xs text-gray-500 mb-3">Après paiement, Orange Money vous envoie un SMS de confirmation contenant un <strong>identifiant de transaction</strong> (ex. : <em class="font-mono">MP241023.1234.A56789</em>). Saisissez-le ci-dessous.</p>
                        <div class="p-3 rounded-lg bg-white border border-blue-100 text-xs text-blue-700">
                            <span class="font-semibold">Exemple de SMS reçu :</span><br>
                            <span class="font-mono">"Vous avez envoyé 5000 FCFA à +22646788578. ID: <strong>MP241023.1234.A56789</strong>. Nouveau solde : XXXX FCFA."</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Errors --}}
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                    @foreach ($errors->all() as $error)
                        <p class="text-sm text-red-600">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('subscriptions.store') }}" class="space-y-4 pt-1">
                @csrf
                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                <input type="hidden" name="operator" value="orange_money">

                {{-- Numéro de téléphone --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Numéro Orange Money utilisé <span class="text-red-500">*</span>
                    </label>
                    <div class="flex rounded-xl border border-gray-200 overflow-hidden focus-within:border-[#FF6B00] focus-within:ring-2 focus-within:ring-[#FF6B00]/20 transition-all">
                        <div class="flex items-center gap-2 px-3 bg-gray-50 border-r border-gray-200 flex-shrink-0">
                            <span class="text-lg">🇧🇫</span>
                            <span class="text-sm font-semibold text-gray-600">+226</span>
                        </div>
                        <input type="tel" name="phone" x-model="phone" value="{{ old('phone') }}"
                            class="flex-1 px-4 py-3 text-sm outline-none bg-transparent placeholder-gray-400"
                            placeholder="XX XX XX XX" maxlength="11" required>
                    </div>
                    @error('phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- ID transaction --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        ID de transaction (SMS de confirmation) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="otp" x-model="txn" value="{{ old('otp') }}"
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm outline-none
                               focus:border-[#FF6B00] focus:ring-2 focus:ring-[#FF6B00]/20 transition-all
                               placeholder-gray-400 tracking-wider font-mono uppercase"
                        placeholder="Ex : MP241023.1234.A56789"
                        required>
                    <p class="text-xs text-gray-400 mt-1">Copiez l'identifiant exactement tel qu'il apparaît dans le SMS Orange Money.</p>
                    @error('otp') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Actions --}}
                <div class="flex gap-3 pt-2">
                    <a href="{{ route('formules') }}"
                        class="flex-1 flex items-center justify-center px-4 py-3 rounded-xl border-2 border-gray-200
                               text-sm font-semibold text-gray-600 hover:border-gray-300 transition">
                        Retour
                    </a>
                    <button type="submit"
                        :disabled="!phone || !txn"
                        class="flex-1 flex items-center justify-center gap-2 px-4 py-3 rounded-xl
                               text-white font-bold text-sm transition disabled:opacity-50 disabled:cursor-not-allowed"
                        style="background:#FF6B00;">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Soumettre ma demande
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Security note --}}
    <p class="text-center text-xs text-gray-400 mt-4 flex items-center justify-center gap-1.5">
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        Votre abonnement sera activé après vérification de l'ID de transaction par notre équipe.
    </p>

</div>
@endsection

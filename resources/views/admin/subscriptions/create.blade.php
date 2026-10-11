@extends('layouts.admin')
@section('title', 'Nouvel abonnement')
@section('page-title', 'Créer un abonnement manuellement')
@section('breadcrumb')
    <a href="{{ route('admin.subscriptions.index') }}" class="hover:text-gray-600">Abonnements</a> / Nouveau
@endsection
@section('header-actions')
    <a href="{{ route('admin.subscriptions.index') }}" class="btn-secondary btn-sm">← Retour</a>
@endsection

@section('content')
<div class="max-w-2xl">
    <div class="card card-body">
        <p class="text-sm text-gray-500 mb-6">Créez un abonnement manuellement pour un apprenant (ex : paiement en espèces ou mobile money vérifié).</p>
        <form method="POST" action="{{ route('admin.subscriptions.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="form-label">Apprenant <span class="text-red-500">*</span></label>
                <select name="user_id" class="form-input" required>
                    <option value="">Sélectionner un apprenant...</option>
                    @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }} — {{ $u->email }}</option>
                    @endforeach
                </select>
                @error('user_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">Formule <span class="text-red-500">*</span></label>
                <select name="plan_id" class="form-input" required>
                    <option value="">Sélectionner une formule...</option>
                    @foreach($plans as $p)
                    <option value="{{ $p->id }}" {{ old('plan_id') == $p->id ? 'selected' : '' }}>
                        {{ $p->name }} — {{ number_format($p->price, 0, '.', ' ') }} {{ $p->currency }} / {{ $p->duration_days }} jours
                    </option>
                    @endforeach
                </select>
                @error('plan_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Mode de paiement</label>
                    <select name="payment_method" class="form-input">
                        @foreach(['orange_money' => 'Orange Money', 'free_money' => 'Free Money', 'wave' => 'Wave', 'carte_bancaire' => 'Carte bancaire', 'especes' => 'Espèces'] as $val => $lab)
                        <option value="{{ $val }}" {{ old('payment_method') === $val ? 'selected' : '' }}>{{ $lab }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Référence de transaction</label>
                    <input type="text" name="payment_reference" value="{{ old('payment_reference') }}"
                        class="form-input" placeholder="TXN...">
                </div>
            </div>
            <div>
                <label class="form-label">Date de début <span class="text-gray-400 font-normal">(optionnel, aujourd'hui par défaut)</span></label>
                <input type="date" name="starts_at" value="{{ old('starts_at') }}" class="form-input">
            </div>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="activate" value="1" {{ old('activate', '1') ? 'checked' : '' }}
                    class="w-4 h-4 text-indigo-600 border-gray-300 rounded">
                <span class="text-sm font-medium text-gray-700">Activer immédiatement (paiement confirmé)</span>
            </label>
            <button type="submit" class="btn-primary w-full justify-center py-2.5">Créer l'abonnement</button>
        </form>
    </div>
</div>
@endsection

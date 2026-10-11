@extends('layouts.admin')
@section('title', 'Formules d\'abonnement')
@section('page-title', 'Formules d\'abonnement')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form -->
    <div class="card card-body h-fit" x-data="{ features: {{ json_encode(old('features', $editPlan->features ?? [''])) }} }">
        <h3 class="font-semibold text-gray-800 mb-5">{{ isset($editPlan) ? 'Modifier' : 'Créer' }} une formule</h3>
        <form method="POST"
            action="{{ isset($editPlan) ? route('admin.plans.update', $editPlan) : route('admin.plans.store') }}"
            class="space-y-4">
            @csrf
            @if(isset($editPlan))@method('PUT')@endif
            <div>
                <label class="form-label">Nom <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $editPlan->name ?? '') }}"
                    class="form-input" required placeholder="Ex: Mensuel">
                @error('name')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="form-label">Prix <span class="text-red-500">*</span></label>
                    <input type="number" name="price" value="{{ old('price', $editPlan->price ?? '') }}"
                        class="form-input" step="0.01" min="0" required placeholder="5000">
                    @error('price')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Durée (jours) <span class="text-red-500">*</span></label>
                    <input type="number" name="duration_days" value="{{ old('duration_days', $editPlan->duration_days ?? '') }}"
                        class="form-input" min="1" required placeholder="30">
                    @error('duration_days')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>
            <div>
                <label class="form-label">Description</label>
                <textarea name="description" rows="2" class="form-input resize-none"
                    placeholder="Description...">{{ old('description', $editPlan->description ?? '') }}</textarea>
            </div>
            <!-- Features -->
            <div>
                <label class="form-label">Avantages</label>
                <div class="space-y-2">
                    <template x-for="(feat, i) in features" :key="i">
                        <div class="flex gap-2">
                            <input type="text" :name="'features[' + i + ']'" x-model="features[i]"
                                class="form-input" placeholder="Ex: Accès illimité">
                            <button type="button" @click="features.splice(i, 1)"
                                class="text-gray-400 hover:text-red-500 flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </template>
                    <button type="button" @click="features.push('')"
                        class="text-sm text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Ajouter un avantage
                    </button>
                </div>
            </div>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $editPlan->is_active ?? true) ? 'checked' : '' }}
                    class="w-4 h-4 text-indigo-600 border-gray-300 rounded">
                <span class="text-sm font-medium text-gray-700">Formule active</span>
            </label>
            <div class="flex gap-3">
                <button type="submit" class="btn-primary flex-1 justify-center">
                    {{ isset($editPlan) ? 'Enregistrer' : 'Créer' }}
                </button>
                @if(isset($editPlan))
                <a href="{{ route('admin.plans.index') }}" class="btn-secondary">Annuler</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Plans list -->
    <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-5 content-start">
        @forelse($plans as $plan)
        <div class="card p-5 flex flex-col gap-3">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <h3 class="font-bold text-gray-900">{{ $plan->name }}</h3>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $plan->duration_days }} jours</p>
                </div>
                <span class="{{ $plan->is_active ? 'badge-green' : 'badge-gray' }}">
                    {{ $plan->is_active ? 'Actif' : 'Inactif' }}
                </span>
            </div>
            <p class="text-2xl font-extrabold text-gray-900">
                {{ number_format($plan->price, 0, '.', ' ') }}
                <span class="text-sm font-normal text-gray-400">{{ $plan->currency }}</span>
            </p>
            @if($plan->features)
            <ul class="space-y-1.5 flex-1">
                @foreach($plan->features as $feat)
                <li class="flex items-start gap-1.5 text-xs text-gray-600">
                    <svg class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ $feat }}
                </li>
                @endforeach
            </ul>
            @endif
            <div class="flex items-center gap-2 pt-3 border-t border-gray-100 mt-auto">
                <span class="text-xs text-gray-500">{{ $plan->subscriptions_count ?? 0 }} abonnement(s)</span>
                <div class="flex gap-1 ml-auto">
                    <a href="{{ route('admin.plans.edit', $plan) }}" class="btn-secondary btn-sm">Modifier</a>
                    <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}" onsubmit="return confirm('Supprimer cette formule ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger btn-sm">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="sm:col-span-2 text-center py-12 text-gray-400">Aucune formule créée</div>
        @endforelse
    </div>
</div>
@endsection

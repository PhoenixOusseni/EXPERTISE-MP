@extends('layouts.admin')
@section('title', isset($publicite) ? 'Modifier la publicité' : 'Nouvelle publicité')
@section('page-title', isset($publicite) ? 'Modifier une publicité' : 'Nouvelle publicité')
@section('breadcrumb')
    <a href="{{ route('admin.publicites.index') }}" class="hover:text-gray-600">Publicités</a> /
    {{ isset($publicite) ? ($publicite->title ?? 'Modifier') : 'Nouvelle' }}
@endsection

@section('header-actions')
    <a href="{{ route('admin.publicites.index') }}" class="btn-secondary btn-sm">← Retour</a>
@endsection

@section('content')
    <form method="POST"
        action="{{ isset($publicite) ? route('admin.publicites.update', $publicite) : route('admin.publicites.store') }}"
        enctype="multipart/form-data"
        x-data="{ imgPreview: '{{ isset($publicite) ? Storage::disk('public')->url($publicite->image_path) : '' }}', isActive: {{ old('is_active', $publicite->is_active ?? true) ? 'true' : 'false' }} }">
        @csrf
        @if (isset($publicite)) @method('PUT') @endif

        @if ($errors->any())
            <div class="alert-error mb-4">
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-5">
                <div class="card card-body space-y-4">
                    <div>
                        <label class="form-label">Titre (optionnel)</label>
                        <input type="text" name="title" value="{{ old('title', $publicite->title ?? '') }}"
                            class="form-input" placeholder="Texte affiché sous l'image...">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Date de début <span class="text-red-500">*</span></label>
                            <input type="date" name="start_date"
                                value="{{ old('start_date', isset($publicite) ? $publicite->start_date->format('Y-m-d') : '') }}"
                                class="form-input" required>
                            @error('start_date') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label">Date de fin <span class="text-red-500">*</span></label>
                            <input type="date" name="end_date"
                                value="{{ old('end_date', isset($publicite) ? $publicite->end_date->format('Y-m-d') : '') }}"
                                class="form-input" required>
                            @error('end_date') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 items-end">
                        <div>
                            <label class="form-label">Ordre d'affichage</label>
                            <input type="number" name="sort_order" min="0"
                                value="{{ old('sort_order', $publicite->sort_order ?? 0) }}"
                                class="form-input">
                        </div>
                        <div>
                            <label class="flex items-center justify-between cursor-pointer">
                                <span class="form-label mb-0">Activée</span>
                                <div @click="isActive = !isActive">
                                    <input type="hidden" name="is_active" :value="isActive ? 1 : 0">
                                    <div :class="isActive ? 'bg-indigo-600' : 'bg-gray-200'"
                                        class="w-11 h-6 rounded-full transition-colors">
                                        <div :class="isActive ? 'translate-x-5' : 'translate-x-0.5'"
                                            class="w-5 h-5 bg-white rounded-full shadow transition-transform mt-0.5 ml-0.5"></div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-5">
                {{-- Image --}}
                <div class="card card-body">
                    <h3 class="font-semibold text-gray-800 mb-4">Image <span class="text-red-500">*</span></h3>
                    <template x-if="imgPreview">
                        <img :src="imgPreview" class="w-full aspect-video object-cover rounded-lg mb-3" alt="">
                    </template>
                    <input type="file" name="image" id="img-input" accept="image/*" class="sr-only"
                        @change="imgPreview = URL.createObjectURL($event.target.files[0])">
                    <label for="img-input"
                        class="flex items-center gap-2 border border-dashed border-gray-200 rounded-lg px-4 py-3 cursor-pointer hover:border-indigo-400 hover:bg-indigo-50/30 transition">
                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-sm text-gray-500">{{ isset($publicite) ? 'Changer l\'image' : 'Choisir une image' }}</span>
                    </label>
                    @error('image') <p class="form-error mt-2">{{ $message }}</p> @enderror
                    <p class="text-xs text-gray-400 mt-2">Formats : JPG, PNG, WebP — max 5 Mo<br>Ratio recommandé : 16/9 ou bannière horizontale</p>
                </div>

                <button type="submit" class="btn-primary w-full justify-center py-2.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ isset($publicite) ? 'Enregistrer' : 'Créer la publicité' }}
                </button>
            </div>
        </div>
    </form>
@endsection

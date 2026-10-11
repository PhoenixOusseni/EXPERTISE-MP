@extends('layouts.admin')
@section('title', isset($video) ? 'Modifier la vidéo' : 'Nouvelle vidéo')
@section('page-title', isset($video) ? 'Modifier une vidéo' : 'Ajouter une vidéo')
@section('breadcrumb')
    <a href="{{ route('admin.videos.index') }}" class="hover:text-gray-600">Vidéos</a> /
    {{ isset($video) ? $video->title : 'Nouvelle vidéo' }}
@endsection

@section('header-actions')
    <a href="{{ route('admin.videos.index') }}" class="btn-secondary btn-sm">← Retour</a>
@endsection

@section('content')
<form method="POST"
    action="{{ isset($video) ? route('admin.videos.update', $video) : route('admin.videos.store') }}"
    enctype="multipart/form-data"
    x-data="{
        sourceType: '{{ old('source_type', $video->source_type->value ?? 'upload') }}',
        isPublished: {{ old('is_published', $video->is_published ?? false) ? 'true' : 'false' }},
        isFree: {{ old('is_free', $video->is_free ?? false) ? 'true' : 'false' }},
        fileName: '',
        thumbName: ''
    }">
    @csrf
    @if(isset($video))@method('PUT')@endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main info -->
        <div class="lg:col-span-2 space-y-5">
            <div class="card card-body">
                <h3 class="font-semibold text-gray-800 mb-5">Informations générales</h3>
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Titre <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $video->title ?? '') }}"
                            class="form-input" placeholder="Titre de la vidéo" required>
                        @error('title')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="4"
                            class="form-input resize-none"
                            placeholder="Description de la vidéo...">{{ old('description', $video->description ?? '') }}</textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Catégorie</label>
                            <select name="category_id" class="form-input">
                                <option value="">Sans catégorie</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $video->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Niveau</label>
                            <select name="level" class="form-input">
                                <option value="">Non défini</option>
                                @foreach(['debutant' => 'Débutant', 'intermediaire' => 'Intermédiaire', 'avance' => 'Avancé'] as $val => $label)
                                <option value="{{ $val }}" {{ old('level', $video->level ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Durée (secondes)</label>
                        <input type="number" name="duration" value="{{ old('duration', $video->duration ?? '') }}"
                            class="form-input" placeholder="Ex: 3600 pour 1h00">
                    </div>
                </div>
            </div>

            <!-- Source -->
            <div class="card card-body">
                <h3 class="font-semibold text-gray-800 mb-5">Source vidéo</h3>
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Type de source</label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                            @foreach([
                                ['value' => 'upload', 'label' => 'Fichier', 'icon' => 'M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12'],
                                ['value' => 'youtube', 'label' => 'YouTube', 'icon' => 'M15 10l4.553-2.069A1 1 0 0121 8.867v6.266a1 1 0 01-1.447.902L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z'],
                                ['value' => 'vimeo', 'label' => 'Vimeo', 'icon' => 'M15 10l4.553-2.069A1 1 0 0121 8.867v6.266a1 1 0 01-1.447.902L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z'],
                                ['value' => 'external', 'label' => 'Lien', 'icon' => 'M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1'],
                            ] as $src)
                            <button type="button"
                                @click="sourceType = '{{ $src['value'] }}'"
                                :class="sourceType === '{{ $src['value'] }}' ? 'border-indigo-600 bg-indigo-50 text-indigo-700' : 'border-gray-200 text-gray-600 hover:border-gray-300'"
                                class="flex flex-col items-center gap-1.5 p-3 rounded-xl border-2 transition text-xs font-medium">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $src['icon'] }}"/>
                                </svg>
                                {{ $src['label'] }}
                            </button>
                            @endforeach
                        </div>
                        <input type="hidden" name="source_type" :value="sourceType">
                    </div>

                    <!-- File upload -->
                    <div x-show="sourceType === 'upload'" x-cloak>
                        @if(isset($video) && $video->file_path)
                        <div class="mb-2 text-xs text-gray-500">
                            Fichier actuel : <span class="font-medium text-gray-700">{{ basename($video->file_path) }}</span>
                            <span class="text-gray-400">(laisser vide pour conserver)</span>
                        </div>
                        @endif
                        <label class="form-label">Fichier vidéo {{ !isset($video) ? '(requis)' : '' }}</label>
                        <div class="relative">
                            <input type="file" name="file" id="file-input" accept="video/*"
                                class="sr-only" @change="fileName = $event.target.files[0]?.name || ''">
                            <label for="file-input"
                                class="flex items-center gap-3 border-2 border-dashed border-gray-200 rounded-xl p-5 cursor-pointer hover:border-indigo-400 hover:bg-indigo-50/30 transition">
                                <svg class="w-8 h-8 text-gray-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-700" x-text="fileName || 'Cliquer pour sélectionner un fichier vidéo'"></p>
                                    <p class="text-xs text-gray-400">MP4, MKV, AVI, MOV, WebM... max 2 Go</p>
                                </div>
                            </label>
                        </div>
                        @error('file')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <!-- External URL -->
                    <div x-show="sourceType !== 'upload'" x-cloak>
                        <label class="form-label">URL de la vidéo <span class="text-red-500">*</span></label>
                        <input type="url" name="external_url"
                            value="{{ old('external_url', $video->external_url ?? '') }}"
                            class="form-input"
                            :placeholder="sourceType === 'youtube' ? 'https://www.youtube.com/watch?v=...' : sourceType === 'vimeo' ? 'https://vimeo.com/...' : 'https://...'">
                        @error('external_url')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-5">
            <!-- Publish -->
            <div class="card card-body">
                <h3 class="font-semibold text-gray-800 mb-4">Publication</h3>
                <div class="space-y-4">
                    <label class="flex items-center justify-between cursor-pointer">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Publier</p>
                            <p class="text-xs text-gray-400">Rendre visible aux apprenants</p>
                        </div>
                        <div class="relative" @click="isPublished = !isPublished">
                            <input type="hidden" name="is_published" :value="isPublished ? 1 : 0">
                            <div :class="isPublished ? 'bg-indigo-600' : 'bg-gray-200'"
                                class="w-11 h-6 rounded-full transition-colors">
                                <div :class="isPublished ? 'translate-x-5' : 'translate-x-0.5'"
                                    class="w-5 h-5 bg-white rounded-full shadow transition-transform mt-0.5 ml-0.5"></div>
                            </div>
                        </div>
                    </label>
                    <label class="flex items-center justify-between cursor-pointer">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Gratuit</p>
                            <p class="text-xs text-gray-400">Accessible sans abonnement</p>
                        </div>
                        <div class="relative" @click="isFree = !isFree">
                            <input type="hidden" name="is_free" :value="isFree ? 1 : 0">
                            <div :class="isFree ? 'bg-emerald-500' : 'bg-gray-200'"
                                class="w-11 h-6 rounded-full transition-colors">
                                <div :class="isFree ? 'translate-x-5' : 'translate-x-0.5'"
                                    class="w-5 h-5 bg-white rounded-full shadow transition-transform mt-0.5 ml-0.5"></div>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Thumbnail -->
            <div class="card card-body">
                <h3 class="font-semibold text-gray-800 mb-4">Miniature</h3>
                @if(isset($video) && $video->thumbnail_path)
                <img src="{{ Storage::disk('public')->url($video->thumbnail_path) }}"
                    class="w-full aspect-video object-cover rounded-lg mb-3" alt="">
                @endif
                <input type="file" name="thumbnail" id="thumb-input" accept="image/*"
                    class="sr-only" @change="thumbName = $event.target.files[0]?.name || ''">
                <label for="thumb-input"
                    class="flex items-center gap-2 border border-dashed border-gray-200 rounded-lg px-4 py-3 cursor-pointer hover:border-indigo-400 hover:bg-indigo-50/30 transition">
                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="text-sm text-gray-500" x-text="thumbName || '{{ isset($video) && $video->thumbnail_path ? 'Changer la miniature' : 'Choisir une image' }}'"></span>
                </label>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn-primary w-full justify-center py-2.5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ isset($video) ? 'Enregistrer les modifications' : 'Créer la vidéo' }}
            </button>
        </div>
    </div>
</form>
@endsection

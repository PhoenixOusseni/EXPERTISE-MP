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
    <form method="POST" action="{{ isset($video) ? route('admin.videos.update', $video) : route('admin.videos.store') }}"
        enctype="multipart/form-data" x-data="{
            sourceType: '{{ old('source_type', $video->source_type->value ?? 'upload') }}',
            fileName: '',
            isPublished: {{ old('is_published', $video->is_published ?? false) ? 'true' : 'false' }},
            isFree: {{ old('is_free', $video->is_free ?? false) ? 'true' : 'false' }},
            fileName: '',
            thumbName: ''
        }">
        @csrf
        @if (isset($video))
            @method('PUT')
        @endif

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
                            @error('title')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="4" class="form-input resize-none" placeholder="Description de la vidéo...">{{ old('description', $video->description ?? '') }}</textarea>
                        </div>
                        <div>
                            <label class="form-label">Groupe cible <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-2 gap-3 mt-1">
                                @foreach (\App\Enums\LearnerProfile::cases() as $p)
                                    @if($p->value === 'autre') @continue @endif
                                    <label
                                        class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer transition
                                    {{ old('audience', $video->audience?->value ?? '') === $p->value ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-indigo-300' }}">
                                        <input type="radio" name="audience" value="{{ $p->value }}"
                                            {{ old('audience', $video->audience?->value ?? '') === $p->value ? 'checked' : '' }}
                                            class="text-indigo-600 focus:ring-indigo-500" required>
                                        <span class="text-sm font-medium text-gray-700">{{ $p->label() }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('audience')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Catégorie</label>
                                <select name="category_id" class="form-input">
                                    <option value="">Sans catégorie</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ old('category_id', $video->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Niveau</label>
                                <select name="level" class="form-input">
                                    <option value="">Non défini</option>
                                    @foreach (['debutant' => 'Débutant', 'intermediaire' => 'Intermédiaire', 'avance' => 'Avancé'] as $val => $label)
                                        <option value="{{ $val }}"
                                            {{ old('level', $video->level ?? '') === $val ? 'selected' : '' }}>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Source -->
                <div class="card card-body">
                    <h3 class="font-semibold text-gray-800 mb-5">Source vidéo</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="form-label">Type de source</label>
                            <div class="grid grid-cols-3 gap-2">
                                @foreach ([
                                    ['value' => 'upload',   'label' => 'Fichier',  'icon' => 'M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12'],
                                    ['value' => 'facebook', 'label' => 'Facebook', 'icon' => 'M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z'],
                                    ['value' => 'tiktok',   'label' => 'TikTok',   'icon' => 'M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 00-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0020 4.77 5.07 5.07 0 0019.91 1S18.73.65 16 2.48a13.38 13.38 0 00-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 005 4.77a5.44 5.44 0 00-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 009 18.13V22'],
                                ] as $src)
                                    <button type="button" @click="sourceType = '{{ $src['value'] }}'"
                                        :class="sourceType === '{{ $src['value'] }}' ?
                                            'border-indigo-600 bg-indigo-50 text-indigo-700' :
                                            'border-gray-200 text-gray-600 hover:border-gray-300'"
                                        class="flex flex-col items-center gap-1.5 p-3 rounded-xl border-2 transition text-xs font-medium">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $src['icon'] }}" />
                                        </svg>
                                        {{ $src['label'] }}
                                    </button>
                                @endforeach
                            </div>
                            <input type="hidden" name="source_type" :value="sourceType">
                        </div>

                        <!-- Upload fichier -->
                        <div x-show="sourceType === 'upload'" x-cloak>
                            @if (isset($video) && $video->file_path)
                                <div class="mb-2 text-xs text-gray-500">
                                    Fichier actuel : <span class="font-medium text-gray-700">{{ basename($video->file_path) }}</span>
                                    <span class="text-gray-400">(laisser vide pour conserver)</span>
                                </div>
                            @endif
                            <label class="form-label">Fichier vidéo {{ !isset($video) ? '(requis)' : '' }}</label>
                            <div class="relative">
                                <input type="file" name="file" id="file-input" accept="video/*" class="sr-only"
                                    @change="fileName = $event.target.files[0]?.name || ''">
                                <label for="file-input"
                                    class="flex items-center gap-3 border-2 border-dashed border-gray-200 rounded-xl p-5 cursor-pointer hover:border-indigo-400 hover:bg-indigo-50/30 transition">
                                    <svg class="w-8 h-8 text-gray-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-700" x-text="fileName || 'Cliquer pour sélectionner un fichier vidéo'"></p>
                                        <p class="text-xs text-gray-400">MP4, MKV, AVI, MOV, WebM... max 2 Go</p>
                                    </div>
                                </label>
                            </div>
                            @error('file')<p class="form-error">{{ $message }}</p>@enderror
                        </div>

                        <!-- URL externe -->
                        <div x-show="sourceType !== 'upload'" x-cloak>
                            <label class="form-label">URL de la vidéo <span class="text-red-500">*</span></label>
                            <input type="url" name="external_url"
                                value="{{ old('external_url', $video->external_url ?? '') }}" class="form-input"
                                :placeholder="sourceType === 'facebook' ? 'https://www.facebook.com/watch?v=...' : 'https://www.tiktok.com/@compte/video/...'">
                            @error('external_url')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
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
                                        class="w-5 h-5 bg-white rounded-full shadow transition-transform mt-0.5 ml-0.5">
                                    </div>
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
                                        class="w-5 h-5 bg-white rounded-full shadow transition-transform mt-0.5 ml-0.5">
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Thumbnail -->
                <div class="card card-body">
                    <h3 class="font-semibold text-gray-800 mb-4">Miniature</h3>

                    {{-- Aperçu (existant ou auto-capturé) --}}
                    <img id="thumb-preview"
                        src="{{ isset($video) && $video->thumbnail_path ? Storage::disk('public')->url($video->thumbnail_path) : '' }}"
                        alt=""
                        class="w-full aspect-video object-cover rounded-lg mb-3 {{ isset($video) && $video->thumbnail_path ? '' : 'hidden' }}">

                    {{-- Indicateur de capture en cours --}}
                    <div id="thumb-capturing" class="hidden mb-3 flex items-center gap-2 text-xs text-indigo-600 bg-indigo-50 rounded-lg px-3 py-2">
                        <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Capture du premier frame en cours…
                    </div>

                    {{-- Bouton de capture depuis vidéo existante (édition uniquement) --}}
                    @if (isset($video) && $video->isUploaded() && $video->file_path)
                        <button type="button" id="btn-capture-existing"
                            class="w-full mb-3 flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg border border-indigo-200 bg-indigo-50 text-indigo-700 text-sm font-medium hover:bg-indigo-100 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            Capturer depuis la vidéo
                        </button>
                        <video id="existing-video-el" class="hidden"
                            src="{{ route('admin.videos.preview-stream', $video) }}"
                            preload="none" muted playsinline crossorigin="anonymous"></video>
                    @endif

                    <input type="file" name="thumbnail" id="thumb-input" accept="image/*" class="sr-only"
                        @change="thumbName = $event.target.files[0]?.name || ''">
                    <label for="thumb-input"
                        class="flex items-center gap-2 border border-dashed border-gray-200 rounded-lg px-4 py-3 cursor-pointer hover:border-indigo-400 hover:bg-indigo-50/30 transition">
                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-sm text-gray-500"
                            x-text="thumbName || '{{ isset($video) && $video->thumbnail_path ? 'Changer la miniature' : 'Choisir une image' }}'"></span>
                    </label>
                    <p class="text-xs text-gray-400 mt-1.5">Laisser vide → miniature extraite automatiquement lors du prochain upload.</p>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn-primary w-full justify-center py-2.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ isset($video) ? 'Enregistrer les modifications' : 'Créer la vidéo' }}
                </button>
            </div>
        </div>
    </form>

@push('scripts')
<script>
(function () {
    const videoInput   = document.getElementById('file-input');
    const thumbInput   = document.getElementById('thumb-input');
    const thumbPreview = document.getElementById('thumb-preview');
    const capturing    = document.getElementById('thumb-capturing');

    if (!thumbInput) return;

    // ── Utilitaire : capture un frame d'un élément <video> et l'injecte comme miniature ──
    function captureFrameFromVideoEl(videoEl, objectUrl) {
        capturing.classList.remove('hidden');

        function doCapture() {
            const canvas  = document.createElement('canvas');
            const MAX_W   = 1280;
            const ratio   = Math.min(1, MAX_W / (videoEl.videoWidth || MAX_W));
            canvas.width  = Math.round((videoEl.videoWidth  || 1280) * ratio);
            canvas.height = Math.round((videoEl.videoHeight || 720)  * ratio);

            canvas.getContext('2d').drawImage(videoEl, 0, 0, canvas.width, canvas.height);

            canvas.toBlob(function (blob) {
                if (!blob) { capturing.classList.add('hidden'); return; }

                try {
                    const dt = new DataTransfer();
                    dt.items.add(new File([blob], 'miniature-auto.jpg', { type: 'image/jpeg' }));
                    thumbInput.files = dt.files;
                    thumbInput.dispatchEvent(new Event('change', { bubbles: true }));
                } catch (_) {}

                thumbPreview.src = URL.createObjectURL(blob);
                thumbPreview.classList.remove('hidden');
                capturing.classList.add('hidden');

                if (objectUrl) URL.revokeObjectURL(objectUrl);
            }, 'image/jpeg', 0.88);
        }

        videoEl.addEventListener('seeked', doCapture, { once: true });
        videoEl.addEventListener('error', () => capturing.classList.add('hidden'), { once: true });

        // Seek après chargement des métadonnées
        if (videoEl.readyState >= 1) {
            videoEl.currentTime = Math.min(2, (videoEl.duration || 10) * 0.1);
        } else {
            videoEl.addEventListener('loadedmetadata', () => {
                videoEl.currentTime = Math.min(2, videoEl.duration * 0.1);
            }, { once: true });
            videoEl.load();
        }
    }

    // ── Aperçu manuel ──
    thumbInput.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        thumbPreview.src = URL.createObjectURL(file);
        thumbPreview.classList.remove('hidden');
    });

    // ── Auto-capture lors d'un nouvel upload de fichier vidéo ──
    if (videoInput) {
        videoInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file || !file.type.startsWith('video/')) return;
            if (thumbInput.files.length > 0) return; // miniature manuelle prioritaire

            const objectUrl = URL.createObjectURL(file);
            const videoEl   = document.createElement('video');
            videoEl.muted       = true;
            videoEl.preload     = 'metadata';
            videoEl.playsInline = true;
            videoEl.src         = objectUrl;
            captureFrameFromVideoEl(videoEl, objectUrl);
        });
    }

    // ── Capture depuis la vidéo déjà stockée (bouton édition) ──
    const btnCapture   = document.getElementById('btn-capture-existing');
    const existingVideo = document.getElementById('existing-video-el');

    if (btnCapture && existingVideo) {
        btnCapture.addEventListener('click', function () {
            btnCapture.disabled = true;
            captureFrameFromVideoEl(existingVideo, null);
            existingVideo.addEventListener('seeked', () => { btnCapture.disabled = false; }, { once: true });
            existingVideo.addEventListener('error',  () => { btnCapture.disabled = false; }, { once: true });
        });
    }
})();
</script>
@endpush
@endsection

@extends('layouts.admin')
@section('title', isset($publication) ? 'Modifier la publication' : 'Nouvelle publication')
@section('page-title', isset($publication) ? 'Modifier une publication' : 'Nouvelle publication')
@section('breadcrumb')
    <a href="{{ route('admin.publications.index') }}" class="hover:text-gray-600">Publications</a> /
    {{ isset($publication) ? $publication->title : 'Nouvelle' }}
@endsection

@section('header-actions')
    <a href="{{ route('admin.publications.index') }}" class="btn-secondary btn-sm">← Retour</a>
@endsection

@section('content')
    <form method="POST"
        action="{{ isset($publication) ? route('admin.publications.update', $publication) : route('admin.publications.store') }}"
        enctype="multipart/form-data"
        x-data="{ fileName: '' }">
        @csrf
        @if (isset($publication)) @method('PUT') @endif

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
                        <label class="form-label">Titre <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $publication->title ?? '') }}"
                            class="form-input" required>
                        @error('title') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Contenu <span class="text-red-500">*</span></label>
                        <textarea name="content" rows="14" class="form-input resize-y font-mono text-sm"
                            required>{{ old('content', $publication->content ?? '') }}</textarea>
                        @error('content') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="space-y-5">
                {{-- Fichier joint --}}
                <div class="card card-body">
                    <h3 class="font-semibold text-gray-800 mb-4">Fichier joint</h3>
                    @if (isset($publication) && $publication->file_path)
                        <div class="mb-3 flex items-center gap-2 text-sm text-blue-700 bg-blue-50 border border-blue-200 rounded-lg px-3 py-2">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                            <span class="truncate">{{ $publication->file_name ?? basename($publication->file_path) }}</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-3">Choisir un nouveau fichier remplacera l'actuel.</p>
                    @endif
                    <input type="file" name="file" id="file-input" class="sr-only"
                        @change="fileName = $event.target.files[0]?.name || ''">
                    <label for="file-input"
                        class="flex items-center gap-3 border-2 border-dashed border-gray-200 rounded-xl p-4 cursor-pointer hover:border-indigo-400 hover:bg-indigo-50/30 transition">
                        <svg class="w-7 h-7 text-gray-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-700" x-text="fileName || 'Cliquer pour choisir un fichier'"></p>
                            <p class="text-xs text-gray-400">PDF, Word, Excel, images… max 20 Mo</p>
                        </div>
                    </label>
                    @error('file') <p class="form-error mt-2">{{ $message }}</p> @enderror
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-primary w-full justify-center py-2.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ isset($publication) ? 'Enregistrer' : 'Créer la publication' }}
                </button>
            </div>
        </div>
    </form>
@endsection

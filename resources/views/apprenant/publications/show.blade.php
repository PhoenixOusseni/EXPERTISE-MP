@extends('layouts.app')
@section('title', $publication->title)

@section('content')
    <div class="max-w-3xl mx-auto">
        <a href="{{ route('publications.index') }}"
            class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 mb-5 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Retour aux publications
        </a>

        <article class="card card-body space-y-6">
            {{-- Header --}}
            <div class="border-b border-gray-100 pb-5">
                <h1 class="text-2xl font-bold text-gray-900 leading-tight mb-3">{{ $publication->title }}</h1>
                <div class="flex items-center gap-4 text-sm text-gray-400">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ $publication->published_at?->format('d/m/Y') }}
                    </span>
                </div>
            </div>

            {{-- Content --}}
            <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $publication->content }}</div>

            {{-- Attached file --}}
            @if ($publication->file_path)
                <div class="border-t border-gray-100 pt-5">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                        Fichier joint
                    </h3>
                    <a href="{{ Storage::disk('public')->url($publication->file_path) }}"
                        target="_blank" rel="noopener"
                        class="inline-flex items-center gap-3 bg-blue-50 border border-blue-200 text-blue-700 hover:bg-blue-100 transition rounded-xl px-4 py-3 text-sm font-medium">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        {{ $publication->file_name ?? basename($publication->file_path) }}
                        <span class="text-blue-400 text-xs ml-auto">Télécharger</span>
                    </a>
                </div>
            @endif
        </article>
    </div>
@endsection

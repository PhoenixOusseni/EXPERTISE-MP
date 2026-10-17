@extends('layouts.app')
@section('title', 'Publications')

@section('content')
    {{-- Header --}}
    <div class="mb-8 bg-gradient-to-r from-blue-900 via-blue-800 to-indigo-800 rounded-2xl p-7 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                <defs><pattern id="g" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
                </pattern></defs>
                <rect width="100%" height="100%" fill="url(#g)"/>
            </svg>
        </div>
        <div class="relative">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
                <span class="text-emerald-300 font-semibold text-sm">Espace abonnés</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold mb-1">Publications</h1>
            <p class="text-blue-200/70 text-sm">Actualités, analyses et ressources exclusives.</p>
        </div>
    </div>

    @if (session('info'))
        <div class="alert-info mb-6">{{ session('info') }}</div>
    @endif

    @if ($publications->isEmpty())
        <div class="text-center py-20 text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p>Aucune publication pour le moment.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($publications as $pub)
                <a href="{{ route('publications.show', $pub) }}"
                    class="card card-body hover:shadow-md hover:-translate-y-0.5 transition flex flex-col gap-3 group">
                    <div class="flex items-start justify-between gap-2">
                        <h2 class="font-semibold text-gray-800 text-base leading-snug group-hover:text-indigo-600 transition line-clamp-2">
                            {{ $pub->title }}
                        </h2>
                        @if ($pub->file_path)
                            <span class="flex-shrink-0 w-7 h-7 bg-blue-50 border border-blue-200 rounded-lg flex items-center justify-center" title="Fichier joint">
                                <svg class="w-3.5 h-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                            </span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-500 line-clamp-3 flex-1">{{ Str::limit(strip_tags($pub->content), 120) }}</p>
                    <div class="flex items-center gap-2 text-xs text-gray-400 pt-2 border-t border-gray-100">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ $pub->published_at?->format('d/m/Y') }}
                    </div>
                </a>
            @endforeach
        </div>
        <div class="mt-6">{{ $publications->links() }}</div>
    @endif
@endsection

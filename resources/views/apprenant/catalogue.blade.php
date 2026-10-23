@extends('layouts.app')
@section('title', 'Catalogue de formations')

@section('content')

    {{-- Publicités carousel --}}
    @if (isset($publicites) && $publicites->isNotEmpty())
    <div class="mb-6 relative group"
        x-data="{
            cur: 0,
            n: {{ $publicites->count() }},
            t: null,
            start(){ this.t = setInterval(()=>this.next(), 4500); },
            next(){ this.cur=(this.cur+1)%this.n; },
            prev(){ this.cur=(this.cur-1+this.n)%this.n; },
            go(i){ this.cur=i; clearInterval(this.t); this.start(); }
        }"
        x-init="start()"
        @mouseenter="clearInterval(t)" @mouseleave="start()">

        {{-- Track --}}
        <div class="overflow-hidden rounded-2xl shadow-sm">
            <div class="flex transition-transform duration-500 ease-in-out"
                :style="`transform:translateX(-${cur*100}%)`">
                @foreach ($publicites as $pub)
                    <div class="min-w-full relative">
                        <a href="{{ route('publicites.show', $pub) }}" class="block">
                            <img src="{{ Storage::disk('public')->url($pub->image_path) }}"
                                alt="{{ $pub->title ?? '' }}"
                                class="w-full h-28 sm:h-36 object-cover">
                            @if ($pub->title)
                                <div class="absolute bottom-0 inset-x-0 rounded-b-2xl px-4 pt-10 pb-3"
                                    style="background: linear-gradient(to top, rgba(0,0,0,0.80) 0%, rgba(0,0,0,0.45) 60%, transparent 100%);">
                                    <p class="text-white font-semibold text-sm sm:text-base leading-snug drop-shadow-md">{{ $pub->title }}</p>
                                </div>
                            @endif
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        @if ($publicites->count() > 1)
            {{-- Arrows --}}
            <button @click="prev()"
                class="absolute left-2 top-[calc(50%-8px)] -translate-y-1/2 z-10 w-7 h-7 bg-white/80 backdrop-blur-sm rounded-full shadow flex items-center justify-center text-gray-700 hover:bg-white transition-all duration-200 opacity-0 group-hover:opacity-100">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button @click="next()"
                class="absolute right-2 top-[calc(50%-8px)] -translate-y-1/2 z-10 w-7 h-7 bg-white/80 backdrop-blur-sm rounded-full shadow flex items-center justify-center text-gray-700 hover:bg-white transition-all duration-200 opacity-0 group-hover:opacity-100">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </button>

            {{-- Dots --}}
            <div class="flex gap-1.5 justify-center mt-2">
                @foreach ($publicites as $i => $pub)
                    <button @click="go({{ $i }})"
                        :class="cur==={{ $i }} ? 'bg-indigo-400 w-5' : 'bg-gray-300 w-2'"
                        class="h-1.5 rounded-full transition-all duration-300"></button>
                @endforeach
            </div>
        @endif
    </div>
    @endif

    <!-- Hero banner -->
    <div
        class="mb-8 bg-gradient-to-r from-blue-900 via-blue-800 to-indigo-800 rounded-2xl p-7 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="g" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#g)" />
            </svg>
        </div>
        <div class="relative flex flex-col sm:flex-row items-start sm:items-center gap-5">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 10l4.553-2.069A1 1 0 0121 8.867v6.266a1 1 0 01-1.447.902L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <span class="text-emerald-300 font-semibold text-sm">Catalogue de formations</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold leading-tight mb-1">
                    Formations en Marchés Publics
                </h1>
                <p class="text-blue-200 text-sm">
                    {{ $totalVideos }} vidéo{{ $totalVideos > 1 ? 's' : '' }} disponible{{ $totalVideos > 1 ? 's' : '' }} —
                    Formation, Consultation, Coaching et plus
                </p>
            </div>
            <!-- Domain pills -->
            <div class="flex flex-wrap gap-2 max-w-xs">
                @foreach (['Formation', 'Consultation', 'Coaching', 'Appui-conseil / conseils', 'Accompagnement', 'Montage de dossiers de soumission / offres', 'Stratégies'] as $tag)
                    <span
                        class="bg-white/10 border border-white/20 text-white text-xs font-medium px-3 py-1 rounded-full">{{ $tag }}</span>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Subscription banner -->
    @if (!auth()->user()->hasActiveSubscription() && !auth()->user()->isAdmin())
        <div
            class="mb-6 bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-2xl p-5 flex flex-col sm:flex-row items-start sm:items-center gap-4">
            <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-amber-900">Abonnez-vous pour accéder à tout le contenu</h3>
                <p class="text-amber-700 text-sm mt-0.5">Regardez toutes les vidéos sans restriction à partir de
                    {{ number_format(\App\Models\Plan::active()->min('price'), 0, '.', ' ') }} USD/mois</p>
            </div>
            <a href="{{ route('formules') }}"
                class="flex-shrink-0 bg-amber-600 hover:bg-amber-700 text-white font-semibold text-sm px-5 py-2.5 rounded-xl transition shadow-sm">
                Voir les formules →
            </a>
        </div>
    @endif

    <div x-data="catalog()" x-init="init()">
        <!-- Title + Search -->
        <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Toutes les vidéos</h2>
                <p class="text-gray-500 text-sm mt-0.5">{{ $totalVideos }} vidéo{{ $totalVideos > 1 ? 's' : '' }}
                    disponible{{ $totalVideos > 1 ? 's' : '' }}</p>
            </div>
            <div class="relative w-full sm:w-72">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" x-model.debounce.400ms="search" placeholder="Rechercher une vidéo..."
                    class="form-input pl-9">
            </div>
        </div>

        <!-- Filters -->
        <div class="flex flex-wrap items-center gap-2 mb-7">
            <button @click="selectedCategory = null"
                :class="selectedCategory === null ? 'bg-indigo-600 text-white' :
                    'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'"
                class="px-4 py-1.5 rounded-full text-sm font-medium transition">
                Toutes
            </button>
            @foreach ($categories as $cat)
                <button @click="selectedCategory = {{ $cat->id }}"
                    :class="selectedCategory === {{ $cat->id }} ? 'bg-indigo-600 text-white' :
                        'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'"
                    class="px-4 py-1.5 rounded-full text-sm font-medium transition">
                    {{ $cat->name }}
                    <span class="ml-1 opacity-60">({{ $cat->videos_count }})</span>
                </button>
            @endforeach
        </div>

        <!-- Video grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @forelse($videos as $video)
                @php
                    $locked =
                        !$video->is_free && !auth()->user()->hasActiveSubscription() && !auth()->user()->isAdmin();
                @endphp
                <a href="{{ $locked ? route('formules') : route('videos.show', $video->slug) }}"
                    class="group bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5"
                    @if ($locked) title="Abonnez-vous pour accéder à cette vidéo" @endif>
                    <!-- Thumbnail -->
                    <div class="relative aspect-video bg-gray-100 overflow-hidden">
                        @if ($video->thumbnail_path)
                            <img src="{{ Storage::disk('public')->url($video->thumbnail_path) }}"
                                alt="{{ $video->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div
                                class="w-full h-full bg-gradient-to-br from-indigo-100 to-violet-100 flex items-center justify-center">
                                <svg class="w-12 h-12 text-indigo-300" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M15 10l4.553-2.069A1 1 0 0121 8.867v6.266a1 1 0 01-1.447.902L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                        <!-- Play overlay -->
                        <div
                            class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                            <div class="w-11 h-11 bg-white/90 rounded-full flex items-center justify-center shadow-lg">
                                <svg class="w-5 h-5 text-indigo-600 ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z" />
                                </svg>
                            </div>
                        </div>
                        <!-- Lock overlay for non-subscribers -->
                        @if ($locked)
                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                                <div class="text-white text-center">
                                    <svg class="w-8 h-8 mx-auto mb-1" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    <span class="text-xs font-semibold">Abonnement requis</span>
                                </div>
                            </div>
                        @endif
                        <!-- Free badge -->
                        @if ($video->is_free)
                            <span class="absolute top-2 left-2 badge-green font-semibold">Gratuit</span>
                        @endif
                        <!-- Duration -->
                        @if ($video->duration)
                            <span class="absolute bottom-2 right-2 bg-black/70 text-white text-xs px-2 py-0.5 rounded-md">
                                {{ gmdate($video->duration >= 3600 ? 'H:i:s' : 'i:s', $video->duration) }}
                            </span>
                        @endif
                    </div>
                    <!-- Info -->
                    <div class="p-4">
                        @if ($video->category)
                            <span class="badge-blue mb-2">{{ $video->category->name }}</span>
                        @endif
                        <h3 class="text-sm font-semibold text-gray-900 leading-snug line-clamp-2 mt-1">{{ $video->title }}
                        </h3>
                        @if ($video->level)
                            <p class="text-xs text-gray-400 mt-1.5 capitalize">{{ $video->level }}</p>
                        @endif
                    </div>
                </a>
            @empty
                <div class="col-span-full py-16 text-center">
                    <svg class="w-14 h-14 text-gray-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M15 10l4.553-2.069A1 1 0 0121 8.867v6.266a1 1 0 01-1.447.902L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <p class="text-gray-400 font-medium">Aucune vidéo trouvée</p>
                    <p class="text-gray-300 text-sm mt-1">Essayez de modifier vos filtres de recherche</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $videos->withQueryString()->links() }}
        </div>
    </div>

    <script>
        function catalog() {
            return {
                search: '{{ request('search', '') }}',
                selectedCategory: {{ request('category_id', 'null') }},
                init() {
                    this.$watch('search', val => this.applyFilters());
                    this.$watch('selectedCategory', val => this.applyFilters());
                },
                applyFilters() {
                    const params = new URLSearchParams();
                    if (this.search) params.set('search', this.search);
                    if (this.selectedCategory) params.set('category_id', this.selectedCategory);
                    window.location.href = '{{ route('catalogue') }}' + (params.toString() ? '?' + params.toString() : '');
                }
            }
        }
    </script>
@endsection

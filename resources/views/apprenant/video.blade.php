@extends('layouts.app')
@section('title', $video->title)

@section('content')
    <div class="max-w-5xl mx-auto">
        <!-- Back link -->
        <a href="{{ route('catalogue') }}"
            class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 mb-5 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour au catalogue
        </a>

        <!-- Player / Paywall -->
        @if ($canWatch)

            {{-- ──────────────────────────────────────────
             WRAPPER PROTÉGÉ — filigrane + JS anticopie
        ────────────────────────────────────────────── --}}
            <div id="player-wrapper"
                class="relative bg-black rounded-2xl overflow-hidden shadow-xl mb-6 aspect-video select-none"
                style="-webkit-user-drag:none;-webkit-touch-callout:none;" oncontextmenu="return false;"
                ondragstart="return false;">

                {{-- Filigrane flottant avec nom/email de l'utilisateur --}}
                <div id="video-watermark" class="absolute z-20 pointer-events-none select-none transition-all duration-1000"
                    style="top:15%;left:10%;">
                    <span class="text-white/20 text-xs font-mono leading-tight block whitespace-nowrap">
                        {{ auth()->user()->name }}
                    </span>
                    <span class="text-white/15 text-[10px] font-mono leading-tight block whitespace-nowrap">
                        {{ auth()->user()->email }}
                    </span>
                </div>

                {{-- Overlay DevTools / protection visuelle --}}
                <div id="devtools-overlay" class="absolute inset-0 z-30 bg-black/95 hidden items-center justify-center">
                    <div class="text-center text-white px-6">
                        <svg class="w-12 h-12 mx-auto mb-3 text-red-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <p class="font-semibold text-lg">Lecture suspendue</p>
                        <p class="text-white/60 text-sm mt-1">Fermez les outils de développement pour continuer.</p>
                    </div>
                </div>

                @if ($video->source_type->value === 'upload' && $streamUrl)
                    <video id="protected-video" class="w-full h-full" controls controlslist="nodownload noremoteplayback"
                        disablepictureinpicture preload="metadata" oncontextmenu="return false;"
                        ondragstart="return false;">
                        <source src="{{ $streamUrl }}" type="video/mp4">
                        Votre navigateur ne supporte pas la lecture vidéo.
                    </video>
                @elseif($video->source_type->value === 'youtube' && $video->external_url)
                    @php
                        preg_match(
                            '/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([^&\s]+)/',
                            $video->external_url,
                            $m,
                        );
                        $ytId = $m[1] ?? null;
                    @endphp
                    @if ($ytId)
                        <iframe class="w-full h-full"
                            src="https://www.youtube.com/embed/{{ $ytId }}?rel=0&modestbranding=1" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope"
                            sandbox="allow-scripts allow-same-origin allow-presentation"></iframe>
                    @endif
                @elseif($video->source_type->value === 'vimeo' && $video->external_url)
                    @php
                        preg_match('/vimeo\.com\/(\d+)/', $video->external_url, $m);
                        $vimeoId = $m[1] ?? null;
                    @endphp
                    @if ($vimeoId)
                        <iframe class="w-full h-full" src="https://player.vimeo.com/video/{{ $vimeoId }}?dnt=1"
                            frameborder="0" allow="autoplay; fullscreen"
                            sandbox="allow-scripts allow-same-origin allow-presentation"></iframe>
                    @endif
                @elseif($video->external_url)
                    <video id="protected-video" class="w-full h-full" controls controlslist="nodownload noremoteplayback"
                        disablepictureinpicture preload="metadata" oncontextmenu="return false;"
                        ondragstart="return false;">
                        <source src="{{ $video->external_url }}">
                    </video>
                @endif
            </div>

            {{-- Toast notification --}}
            <div id="protection-toast"
                class="fixed bottom-6 right-6 z-50 bg-gray-900 text-white text-sm px-4 py-3 rounded-xl shadow-lg hidden items-center gap-2 transition-all">
                <svg class="w-4 h-4 text-red-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
                <span id="protection-toast-msg">Action non autorisée</span>
            </div>

            <script>
                (function() {
                    'use strict';

                    const video = document.getElementById('protected-video');
                    const overlay = document.getElementById('devtools-overlay');
                    const toast = document.getElementById('protection-toast');
                    const toastMsg = document.getElementById('protection-toast-msg');
                    let toastTimer = null;

                    // ── Afficher un toast ──────────────────────────────────
                    function showToast(msg) {
                        toastMsg.textContent = msg;
                        toast.classList.remove('hidden');
                        toast.classList.add('flex');
                        clearTimeout(toastTimer);
                        toastTimer = setTimeout(() => {
                            toast.classList.add('hidden');
                            toast.classList.remove('flex');
                        }, 3000);
                    }

                    // ── Clic droit sur toute la page ───────────────────────
                    document.addEventListener('contextmenu', function(e) {
                        if (e.target.closest('#player-wrapper')) {
                            e.preventDefault();
                            showToast('Clic droit désactivé sur les vidéos.');
                        }
                    });

                    // ── Raccourcis clavier bloqués ─────────────────────────
                    document.addEventListener('keydown', function(e) {
                        const ctrl = e.ctrlKey || e.metaKey;
                        const shift = e.shiftKey;
                        const key = e.key.toLowerCase();

                        const blocked =
                            // Outils développeur / console
                            e.key === 'F12' ||
                            (ctrl && shift && ['i', 'j', 'c'].includes(key)) ||
                            // Enregistrer la page
                            (ctrl && key === 's') ||
                            // Voir le source
                            (ctrl && key === 'u') ||
                            // Imprimer
                            (ctrl && key === 'p') ||
                            // Capture d'écran Windows
                            e.key === 'PrintScreen';

                        if (blocked) {
                            e.preventDefault();
                            e.stopImmediatePropagation();
                            if (e.key === 'PrintScreen') {
                                // Vider le presse-papiers après PrintScreen
                                try {
                                    navigator.clipboard.writeText('');
                                } catch (_) {}
                                showToast('Capture d\'écran non autorisée sur ce contenu.');
                            } else {
                                showToast('Action non autorisée.');
                            }
                        }
                    }, true);

                    // ── Drag prevention ────────────────────────────────────
                    document.addEventListener('dragstart', function(e) {
                        if (e.target.closest('#player-wrapper')) {
                            e.preventDefault();
                        }
                    });

                    // ── Filigrane flottant ─────────────────────────────────
                    const wm = document.getElementById('video-watermark');
                    const wmPositions = [{
                            top: '12%',
                            left: '8%',
                            right: 'auto',
                            bottom: 'auto'
                        },
                        {
                            top: '65%',
                            left: 'auto',
                            right: '10%',
                            bottom: 'auto'
                        },
                        {
                            top: '35%',
                            left: '45%',
                            right: 'auto',
                            bottom: 'auto'
                        },
                        {
                            top: '20%',
                            left: 'auto',
                            right: '8%',
                            bottom: 'auto'
                        },
                        {
                            top: 'auto',
                            left: '12%',
                            right: 'auto',
                            bottom: '18%'
                        },
                        {
                            top: '50%',
                            left: '25%',
                            right: 'auto',
                            bottom: 'auto'
                        },
                    ];
                    let wmIdx = 0;

                    function moveWatermark() {
                        const p = wmPositions[wmIdx % wmPositions.length];
                        wm.style.top = p.top;
                        wm.style.left = p.left;
                        wm.style.right = p.right;
                        wm.style.bottom = p.bottom;
                        wmIdx++;
                    }
                    moveWatermark();
                    setInterval(moveWatermark, 9000);

                    // ── Détection DevTools (méthode taille fenêtre) ────────
                    if (video) {
                        const THRESHOLD = 160;
                        let devToolsOpen = false;

                        setInterval(function() {
                            const opened =
                                (window.outerWidth - window.innerWidth) > THRESHOLD ||
                                (window.outerHeight - window.innerHeight) > THRESHOLD;

                            if (opened && !devToolsOpen) {
                                devToolsOpen = true;
                                video.pause();
                                overlay.classList.remove('hidden');
                                overlay.classList.add('flex');
                            } else if (!opened && devToolsOpen) {
                                devToolsOpen = false;
                                overlay.classList.add('hidden');
                                overlay.classList.remove('flex');
                            }
                        }, 800);
                    }

                    // ── Désactiver la sélection de texte sur le wrapper ────
                    const wrapper = document.getElementById('player-wrapper');
                    if (wrapper) {
                        wrapper.addEventListener('selectstart', e => e.preventDefault());
                    }
                })();
            </script>
        @else
            <!-- Paywall -->
            <div
                class="relative aspect-video bg-gradient-to-br from-slate-800 to-indigo-900 rounded-2xl overflow-hidden shadow-xl mb-6 flex items-center justify-center">
                @if ($video->thumbnail_path)
                    <img src="{{ Storage::disk('public')->url($video->thumbnail_path) }}"
                        class="absolute inset-0 w-full h-full object-cover opacity-20" alt="">
                @endif
                <div class="relative text-center text-white z-10 px-8">
                    <div
                        class="w-16 h-16 bg-white/10 backdrop-blur rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Cette vidéo est réservée aux abonnés</h3>
                    <p class="text-white/70 text-sm mb-6">Souscrivez à un abonnement pour regarder cette vidéo et accéder à
                        tout le catalogue.</p>
                    <a href="{{ route('formules') }}"
                        class="inline-flex items-center gap-2 bg-indigo-600 text-white font-semibold px-6 py-3 rounded-xl hover:bg-indigo-700 transition">
                        Voir les formules d'abonnement
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                </div>
            </div>
        @endif

        <!-- Video info -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <!-- Tags -->
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    @if ($video->category)
                        <span class="badge-blue">{{ $video->category->name }}</span>
                    @endif
                    @if ($video->is_free)
                        <span class="badge-green">Gratuit</span>
                    @endif
                    @if ($video->level)
                        <span class="badge-gray capitalize">{{ $video->level }}</span>
                    @endif
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-3">{{ $video->title }}</h1>
                @if ($video->description)
                    <div class="prose prose-sm max-w-none text-gray-600">
                        <p>{{ $video->description }}</p>
                    </div>
                @endif
            </div>

            <!-- Sidebar info -->
            <div class="card card-body space-y-4 h-fit">
                <h3 class="font-semibold text-gray-800">Informations</h3>
                @if ($video->duration)
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Durée : {{ gmdate($video->duration >= 3600 ? 'H:i:s' : 'i:s', $video->duration) }}
                    </div>
                @endif
                <div class="flex items-center gap-3 text-sm text-gray-600">
                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    {{ number_format($video->views_count) }} vue{{ $video->views_count > 1 ? 's' : '' }}
                </div>
                @if ($video->published_at)
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ $video->published_at->format('d/m/Y') }}
                    </div>
                @endif
                @if (!$canWatch)
                    <div class="pt-2 border-t border-gray-100">
                        <a href="{{ route('formules') }}" class="btn-primary w-full justify-center">
                            S'abonner pour regarder
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

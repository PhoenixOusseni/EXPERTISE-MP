<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $publicite->title ?? 'Publicité' }} — Expertise Marchés Publics</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4/dist/index.global.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .text-gold { color: #C9A84C; }
        .bg-navy { background-color: #0B1E3E; }
        .hero-overlay {
            background: linear-gradient(to bottom, rgba(11,30,62,0.15) 0%, rgba(11,30,62,0.7) 100%);
        }
        .section-badge {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 0.7rem; font-weight: 600; letter-spacing: 0.08em;
            text-transform: uppercase; color: #C9A84C;
            padding: 5px 12px; border-radius: 100px;
            background: rgba(201,168,76,0.12);
            border: 1px solid rgba(201,168,76,0.3);
        }
        .file-card { transition: box-shadow .2s, transform .2s; }
        .file-card:hover { box-shadow: 0 8px 30px rgba(11,30,62,0.12); transform: translateY(-2px); }
    </style>
</head>
<body class="bg-[#F4F6FB] min-h-full">

    {{-- ── NAV ── --}}
    <nav class="bg-navy sticky top-0 z-50 shadow-lg">
        <div class="max-w-6xl mx-auto px-5 sm:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('welcome') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/logo_expert.png') }}" alt="Expertise Marchés Publics"
                    class="h-9 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
                <span class="hidden sm:block text-white/80 text-sm font-semibold tracking-wide">Expertise Marchés Publics</span>
            </a>
            <a href="{{ url()->previous() === url()->current() ? route('welcome') : url()->previous() }}"
                class="inline-flex items-center gap-2 text-white/70 hover:text-white text-sm font-medium transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour
            </a>
        </div>
    </nav>

    {{-- ── HERO IMAGE ── --}}
    <div class="relative w-full" style="max-height: 480px; overflow: hidden;">
        <img src="{{ Storage::disk('public')->url($publicite->image_path) }}"
            alt="{{ $publicite->title ?? 'Publicité' }}"
            class="w-full object-cover"
            style="max-height: 480px; width: 100%;">
        <div class="hero-overlay absolute inset-0"></div>

        {{-- Badge + title overlay --}}
        @if ($publicite->title)
        <div class="absolute bottom-0 inset-x-0 px-6 sm:px-12 pb-8 pt-16">
            <div class="max-w-4xl mx-auto">
                <span class="section-badge mb-4 block w-fit">Annonce</span>
                <h1 class="text-2xl sm:text-4xl font-bold text-white leading-tight"
                    style="font-family:'Playfair Display',serif; text-shadow:0 2px 12px rgba(0,0,0,0.35);">
                    {{ $publicite->title }}
                </h1>
            </div>
        </div>
        @endif
    </div>

    {{-- ── MAIN CONTENT ── --}}
    <div class="max-w-4xl mx-auto px-5 sm:px-8 py-10 space-y-6">

        {{-- Info card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            {{-- Top strip --}}
            <div class="h-1 w-full" style="background: linear-gradient(90deg,#1a3a6e,#C9A84C);"></div>
            <div class="px-6 sm:px-8 py-6">
                <div class="flex flex-wrap items-center gap-3">

                    {{-- Validity badge --}}
                    @php
                        $today = now()->toDateString();
                        $isActive = $publicite->is_active
                            && $publicite->start_date->toDateString() <= $today
                            && $publicite->end_date->toDateString() >= $today;
                    @endphp
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold
                        {{ $isActive ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-600 border border-red-200' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $isActive ? 'bg-emerald-500' : 'bg-red-400' }}"></span>
                        {{ $isActive ? 'En cours' : 'Expirée' }}
                    </span>

                    {{-- Date range --}}
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-gray-50 text-gray-600 border border-gray-200">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Du {{ $publicite->start_date->translatedFormat('d M Y') }}
                        au {{ $publicite->end_date->translatedFormat('d M Y') }}
                    </span>
                </div>

                @if ($publicite->title)
                    <div class="mt-5 pt-5 border-t border-gray-100">
                        <p class="text-sm text-gray-500 leading-relaxed">
                            Consultez les informations détaillées ci-dessous concernant cette annonce.
                            @if ($publicite->file_path) Un document est disponible au téléchargement. @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>

        {{-- File download card --}}
        @if ($publicite->file_path)
            @php
                $ext = strtolower(pathinfo($publicite->file_name ?? $publicite->file_path, PATHINFO_EXTENSION));
                $bgColor = match($ext) {
                    'pdf'         => 'bg-red-50 border-red-100',
                    'xls','xlsx'  => 'bg-green-50 border-green-100',
                    default       => 'bg-blue-50 border-blue-100',
                };
                $iconColor = match($ext) {
                    'pdf'         => 'text-red-500',
                    'xls','xlsx'  => 'text-green-600',
                    default       => 'text-blue-500',
                };
                $iconBg = match($ext) {
                    'pdf'         => 'bg-red-100',
                    'xls','xlsx'  => 'bg-green-100',
                    default       => 'bg-blue-100',
                };
                $typeLabel = match($ext) {
                    'pdf'         => 'Document PDF',
                    'xls','xlsx'  => 'Feuille Excel',
                    'doc','docx'  => 'Document Word',
                    default       => 'Fichier ' . strtoupper($ext),
                };
            @endphp
            <div class="file-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="h-1 w-full bg-gradient-to-r {{ $ext === 'pdf' ? 'from-red-400 to-red-600' : ($ext === 'xls' || $ext === 'xlsx' ? 'from-green-400 to-green-600' : 'from-blue-400 to-blue-600') }}"></div>
                <div class="px-6 sm:px-8 py-6">
                    <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Document joint</h2>
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-xl {{ $iconBg }} flex items-center justify-center flex-shrink-0">
                            <svg class="w-7 h-7 {{ $iconColor }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800 truncate text-sm sm:text-base">
                                {{ $publicite->file_name ?? 'Document joint' }}
                            </p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $typeLabel }}</p>
                        </div>
                        <a href="{{ Storage::disk('public')->url($publicite->file_path) }}"
                            download="{{ $publicite->file_name ?? 'document' }}"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-white text-sm font-semibold flex-shrink-0 transition-all duration-200 hover:opacity-90 hover:shadow-md"
                            style="background: linear-gradient(135deg,#1a3a6e,#2b5aad);">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            <span class="hidden sm:inline">Télécharger</span>
                        </a>
                    </div>
                </div>
            </div>
        @endif

        {{-- CTA contact --}}
        <div class="rounded-2xl overflow-hidden shadow-sm"
            style="background: linear-gradient(135deg, #0B1E3E 0%, #1a3a6e 60%, #2b5aad 100%);">
            {{-- Grid overlay --}}
            <div class="absolute inset-0 pointer-events-none"
                style="background-image: linear-gradient(rgba(255,255,255,.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.03) 1px, transparent 1px); background-size: 40px 40px;"></div>
            <div class="relative px-6 sm:px-10 py-8 text-center">
                <div class="w-10 h-10 rounded-full mx-auto mb-4 flex items-center justify-center"
                    style="background: rgba(201,168,76,0.15); border: 1px solid rgba(201,168,76,0.3);">
                    <svg class="w-5 h-5 text-[#C9A84C]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <h3 class="text-white font-bold text-lg mb-1" style="font-family:'Playfair Display',serif;">
                    Besoin d'informations ?
                </h3>
                <p class="text-blue-300/70 text-sm mb-6 max-w-xs mx-auto">
                    Notre équipe est disponible pour répondre à toutes vos questions.
                </p>
                <div class="flex flex-wrap items-center justify-center gap-3">
                    <a href="https://wa.me/22678547805" target="_blank" rel="noopener"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white text-sm font-semibold transition-all duration-200 hover:opacity-90 hover:shadow-lg"
                        style="background:#25D366;">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="white"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        +226 78547805
                    </a>
                    <a href="tel:+22646788578"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white text-sm font-semibold border border-white/20 hover:bg-white/10 transition-all duration-200">
                        <svg class="w-4 h-4 text-[#C9A84C]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        +226 46788578
                    </a>
                </div>
            </div>
        </div>

    </div>

    {{-- ── FOOTER ── --}}
    <footer class="bg-navy mt-10 py-6">
        <div class="max-w-6xl mx-auto px-5 sm:px-8 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-white/40 text-xs">© {{ date('Y') }} Expertise Marchés Publics — Tous droits réservés</p>
            <a href="{{ route('welcome') }}" class="text-[#C9A84C] text-xs hover:underline">
                Retour à l'accueil
            </a>
        </div>
    </footer>

</body>
</html>

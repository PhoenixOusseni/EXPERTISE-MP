<!DOCTYPE html>
<html lang="fr" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Expertise Marchés Publics') — Plateforme de formation</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4/dist/index.global.js"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/persist@3.17.4/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.17.4/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .auth-panel {
            background: linear-gradient(160deg, #071323 0%, #0B1E3E 50%, #0F2958 100%);
        }

        .auth-panel-grid {
            background-image: linear-gradient(rgba(255, 255, 255, .03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .03) 1px, transparent 1px);
            background-size: 48px 48px;
        }

        .text-gold {
            color: #C9A84C;
        }

        .section-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #C9A84C;
            padding: 5px 12px;
            border-radius: 100px;
            background: rgba(201, 168, 76, 0.1);
            border: 1px solid rgba(201, 168, 76, 0.25);
        }
    </style>
</head>

<body class="h-full" x-data>
    <div class="min-h-full flex">

        <!-- ── LEFT PANEL (desktop only) ── -->
        <div class="hidden lg:flex lg:w-[46%] auth-panel flex-col justify-between px-12 py-12 relative overflow-hidden">
            <div class="auth-panel-grid absolute inset-0"></div>
            <!-- Ambient glow -->
            <div class="absolute top-1/4 right-0 w-64 h-64 pointer-events-none"
                style="background: radial-gradient(circle, rgba(27,79,216,0.2) 0%, transparent 70%);"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 pointer-events-none"
                style="background: radial-gradient(circle, rgba(201,168,76,0.1) 0%, transparent 70%);"></div>

            <!-- Logo -->
            <div class="relative z-10">
                <a href="{{ route('welcome') }}" class="inline-flex items-center gap-3 group">
                    <img src="/images/logo_expert.png" alt="Expertise Marchés Publics"
                        class="h-14 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
                </a>
            </div>

            <!-- Center content -->
            <div class="relative z-10 flex-1 flex flex-col justify-center py-12">
                <span class="section-badge mb-6 inline-flex w-fit">Plateforme de formation</span>
                <h2 class="text-3xl font-bold text-white leading-tight mb-4"
                    style="font-family: 'Playfair Display', serif;">
                    L'expertise au service<br>des marchés publics
                </h2>
                <div class="w-12 h-0.5 mb-5" style="background: linear-gradient(90deg, #C9A84C, #F0C96B);"></div>
                <p class="text-blue-200/70 text-sm leading-relaxed mb-8 max-w-xs">
                    Formation, consultation, accompagnement et coaching pour maîtriser les marchés publics en Afrique de
                    l'Ouest.
                </p>

                <!-- Services list -->
                <div class="space-y-2.5 mb-8">
                    @foreach (['Formation professionnelle', 'Consultation & Appui-Conseil', 'Accompagnement sur mesure', 'Montage de dossiers', 'Coaching individuel', 'Stratégies gagnantes'] as $svc)
                        <div class="flex items-center gap-3">
                            <div class="w-4 h-4 rounded-sm flex items-center justify-center flex-shrink-0"
                                style="background: rgba(201,168,76,0.15); border: 1px solid rgba(201,168,76,0.3);">
                                <svg class="w-2.5 h-2.5 text-[#C9A84C]" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <span class="text-sm text-blue-100/70">{{ $svc }}</span>
                        </div>
                    @endforeach
                </div>

                <!-- Stats row -->
                <div class="grid grid-cols-3 gap-3">
                    @foreach ([['3 247', 'Followers'], ['4 694', "J'aime"], ['7+', 'Services']] as [$n, $l])
                        <div class="text-center py-3 px-2 rounded-xl"
                            style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);">
                            <p class="text-lg font-bold text-white">{{ $n }}</p>
                            <p class="text-[10px] text-blue-300/50 mt-0.5">{{ $l }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Bottom contact -->
            <div class="relative z-10 flex items-center gap-2 text-blue-300/50 text-sm">
                <svg class="w-4 h-4 text-[#C9A84C]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
                +226 78547805 · @expertise.marchs
            </div>
        </div>

        <!-- ── RIGHT PANEL (form) ── -->
        <div class="flex-1 flex flex-col justify-center bg-[#F7F8FB] py-10 px-5 sm:px-10 overflow-y-auto">
            <!-- Mobile logo -->
            <div class="lg:hidden flex justify-center mb-8">
                <a href="{{ route('welcome') }}" class="flex flex-col items-center gap-2">
                    <img src="/images/logo_expert.png" alt="Expertise Marchés Publics"
                        class="h-14 w-auto object-contain">
                    <span class="text-sm font-bold text-[#0B1E3E]">Expertise Marchés Publics</span>
                </a>
            </div>

            <div class="w-full max-w-[420px] mx-auto">
                @include('components.alert')
                @yield('content')

                <p class="mt-8 text-center text-xs text-gray-400">
                    © {{ date('Y') }} Expertise Marchés Publics — Tous droits réservés
                </p>
            </div>
        </div>
    </div>
</body>

</html>

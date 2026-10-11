<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expertise Marchés Publics — Formation & Conseil</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('build/assets/app-DIS-EYDy.css') }}">
    <script src="{{ asset('build/assets/app-DxGHKhll.js') }}" defer></script>
    <style>
        :root {
            --navy: #0B1E3E;
            --navy-mid: #142B54;
            --blue: #1B4FD8;
            --blue-light: #3B82F6;
            --gold: #C9A84C;
            --gold-light: #F0C96B;
            --emerald: #059669;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        h1,
        h2,
        .serif {
            font-family: 'Playfair Display', serif;
        }

        /* Navbar */
        .navbar-glass {
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        }

        /* Hero */
        .hero-section {
            background: linear-gradient(160deg, #071323 0%, #0B1E3E 45%, #0F2958 100%);
            min-height: 100vh;
        }

        .hero-glow {
            background: radial-gradient(ellipse 600px 400px at 80% 50%, rgba(27, 79, 216, 0.25) 0%, transparent 70%);
        }

        .hero-line {
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, var(--gold), var(--gold-light));
            border-radius: 2px;
        }

        /* Gold accent */
        .text-gold {
            color: var(--gold);
        }

        .bg-gold {
            background: var(--gold);
        }

        .border-gold {
            border-color: var(--gold);
        }

        /* Service cards */
        .service-card {
            border-left: 3px solid transparent;
            transition: all 0.25s ease;
        }

        .service-card:hover {
            border-left-color: var(--gold);
            transform: translateX(4px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }

        /* Stats bar */
        .stat-divider {
            width: 1px;
            background: rgba(255, 255, 255, 0.15);
        }

        /* Section badge */
        .section-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--gold);
            padding: 6px 14px;
            border-radius: 100px;
            background: rgba(201, 168, 76, 0.1);
            border: 1px solid rgba(201, 168, 76, 0.3);
        }

        /* Pricing card */
        .pricing-featured {
            background: linear-gradient(150deg, #0B1E3E 0%, #142B54 100%);
        }

        /* CTA section */
        .cta-bg {
            background: linear-gradient(135deg, #071323 0%, #0B1E3E 60%, #0d2d5e 100%);
        }

        /* Animated underline */
        .animated-underline {
            position: relative;
        }

        .animated-underline::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, var(--gold), var(--gold-light));
            border-radius: 2px;
        }

        /* Button premium */
        .btn-gold {
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-light) 100%);
            color: #0B1E3E;
            font-weight: 700;
            border-radius: 10px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 20px rgba(201, 168, 76, 0.35);
        }

        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(201, 168, 76, 0.45);
        }

        .btn-outline-white {
            background: transparent;
            color: white;
            font-weight: 600;
            border-radius: 10px;
            border: 1.5px solid rgba(255, 255, 255, 0.3);
            transition: all 0.2s ease;
        }

        .btn-outline-white:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.5);
        }

        /* Step circle */
        .step-ring {
            box-shadow: 0 0 0 8px rgba(27, 79, 216, 0.12);
        }

        /* Footer */
        .footer-bg {
            background: #060E1C;
        }
    </style>
</head>

<body class="bg-white antialiased" x-data>

    <!-- ═══════════════════ NAVIGATION ═══════════════════ -->
    <nav class="navbar-glass sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 flex items-center justify-between h-[70px]">
            <!-- Brand -->
            <a href="{{ route('welcome') }}" class="flex items-center gap-3 group">
                <img src="/images/logo_expert.png" alt="Expertise Marchés Publics"
                    class="h-11 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
                <div class="hidden lg:block leading-none">
                    <span class="block text-[15px] font-bold text-[#0B1E3E]">Expertise Marchés Publics</span>
                    <span class="block text-[11px] font-medium text-[#C9A84C] mt-0.5 tracking-wide">Les marchés publics,
                        au service du développement</span>
                </div>
            </a>

            <!-- Nav links -->
            <div class="hidden md:flex items-center gap-1">
                <a href="#domaines"
                    class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-[#1B4FD8] rounded-lg hover:bg-blue-50 transition">Nos
                    domaines</a>
                <a href="#pourquoi"
                    class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-[#1B4FD8] rounded-lg hover:bg-blue-50 transition">À
                    propos</a>
                <a href="#pricing"
                    class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-[#1B4FD8] rounded-lg hover:bg-blue-50 transition">Tarifs</a>
            </div>

            <!-- Auth buttons -->
            <div class="flex items-center gap-2.5">
                <a href="{{ route('login') }}"
                    class="hidden sm:inline-flex items-center px-4 py-2 text-sm font-semibold text-[#0B1E3E] border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-gray-300 transition">
                    Se connecter
                </a>
                <a href="{{ route('register') }}" class="btn-gold inline-flex items-center gap-2 px-5 py-2 text-sm">
                    S'inscrire
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
            </div>
        </div>
    </nav>

    <!-- ═══════════════════ HERO ═══════════════════ -->
    <section class="hero-section relative flex items-center overflow-hidden">
        <!-- Ambient glow -->
        <div class="hero-glow absolute inset-0 pointer-events-none"></div>
        <!-- Grid overlay -->
        <div class="absolute inset-0 pointer-events-none"
            style="background-image: linear-gradient(rgba(255,255,255,.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.03) 1px, transparent 1px); background-size: 64px 64px;">
        </div>
        <!-- Orbs -->
        <div class="absolute top-1/4 right-10 w-72 h-72 rounded-full pointer-events-none"
            style="background: radial-gradient(circle, rgba(27,79,216,0.18) 0%, transparent 70%);"></div>
        <div class="absolute bottom-1/4 left-20 w-48 h-48 rounded-full pointer-events-none"
            style="background: radial-gradient(circle, rgba(201,168,76,0.12) 0%, transparent 70%);"></div>

        <div class="relative w-full max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 py-24 md:py-32">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

                <!-- Left -->
                <div class="order-2 lg:order-1">
                    <!-- Badge -->
                    <span class="section-badge mb-8 inline-flex">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        Plateforme de référence · Burkina Faso
                    </span>

                    <div class="hero-line mb-6"></div>

                    <h1
                        class="text-[2.8rem] sm:text-5xl lg:text-[3.5rem] font-bold text-white leading-[1.12] tracking-tight mb-6">
                        L'expertise au service<br>
                        des <span class="text-gold">Marchés Publics</span>
                    </h1>

                    <p class="text-base sm:text-lg text-blue-200/80 max-w-lg mb-10 leading-relaxed font-light">
                        Formation professionnelle, consultation, accompagnement et coaching pour maîtriser les marchés
                        publics et réussir vos appels d'offres.
                    </p>

                    <div class="flex flex-wrap gap-3 mb-10">
                        <a href="{{ route('register') }}"
                            class="btn-gold inline-flex items-center gap-2.5 px-7 py-3.5 text-[15px]">
                            Commencer ma formation
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                        <a href="#domaines"
                            class="btn-outline-white inline-flex items-center gap-2 px-7 py-3.5 text-[15px]">
                            Découvrir nos services
                        </a>
                    </div>

                    <!-- Trust bar -->
                    <div class="flex items-center gap-6 flex-wrap">
                        <div class="flex -space-x-2">
                            @foreach (['#1B4FD8', '#059669', '#7C3AED', '#DC2626'] as $c)
                                <div class="w-8 h-8 rounded-full border-2 border-[#0B1E3E] flex items-center justify-center"
                                    style="background:{{ $c }};">
                                    <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
                                    </svg>
                                </div>
                            @endforeach
                        </div>
                        <div>
                            <div class="flex items-center gap-1 mb-0.5">
                                @for ($i = 0; $i < 5; $i++)
                                    <svg class="w-3.5 h-3.5 text-[#F0C96B]" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-blue-200/70 text-xs font-medium">Plus de 3 000 apprenants
                                satisfaits</span>
                        </div>
                    </div>
                </div>

                <!-- Right: premium card -->
                <div class="order-1 lg:order-2 flex justify-center lg:justify-end">
                    <div class="relative w-full max-w-sm">
                        <!-- Main card -->
                        <div class="relative z-10 rounded-2xl overflow-hidden shadow-2xl"
                            style="background: rgba(255,255,255,0.05); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.12);">
                            <!-- Card header -->
                            <div class="px-7 pt-7 pb-5 border-b border-white/10">
                                <img src="/images/logo_expert.png" alt="Logo"
                                    class="h-16 w-auto object-contain mb-4">
                                <h3 class="text-white font-bold text-lg leading-snug">Expertise Marchés Publics</h3>
                                <p class="text-blue-300/70 text-sm mt-1 italic">"Les marchés publics, au service du
                                    développement"</p>
                            </div>
                            <!-- Stats inside card -->
                            <div class="grid grid-cols-3 divide-x divide-white/10">
                                @foreach ([['3 247', 'Followers'], ['4 694', "J'aime"], ['7+', 'Services']] as [$val, $lbl])
                                    <div class="py-5 text-center">
                                        <p class="text-xl font-bold text-white">{{ $val }}</p>
                                        <p class="text-[11px] text-blue-300/60 mt-0.5">{{ $lbl }}</p>
                                    </div>
                                @endforeach
                            </div>
                            <!-- Services list -->
                            <div class="p-5 space-y-2.5">
                                @foreach (['Formation', 'Consultation', 'Appui-Conseil', 'Accompagnement'] as $svc)
                                    <div class="flex items-center gap-3">
                                        <div class="w-1.5 h-1.5 rounded-full bg-[#C9A84C] flex-shrink-0"></div>
                                        <span class="text-sm text-blue-100/80">{{ $svc }}</span>
                                    </div>
                                @endforeach
                                <div class="flex items-center gap-3">
                                    <div class="w-1.5 h-1.5 rounded-full bg-[#C9A84C] flex-shrink-0"></div>
                                    <span class="text-sm text-blue-100/80">+ 3 autres services…</span>
                                </div>
                            </div>
                            <!-- CTA in card -->
                            <div class="px-5 pb-6">
                                <a href="{{ route('register') }}"
                                    class="btn-gold flex items-center justify-center gap-2 py-3 text-sm w-full">
                                    Accéder à la plateforme
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <!-- Contact floating badge -->
                        <div
                            class="absolute -bottom-4 -left-4 bg-[#0B1E3E] border border-white/10 rounded-xl px-4 py-3 shadow-xl z-20">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-[#C9A84C]/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-[#C9A84C]" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[10px] text-blue-300/60 font-medium">Contactez-nous</p>
                                    <p class="text-[13px] font-bold text-white">+226 78547805</p>
                                </div>
                            </div>
                        </div>
                        <!-- Verified badge -->
                        <div
                            class="absolute -top-3 -right-3 bg-emerald-500 rounded-full w-12 h-12 flex items-center justify-center shadow-lg shadow-emerald-500/40 z-20">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom fade -->
        <div class="absolute bottom-0 left-0 right-0 h-20 pointer-events-none"
            style="background: linear-gradient(to bottom, transparent, #fff);"></div>
    </section>

    <!-- ═══════════════════ STATS BAR ═══════════════════ -->
    <section class="bg-[#0B1E3E] py-0">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
            <div class="flex flex-wrap items-stretch divide-y sm:divide-y-0 sm:divide-x divide-white/10">
                @foreach ([['3 247', 'Abonnés & Followers', 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'], ['7', "Domaines d'intervention", 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'], ['4 694', "J'aime & Réactions", 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'], ['100%', 'Satisfaction clients', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z']] as [$num, $lbl, $icon])
                    <div class="flex-1 min-w-[200px] flex items-center gap-4 px-8 py-6">
                        <div
                            class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-[#C9A84C]" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="{{ $icon }}" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-white leading-none">{{ $num }}</p>
                            <p class="text-[12px] text-blue-300/60 mt-1 font-medium">{{ $lbl }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ═══════════════════ DOMAINES D'INTERVENTION ═══════════════════ -->
    <section id="domaines" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
            <!-- Section header -->
            <div class="max-w-2xl mb-16">
                <span class="section-badge mb-5 inline-flex">Nos domaines d'intervention</span>
                <h2 class="text-4xl font-bold text-[#0B1E3E] mb-4 leading-tight">
                    Une expertise complète<br>à votre service
                </h2>
                <p class="text-gray-500 text-base leading-relaxed">De la stratégie jusqu'à la soumission finale, nous
                    vous accompagnons sur tous les aspects des marchés publics.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @php
                    $domains = [
                        [
                            'num' => '01',
                            'title' => 'Formation',
                            'desc' =>
                                'Programmes spécialisés en réglementation, procédures et meilleures pratiques des marchés publics.',
                            'icon' =>
                                'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
                            'color' => '#1B4FD8',
                        ],
                        [
                            'num' => '02',
                            'title' => 'Consultation',
                            'desc' =>
                                "Analyse et évaluation experte de vos dossiers pour maximiser vos chances d'attribution de marchés.",
                            'icon' =>
                                'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                            'color' => '#7C3AED',
                        ],
                        [
                            'num' => '03',
                            'title' => 'Appui-Conseil',
                            'desc' =>
                                "Conseils stratégiques personnalisés pour optimiser votre positionnement dans les appels d'offres.",
                            'icon' =>
                                'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z',
                            'color' => '#0891B2',
                        ],
                        [
                            'num' => '04',
                            'title' => 'Accompagnement',
                            'desc' =>
                                "Suivi personnalisé tout au long de votre processus de soumission, de la préparation à l'attribution.",
                            'icon' =>
                                'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
                            'color' => '#059669',
                        ],
                        [
                            'num' => '05',
                            'title' => 'Stratégies',
                            'desc' =>
                                'Élaboration de stratégies gagnantes pour vous différencier dans les marchés publics compétitifs.',
                            'icon' =>
                                'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                            'color' => '#C9A84C',
                        ],
                        [
                            'num' => '06',
                            'title' => 'Montage de dossiers',
                            'desc' =>
                                'Préparation et structuration rigoureuse de vos dossiers de soumission, conformes aux exigences légales.',
                            'icon' =>
                                'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                            'color' => '#DC2626',
                        ],
                        [
                            'num' => '07',
                            'title' => 'Coaching',
                            'desc' =>
                                'Sessions de coaching individuel et collectif pour développer une expertise opérationnelle en marchés publics.',
                            'icon' =>
                                'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
                            'color' => '#EA580C',
                        ],
                    ];
                @endphp

                @foreach ($domains as $d)
                    <div class="service-card group bg-white border border-gray-100 rounded-xl p-6 cursor-default"
                        style="border-left-width: 3px;">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 mt-0.5">
                                <div class="w-11 h-11 rounded-xl flex items-center justify-center"
                                    style="background: {{ $d['color'] }}15;">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        style="color: {{ $d['color'] }};">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                            d="{{ $d['icon'] }}" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-[11px] font-bold tracking-widest"
                                        style="color: {{ $d['color'] }};">{{ $d['num'] }}</span>
                                    <h3 class="text-[15px] font-bold text-[#0B1E3E]">{{ $d['title'] }}</h3>
                                </div>
                                <p class="text-sm text-gray-500 leading-relaxed">{{ $d['desc'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- CTA card -->
                <div class="rounded-xl p-6 flex flex-col justify-between"
                    style="background: linear-gradient(150deg, #0B1E3E 0%, #142B54 100%); border: 1px solid rgba(201,168,76,0.25);">
                    <div>
                        <div class="w-11 h-11 rounded-xl bg-[#C9A84C]/15 flex items-center justify-center mb-4">
                            <svg class="w-5 h-5 text-[#C9A84C]" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="text-[15px] font-bold text-white mb-2">Commencez dès aujourd'hui</h3>
                        <p class="text-sm text-blue-300/70 leading-relaxed">Rejoignez notre plateforme et développez
                            votre expertise.</p>
                    </div>
                    <a href="{{ route('register') }}"
                        class="btn-gold mt-6 inline-flex items-center justify-center gap-2 py-2.5 text-sm rounded-lg px-5">
                        S'inscrire gratuitement
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════ POURQUOI NOUS CHOISIR ═══════════════════ -->
    <section id="pourquoi" class="py-24 bg-[#F7F8FB]">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

                <!-- Left: visual panel -->
                <div class="relative">
                    <!-- Main panel -->
                    <div class="rounded-2xl overflow-hidden shadow-xl"
                        style="background: linear-gradient(150deg, #0B1E3E 0%, #142B54 100%); border: 1px solid rgba(201,168,76,0.2);">
                        <div class="p-8">
                            <img src="/images/logo_expert.png" alt="Logo"
                                class="h-16 w-auto object-contain mb-6">
                            <blockquote
                                class="text-white/90 text-lg font-light leading-relaxed italic mb-6 border-l-2 border-[#C9A84C] pl-4">
                                "Les marchés publics, au service du développement"
                            </blockquote>
                            <div class="grid grid-cols-3 gap-3">
                                @foreach ([['3 247', 'Followers'], ['4 694', "J'aime"], ['7', 'Services']] as [$n, $l])
                                    <div class="bg-white/5 border border-white/10 rounded-xl p-4 text-center">
                                        <p class="text-xl font-bold text-white">{{ $n }}</p>
                                        <p class="text-[11px] text-blue-300/60 mt-1">{{ $l }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="px-8 py-5 border-t border-white/10 flex items-center justify-between">
                            <div class="flex items-center gap-2 text-sm text-blue-300/70">
                                <svg class="w-4 h-4 text-[#C9A84C]" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                +226 78547805
                            </div>
                            <span class="text-[11px] text-blue-300/40">@expertise.marchs</span>
                        </div>
                    </div>
                    <!-- Floating: certified -->
                    <div
                        class="absolute -bottom-5 -right-5 bg-white rounded-xl shadow-xl px-5 py-4 border border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Plateforme certifiée</p>
                                <p class="text-sm font-bold text-gray-900">100% satisfaction</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: benefits list -->
                <div>
                    <span class="section-badge mb-6 inline-flex">Pourquoi nous choisir ?</span>
                    <h2 class="text-3xl sm:text-4xl font-bold text-[#0B1E3E] mb-4 leading-tight">
                        Une approche pratique,<br><span class="animated-underline text-[#1B4FD8]">orientée
                            résultats</span>
                    </h2>
                    <p class="text-gray-500 mb-8 leading-relaxed">Nos experts vous transmettent une connaissance
                        terrain approfondie des marchés publics en Afrique de l'Ouest.</p>
                    <div class="space-y-4">
                        @foreach ([['Expertise reconnue', "Des formateurs avec plus de 10 ans d'expérience dans les marchés publics.", '#1B4FD8'], ['Formation sur mesure', 'Programmes adaptés à votre niveau et à vos objectifs professionnels spécifiques.', '#059669'], ['Contenu vidéo HD', 'Formations disponibles 24h/24 depuis tous vos appareils, en HD.', '#7C3AED'], ['Résultats prouvés', 'Nos apprenants remportent des marchés grâce à notre accompagnement concret.', '#C9A84C']] as [$title, $desc, $color])
                            <div
                                class="flex items-start gap-4 bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5"
                                    style="background: {{ $color }}18;">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        style="color: {{ $color }};">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-[#0B1E3E] text-sm">{{ $title }}</h4>
                                    <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">{{ $desc }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════ COMMENT ÇA MARCHE ═══════════════════ -->
    <section class="py-24 bg-white">
        <div class="max-w-5xl mx-auto px-5 sm:px-8 lg:px-10">
            <div class="text-center mb-16">
                <span class="section-badge mb-5 inline-flex">Comment ça marche ?</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-[#0B1E3E]">Démarrez en 3 étapes simples</h2>
            </div>
            <div class="relative">
                <!-- Connector -->
                <div class="hidden md:block absolute top-[52px] left-[calc(16.67%+28px)] right-[calc(16.67%+28px)] h-px"
                    style="background: linear-gradient(90deg, #C9A84C, #1B4FD8, #C9A84C);"></div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                    @foreach ([['01', 'Créez votre compte', 'Inscrivez-vous en quelques secondes et accédez à votre espace formation personnalisé.', '#1B4FD8'], ['02', 'Choisissez votre formule', 'Sélectionnez l\'abonnement mensuel, trimestriel ou annuel adapté à vos besoins.', '#C9A84C'], ['03', 'Apprenez & Progressez', 'Accédez au catalogue complet, progressez à votre rythme et maîtrisez les marchés publics.', '#059669']] as [$n, $t, $d, $c])
                        <div class="text-center relative">
                            <div class="w-[56px] h-[56px] rounded-xl mx-auto mb-6 flex items-center justify-center step-ring text-white font-black text-lg relative z-10 shadow-lg"
                                style="background: {{ $c }}; box-shadow: 0 8px 24px {{ $c }}40;">
                                {{ $n }}</div>
                            <h3 class="font-bold text-[#0B1E3E] mb-2">{{ $t }}</h3>
                            <p class="text-sm text-gray-500 leading-relaxed max-w-[240px] mx-auto">{{ $d }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="text-center mt-12">
                <a href="{{ route('register') }}"
                    class="btn-gold inline-flex items-center gap-2.5 px-8 py-3.5 text-[15px]">
                    Commencer maintenant — c'est gratuit
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- ═══════════════════ PRICING ═══════════════════ -->
    <section id="pricing" class="py-24 bg-[#F7F8FB]">
        <div class="max-w-5xl mx-auto px-5 sm:px-8 lg:px-10">
            <div class="text-center mb-16">
                <span class="section-badge mb-5 inline-flex">Nos formules d'abonnement</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-[#0B1E3E] mb-3">Tarifs simples & transparents</h2>
                <p class="text-gray-500 max-w-md mx-auto">Aucun frais caché. Annulez à tout moment. Accès illimité au
                    catalogue dès votre inscription.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                @foreach ($plans as $plan)
                    @php $featured = $loop->iteration === 2; @endphp
                    <div
                        class="relative rounded-2xl overflow-hidden flex flex-col {{ $featured ? 'shadow-2xl scale-[1.03]' : 'shadow-md' }}">
                        @if ($featured)
                            <div class="text-center py-2.5 text-xs font-bold tracking-widest uppercase text-[#0B1E3E]"
                                style="background: linear-gradient(90deg, #C9A84C, #F0C96B);">
                                ★ Le plus populaire
                            </div>
                        @endif
                        <div
                            class="{{ $featured ? 'pricing-featured text-white' : 'bg-white' }} p-7 flex flex-col flex-1 {{ !$featured ? 'border border-gray-100' : '' }} {{ $featured ? 'rounded-b-2xl' : 'rounded-2xl' }}">
                            <h3 class="text-lg font-bold {{ $featured ? 'text-white' : 'text-[#0B1E3E]' }} mb-1">
                                {{ $plan->name }}</h3>
                            @if ($plan->description)
                                <p class="text-sm {{ $featured ? 'text-blue-300/70' : 'text-gray-500' }} mb-4">
                                    {{ $plan->description }}</p>
                            @endif
                            <div class="mb-6">
                                <div class="flex items-end gap-1.5">
                                    <span
                                        class="text-4xl font-bold {{ $featured ? 'text-white' : 'text-[#0B1E3E]' }}">{{ number_format($plan->price, 0, '.', ' ') }}</span>
                                    <span
                                        class="{{ $featured ? 'text-blue-300/60' : 'text-gray-400' }} text-sm pb-1">{{ $plan->currency }}</span>
                                </div>
                                <p class="{{ $featured ? 'text-blue-300/50' : 'text-gray-400' }} text-xs mt-1">pour
                                    {{ $plan->duration_days }} jours d'accès complet</p>
                            </div>
                            @if ($plan->features)
                                <ul class="space-y-2.5 flex-1 mb-7">
                                    @foreach ($plan->features as $feature)
                                        <li
                                            class="flex items-start gap-2.5 text-sm {{ $featured ? 'text-blue-100/80' : 'text-gray-600' }}">
                                            <svg class="w-4 h-4 flex-shrink-0 mt-0.5 {{ $featured ? 'text-[#C9A84C]' : 'text-emerald-500' }}"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2.5" d="M5 13l4 4L19 7" />
                                            </svg>
                                            {{ $feature }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                            @if ($featured)
                                <a href="{{ route('register') }}"
                                    class="btn-gold flex items-center justify-center gap-2 py-3 text-sm rounded-xl font-bold">
                                    Choisir cette formule
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </a>
                            @else
                                <a href="{{ route('register') }}"
                                    class="flex items-center justify-center gap-2 py-3 text-sm rounded-xl font-semibold border-2 border-[#0B1E3E]/15 text-[#0B1E3E] hover:bg-[#0B1E3E] hover:text-white transition">
                                    Choisir cette formule
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ═══════════════════ CTA FINAL ═══════════════════ -->
    <section class="cta-bg py-24 relative overflow-hidden">
        <div class="absolute inset-0 pointer-events-none"
            style="background: radial-gradient(ellipse 800px 400px at 50% 50%, rgba(27,79,216,0.15) 0%, transparent 70%);">
        </div>
        <div class="absolute inset-0 pointer-events-none"
            style="background-image: linear-gradient(rgba(255,255,255,.02) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.02) 1px, transparent 1px); background-size: 50px 50px;">
        </div>

        <div class="relative max-w-3xl mx-auto px-5 sm:px-8 text-center">
            <img src="/images/logo_expert.png" alt="Logo" class="h-16 w-auto object-contain mx-auto mb-8">
            <div class="hero-line mx-auto mb-6"></div>
            <h2 class="text-4xl sm:text-5xl font-bold text-white mb-5 leading-tight">
                Prêt à maîtriser les<br><span class="text-gold">Marchés Publics ?</span>
            </h2>
            <p class="text-blue-200/70 text-lg mb-10 max-w-xl mx-auto leading-relaxed font-light">
                Rejoignez notre communauté d'experts et accédez à toutes nos formations pour développer votre
                excellence.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
                <a href="{{ route('register') }}"
                    class="btn-gold inline-flex items-center justify-center gap-2.5 px-9 py-4 text-base">
                    Créer mon compte — c'est gratuit
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
                <a href="{{ route('login') }}"
                    class="btn-outline-white inline-flex items-center justify-center gap-2 px-9 py-4 text-base">
                    Se connecter
                </a>
            </div>
            <!-- Contact strip -->
            <div class="inline-flex items-center gap-6 px-6 py-3.5 rounded-full text-sm font-medium"
                style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
                <span class="flex items-center gap-2 text-blue-200/80">
                    <svg class="w-4 h-4 text-[#C9A84C]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    +226 78547805
                </span>
                <div class="w-px h-4 bg-white/20"></div>
                <span class="text-blue-200/60">@expertise.marchs</span>
            </div>
        </div>
    </section>

    <!-- ═══════════════════ FOOTER ═══════════════════ -->
    <footer class="footer-bg text-gray-500 py-14">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-10">
                <!-- Brand column -->
                <div class="md:col-span-2">
                    <img src="/images/logo_expert.png" alt="Logo" class="h-12 w-auto object-contain mb-4"
                        style="filter: brightness(0) invert(1); opacity: 0.85;">
                    <p class="text-sm leading-relaxed mb-5 max-w-xs">Formation, Consultation, Accompagnement et
                        Coaching pour l'excellence en marchés publics en Afrique de l'Ouest.</p>
                    <div class="flex items-center gap-3">
                        <a href="#"
                            class="w-9 h-9 bg-white/5 border border-white/10 rounded-lg flex items-center justify-center hover:bg-[#C9A84C]/20 hover:border-[#C9A84C]/30 transition">
                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Services -->
                <div>
                    <h4 class="text-white font-semibold text-sm mb-4">Nos services</h4>
                    <ul class="space-y-2.5 text-sm">
                        @foreach (['Formation', 'Consultation', 'Appui-Conseil', 'Accompagnement', 'Stratégies', 'Montage de dossiers', 'Coaching'] as $s)
                            <li class="flex items-center gap-2 hover:text-[#C9A84C] transition cursor-default">
                                <div class="w-1 h-1 rounded-full bg-[#C9A84C]/50"></div>
                                {{ $s }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="text-white font-semibold text-sm mb-4">Contact</h4>
                    <div class="space-y-3 text-sm mb-6">
                        <a href="tel:+22678547805"
                            class="flex items-center gap-3 hover:text-[#C9A84C] transition group">
                            <div
                                class="w-8 h-8 bg-white/5 border border-white/10 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:border-[#C9A84C]/30 transition">
                                <svg class="w-3.5 h-3.5 text-[#C9A84C]" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            +226 78547805
                        </a>
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 bg-white/5 border border-white/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-[#C9A84C]" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                </svg>
                            </div>
                            @expertise.marchs
                        </div>
                    </div>
                    <a href="{{ route('register') }}"
                        class="btn-gold inline-flex items-center gap-1.5 px-5 py-2.5 text-sm rounded-lg">
                        S'inscrire
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Bottom bar -->
            <div
                class="border-t border-white/5 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-600">
                <span>© {{ date('Y') }} Expertise Marchés Publics. Tous droits réservés.</span>
                <span class="text-gray-700">Burkina Faso · Afrique de l'Ouest</span>
            </div>
        </div>
    </footer>

</body>

</html>

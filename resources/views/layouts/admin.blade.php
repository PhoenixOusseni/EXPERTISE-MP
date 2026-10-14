<!DOCTYPE html>
<html lang="fr" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Administration') — Expertise Marchés Publics</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4/dist/index.global.js"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/persist@3.17.4/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.17.4/dist/cdn.min.js"></script>
</head>

<body class="h-full bg-slate-950" x-data="{ sidebarOpen: false }">

    <!-- Mobile overlay -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-20 lg:hidden"
        x-transition></div>

    <!-- Sidebar -->
    <aside
        class="fixed inset-y-0 left-0 w-64 bg-slate-900 flex flex-col z-30 transition-transform duration-300 lg:translate-x-0"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

        <!-- Logo -->
        <div class="flex items-center gap-3 px-4 py-4 border-b border-slate-700/50">
            <img src="/images/logo_expert.png" alt="Logo"
                class="h-9 w-auto object-contain flex-shrink-0 brightness-0 invert opacity-90">
            <div>
                <p class="text-white font-bold text-xs leading-tight">Expertise Marchés Publics</p>
                <p class="text-slate-400 text-xs mt-0.5">Administration</p>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
            <p class="px-3 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tableau de bord</p>
            <a href="{{ route('admin.dashboard') }}"
                class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 12a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1v-7z" />
                </svg>
                Tableau de bord
            </a>

            <div class="pt-4 pb-2">
                <p class="px-3 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Contenu</p>
            </div>
            <a href="{{ route('admin.videos.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.videos.*') ? 'active' : '' }}">
                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 10l4.553-2.069A1 1 0 0121 8.867v6.266a1 1 0 01-1.447.902L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                Vidéos
            </a>
            <a href="{{ route('admin.categories.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                Catégories
            </a>

            <div class="pt-4 pb-2">
                <p class="px-3 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Abonnements</p>
            </div>
            <a href="{{ route('admin.plans.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.plans.*') ? 'active' : '' }}">
                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                Formules
            </a>
            <a href="{{ route('admin.subscriptions.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">
                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                Abonnements
            </a>

            <div class="pt-4 pb-2">
                <p class="px-3 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Utilisateurs</p>
            </div>
            <a href="{{ route('admin.users.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Apprenants
            </a>

            <div class="pt-4 pb-2">
                <p class="px-3 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Support</p>
            </div>
            <a href="{{ route('admin.contact.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.contact.*') ? 'active' : '' }}">
                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Messages
                @php $pendingMessages = \App\Models\ContactRequest::where('status', \App\Enums\ContactRequestStatus::EnAttente)->count(); @endphp
                @if ($pendingMessages > 0)
                    <span
                        class="ml-auto bg-red-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full min-w-[1.25rem] text-center">{{ $pendingMessages }}</span>
                @endif
            </a>
        </nav>

        <!-- Bottom: user info -->
        <div class="px-3 py-4 border-t border-slate-700/50" x-data="{ open: false }">
            <button @click="open = !open" @click.outside="open = false"
                class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-700/60 transition">
                <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <span
                        class="text-xs font-bold text-white">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</span>
                </div>
                <div class="flex-1 text-left min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-400 truncate">Administrateur</p>
                </div>
                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                </svg>
            </button>
            <div x-show="open" x-cloak x-transition
                class="mt-1 bg-slate-800 rounded-lg border border-slate-700/50 py-1">
                <a href="{{ route('admin.profile.password') }}"
                    class="flex items-center gap-2 px-3 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                    Changer le mot de passe
                </a>
                <a href="{{ route('catalogue') }}"
                    class="flex items-center gap-2 px-3 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    Voir le catalogue
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-2 px-3 py-2 text-sm text-red-400 hover:text-red-300 hover:bg-slate-700/60 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main content area -->
    <div class="lg:pl-64 flex flex-col min-h-screen">
        <!-- Top bar -->
        <header class="bg-white border-b border-gray-200 sticky top-0 z-10">
            <div class="flex items-center gap-4 px-4 sm:px-6 h-16">
                <button @click="sidebarOpen = true"
                    class="lg:hidden p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div class="flex-1">
                    <h1 class="text-base font-semibold text-gray-800">@yield('page-title', 'Administration')</h1>
                    @hasSection('breadcrumb')
                        <nav class="text-xs text-gray-400 mt-0.5">@yield('breadcrumb')</nav>
                    @endif
                </div>

                <div class="flex items-center gap-2">
                    @yield('header-actions')
                </div>
            </div>
        </header>

        <!-- Page content -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8 bg-gray-50">
            @include('components.alert')
            @yield('content')
        </main>
    </div>

</body>

</html>

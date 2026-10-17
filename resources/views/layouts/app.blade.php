<!DOCTYPE html>
<html lang="fr" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Expertise Marchés Publics') — Formation en marchés publics</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4/dist/index.global.js"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/persist@3.17.4/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.17.4/dist/cdn.min.js"></script>
</head>
<body class="h-full" x-data>
    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center gap-8">
                    <a href="{{ route('catalogue') }}" class="flex items-center gap-2.5">
                        <img src="/images/logo_expert.png" alt="Expertise Marchés Publics" class="h-10 w-auto object-contain">
                        <div class="hidden sm:block">
                            <span class="text-sm font-bold text-blue-900 leading-tight block">Expertise Marchés Publics</span>
                            <span class="text-xs text-blue-400">Formation &amp; Conseil</span>
                        </div>
                    </a>
                    <!-- Nav links -->
                    <div class="hidden md:flex items-center gap-1">
                        <a href="{{ route('catalogue') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('catalogue') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} transition">
                            Catalogue
                        </a>
                        <a href="{{ route('formules') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('formules') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} transition">
                            Formules
                        </a>
                        <a href="{{ route('mon-abonnement') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('mon-abonnement') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} transition">
                            Mon abonnement
                        </a>
                        @if(auth()->user()->isAdmin() || auth()->user()->hasActiveSubscription())
                        <a href="{{ route('publications.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('publications.*') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} transition">
                            Publications
                        </a>
                        @endif
                        <a href="{{ route('contact.create') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('contact.*') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} transition">
                            Nous contacter
                        </a>
                    </div>
                </div>

                <!-- User menu -->
                <div class="flex items-center gap-3">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="hidden sm:flex btn btn-secondary btn-sm gap-1.5">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                                Admin
                            </a>
                        @endif
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.outside="open = false"
                                class="flex items-center gap-2 px-3 py-1.5 rounded-lg hover:bg-gray-100 transition">
                                @if(auth()->user()->avatarUrl())
                                    <img src="{{ auth()->user()->avatarUrl() }}" alt="" class="w-7 h-7 rounded-full object-cover">
                                @else
                                    <div class="w-7 h-7 bg-indigo-100 rounded-full flex items-center justify-center">
                                        <span class="text-xs font-semibold text-indigo-700">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                    </div>
                                @endif
                                <span class="hidden sm:block text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" x-cloak x-transition
                                class="absolute right-0 mt-1 w-52 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
                                <div class="px-3 py-2 border-b border-gray-100 flex items-center gap-2.5">
                                    @if(auth()->user()->avatarUrl())
                                        <img src="{{ auth()->user()->avatarUrl() }}" alt="" class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                                    @else
                                        <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0">
                                            <span class="text-xs font-semibold text-indigo-700">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="text-xs font-medium text-gray-800 truncate">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('profile.show') }}"
                                   class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 hover:bg-gray-50 transition">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Mon profil
                                </a>
                                <a href="{{ route('contact.index') }}"
                                   class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 hover:bg-gray-50 transition">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    Mes demandes
                                </a>
                                <div class="border-t border-gray-100 mt-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                            Se déconnecter
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @include('components.alert')
        @yield('content')
    </main>

    <footer class="mt-16 border-t border-gray-200 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-sm text-gray-500">
            <div class="flex items-center gap-2">
                <img src="/images/logo_expert.png" alt="Logo" class="h-7 w-auto object-contain opacity-70">
                <span>© {{ date('Y') }} Expertise Marchés Publics</span>
            </div>
            <div class="flex items-center gap-3 text-gray-400">
                <span class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" viewBox="0 0 24 24" fill="#25D366"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    +226 78547805
                </span>
                <span class="opacity-40">·</span>
                <span class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    +226 46788578
                </span>
            </div>
        </div>
    </footer>
</body>
</html>

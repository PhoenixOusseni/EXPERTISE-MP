@extends('layouts.admin')
@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')

@section('content')
    <!-- Stats grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-5">
        <div class="stat-card">
            <div class="stat-icon bg-indigo-100">
                <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['apprenants']['total']) }}</p>
                <p class="text-sm text-gray-500">Apprenants</p>
                <p class="text-xs text-emerald-600 mt-0.5 font-medium">{{ $stats['apprenants']['abonnes'] }}
                    abonné{{ $stats['apprenants']['abonnes'] > 1 ? 's' : '' }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-violet-100">
                <svg class="w-6 h-6 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 10l4.553-2.069A1 1 0 0121 8.867v6.266a1 1 0 01-1.447.902L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['videos']['total']) }}</p>
                <p class="text-sm text-gray-500">Vidéos</p>
                <p class="text-xs text-emerald-600 mt-0.5 font-medium">{{ $stats['videos']['publiees'] }}
                    publiée{{ $stats['videos']['publiees'] > 1 ? 's' : '' }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-emerald-100">
                <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['abonnements']['actifs']) }}</p>
                <p class="text-sm text-gray-500">Abonnements actifs</p>
                @if ($stats['abonnements']['en_attente'] > 0)
                    <p class="text-xs text-amber-600 mt-0.5 font-medium">{{ $stats['abonnements']['en_attente'] }} en
                        attente</p>
                @endif
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-amber-100">
                <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">
                    {{ number_format($stats['abonnements']['revenus_mois'], 0, '.', ' ') }}</p>
                <p class="text-sm text-gray-500">Revenus ce mois (XOF)</p>
                <p class="text-xs text-gray-400 mt-0.5">Total :
                    {{ number_format($stats['abonnements']['revenus_total'], 0, '.', ' ') }}</p>
            </div>
        </div>
    </div>

    <!-- Publications & Publicités stats row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
        <div class="stat-card">
            <div class="stat-icon bg-blue-100">
                <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['publications']['total']) }}</p>
                <p class="text-sm text-gray-500">Publications</p>
                <p class="text-xs text-emerald-600 mt-0.5 font-medium">{{ $stats['publications']['publiees'] }} publiée{{ $stats['publications']['publiees'] > 1 ? 's' : '' }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-sky-100">
                <svg class="w-6 h-6 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['publications']['vues']) }}</p>
                <p class="text-sm text-gray-500">Vues publications</p>
                <p class="text-xs text-gray-400 mt-0.5">Moy. {{ $stats['publications']['moy_vues'] }} / publication</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-rose-100">
                <svg class="w-6 h-6 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['publicites']['total']) }}</p>
                <p class="text-sm text-gray-500">Publicités</p>
                <p class="text-xs text-emerald-600 mt-0.5 font-medium">{{ $stats['publicites']['actives'] }} active{{ $stats['publicites']['actives'] > 1 ? 's' : '' }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-orange-100">
                <svg class="w-6 h-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['publicites']['expirees']) }}</p>
                <p class="text-sm text-gray-500">Publicités expirées</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $stats['publicites']['total'] - $stats['publicites']['actives'] - $stats['publicites']['expirees'] }} à venir</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent subscriptions -->
        <div class="lg:col-span-2 card">
            <div class="card-header flex items-center justify-between">
                <h3 class="font-semibold text-gray-800">Derniers abonnements</h3>
                <a href="{{ route('admin.subscriptions.index') }}"
                    class="text-xs text-indigo-600 font-medium hover:text-indigo-800">Voir tout →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th>Apprenant</th>
                            <th>Formule</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentSubscriptions as $sub)
                            <tr>
                                <td>
                                    <p class="font-medium text-gray-900 text-sm">{{ $sub->user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $sub->user->email }}</p>
                                </td>
                                <td class="text-sm">{{ $sub->plan->name }}</td>
                                <td>
                                    @switch($sub->status->value)
                                        @case('active')
                                            <span class="badge-green">Actif</span>
                                        @break

                                        @case('expired')
                                            <span class="badge-gray">Expiré</span>
                                        @break

                                        @case('pending')
                                            <span class="badge-yellow">En attente</span>
                                        @break

                                        @case('cancelled')
                                            <span class="badge-red">Annulé</span>
                                        @break
                                    @endswitch
                                </td>
                                <td class="text-sm text-gray-500">{{ $sub->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.subscriptions.show', $sub) }}"
                                        class="text-xs text-indigo-600 font-medium hover:text-indigo-800">Voir</a>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-8 text-gray-400">Aucun abonnement</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick actions + alerts -->
            <div class="space-y-5">
                <!-- Quick actions -->
                <div class="card card-body">
                    <h3 class="font-semibold text-gray-800 mb-4">Actions rapides</h3>
                    <div class="space-y-2">
                        <a href="{{ route('admin.videos.create') }}"
                            class="flex items-center gap-3 p-3 rounded-lg bg-indigo-50 hover:bg-indigo-100 transition group">
                            <div class="w-9 h-9 bg-indigo-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-indigo-700">Ajouter une vidéo</p>
                                <p class="text-xs text-indigo-500">Publier un nouveau contenu</p>
                            </div>
                        </a>
                        <a href="{{ route('admin.subscriptions.index') }}?status=pending"
                            class="flex items-center gap-3 p-3 rounded-lg bg-amber-50 hover:bg-amber-100 transition">
                            <div class="w-9 h-9 bg-amber-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-amber-700">Valider les demandes</p>
                                <p class="text-xs text-amber-500">{{ $stats['abonnements']['en_attente'] }} en attente de
                                    validation</p>
                            </div>
                        </a>
                        <a href="{{ route('admin.users.index') }}"
                            class="flex items-center gap-3 p-3 rounded-lg bg-slate-50 hover:bg-slate-100 transition">
                            <div class="w-9 h-9 bg-slate-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-700">Gérer les apprenants</p>
                                <p class="text-xs text-slate-500">{{ $stats['apprenants']['total'] }} inscrits</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Alert: expiring soon -->
                @if ($stats['abonnements']['expirent_sous_7_jours'] > 0)
                    <div class="card card-body bg-amber-50 border-amber-200">
                        <div class="flex items-center gap-3">
                            <svg class="w-8 h-8 text-amber-500 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div>
                                <p class="font-semibold text-amber-800 text-sm">Expirations imminentes</p>
                                <p class="text-xs text-amber-600">{{ $stats['abonnements']['expirent_sous_7_jours'] }}
                                    abonnement{{ $stats['abonnements']['expirent_sous_7_jours'] > 1 ? 's expirent' : ' expire' }}
                                    dans moins de 7 jours</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Video stats -->
                <div class="card card-body">
                    <h3 class="font-semibold text-gray-800 mb-3">Statistiques vidéos</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Total vues</span>
                            <span class="font-semibold text-gray-900">{{ number_format($stats['videos']['vues']) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Vidéos publiées</span>
                            <span class="font-semibold text-gray-900">{{ $stats['videos']['publiees'] }} /
                                {{ $stats['videos']['total'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Publications stats -->
                <div class="card card-body">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-semibold text-gray-800">Statistiques publications</h3>
                        <a href="{{ route('admin.publications.index') }}" class="text-xs text-indigo-600 font-medium hover:text-indigo-800">Gérer →</a>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Total envoyées</span>
                            <span class="font-semibold text-gray-900">{{ $stats['publications']['publiees'] }} / {{ $stats['publications']['total'] }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Total vues</span>
                            <span class="font-semibold text-gray-900">{{ number_format($stats['publications']['vues']) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Moy. vues / publication</span>
                            <span class="font-semibold text-gray-900">{{ $stats['publications']['moy_vues'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Publicités stats -->
                <div class="card card-body">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-semibold text-gray-800">Statistiques publicités</h3>
                        <a href="{{ route('admin.publicites.index') }}" class="text-xs text-indigo-600 font-medium hover:text-indigo-800">Gérer →</a>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Total publicités</span>
                            <span class="font-semibold text-gray-900">{{ $stats['publicites']['total'] }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Actives en ce moment</span>
                            <span class="font-semibold text-emerald-600">{{ $stats['publicites']['actives'] }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Expirées</span>
                            <span class="font-semibold text-gray-400">{{ $stats['publicites']['expirees'] }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">À venir</span>
                            <span class="font-semibold text-gray-900">{{ $stats['publicites']['total'] - $stats['publicites']['actives'] - $stats['publicites']['expirees'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

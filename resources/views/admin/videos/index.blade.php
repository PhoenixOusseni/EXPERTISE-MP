@extends('layouts.admin')
@section('title', 'Vidéos')
@section('page-title', 'Gestion des vidéos')

@section('header-actions')
    <a href="{{ route('admin.videos.create') }}" class="btn-primary btn-sm">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Ajouter une vidéo
    </a>
@endsection

@section('content')
    <!-- Filters -->
    <div class="card mb-5">
        <div class="p-4">
            <form method="GET" action="{{ route('admin.videos.index') }}" class="flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-40">
                    <label class="form-label text-xs">Recherche</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-input"
                        placeholder="Titre de la vidéo...">
                </div>
                <div class="w-44">
                    <label class="form-label text-xs">Catégorie</label>
                    <select name="category_id" class="form-input">
                        <option value="">Toutes</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-44">
                    <label class="form-label text-xs">Groupe cible</label>
                    <select name="audience" class="form-input">
                        <option value="">Tous</option>
                        @foreach (\App\Enums\LearnerProfile::cases() as $p)
                            @if($p->value === 'autre') @continue @endif
                            <option value="{{ $p->value }}" {{ request('audience') === $p->value ? 'selected' : '' }}>
                                {{ $p->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-40">
                    <label class="form-label text-xs">Statut</label>
                    <select name="is_published" class="form-input">
                        <option value="">Tous</option>
                        <option value="1" {{ request('is_published') === '1' ? 'selected' : '' }}>Publiées</option>
                        <option value="0" {{ request('is_published') === '0' ? 'selected' : '' }}>Brouillons</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary btn-sm">Filtrer</button>
                @if (request()->hasAny(['search', 'category_id', 'is_published']))
                    <a href="{{ route('admin.videos.index') }}" class="btn-secondary btn-sm">Réinitialiser</a>
                @endif
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="table-auto w-full">
                <thead>
                    <tr>
                        <th class="w-16">Miniature</th>
                        <th>Titre</th>
                        <th>Groupe</th>
                        <th>Catégorie</th>
                        <th>Type</th>
                        <th>Vues</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th class="text-right pr-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($videos as $video)
                        <tr>
                            <td>
                                <div class="w-14 h-10 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                                    @if ($video->thumbnail_path)
                                        <img src="{{ Storage::disk('public')->url($video->thumbnail_path) }}"
                                            class="w-full h-full object-cover" alt="">
                                    @else
                                        <div class="w-full h-full bg-indigo-50 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-indigo-300" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 10l4.553-2.069A1 1 0 0121 8.867v6.266a1 1 0 01-1.447.902L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <p class="font-medium text-gray-900 text-sm max-w-xs truncate">{{ $video->title }}</p>
                                <p class="text-xs text-gray-400">{{ $video->slug }}</p>
                            </td>
                            <td>
                                @if ($video->audience)
                                    <span
                                        class="{{ $video->audience === \App\Enums\LearnerProfile::Entreprise ? 'badge-blue' : 'badge-yellow' }} text-xs">
                                        {{ $video->audience->label() }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">—</span>
                                @endif
                            </td>
                            <td class="text-sm text-gray-600">{{ $video->category?->name ?? '—' }}</td>
                            <td>
                                <span class="badge-gray capitalize">{{ $video->source_type->value }}</span>
                            </td>
                            <td class="text-sm text-gray-600">{{ number_format($video->views_count) }}</td>
                            <td>
                                @if ($video->is_published)
                                    <span class="badge-green">Publiée</span>
                                @else
                                    <span class="badge-gray">Brouillon</span>
                                @endif
                            </td>
                            <td class="text-sm text-gray-500">{{ $video->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="flex items-center justify-end gap-1 pr-2">
                                    <a href="{{ route('admin.videos.edit', $video) }}"
                                        class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition"
                                        title="Modifier">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    @if ($video->is_published)
                                        <form method="POST" action="{{ route('admin.videos.unpublish', $video) }}">
                                            @csrf
                                            <button type="submit"
                                                class="p-1.5 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition"
                                                title="Dépublier">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.videos.publish', $video) }}">
                                            @csrf
                                            <button type="submit"
                                                class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition"
                                                title="Publier">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.videos.destroy', $video) }}"
                                        onsubmit="return confirm('Supprimer définitivement cette vidéo ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                                            title="Supprimer">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-200" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M15 10l4.553-2.069A1 1 0 0121 8.867v6.266a1 1 0 01-1.447.902L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                <p>Aucune vidéo trouvée</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($videos->hasPages())
            <div class="px-4 py-4 border-t border-gray-100">
                {{ $videos->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection

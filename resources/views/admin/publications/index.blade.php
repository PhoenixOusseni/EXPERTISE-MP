@extends('layouts.admin')
@section('title', 'Publications')
@section('page-title', 'Publications')

@section('header-actions')
    <a href="{{ route('admin.publications.create') }}" class="btn-primary btn-sm">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nouvelle publication
    </a>
@endsection

@section('content')
    {{-- Filters --}}
    <form method="GET" class="card card-body mb-5 flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-[200px]">
            <label class="form-label">Recherche</label>
            <input type="text" name="search" value="{{ request('search') }}" class="form-input" placeholder="Titre...">
        </div>
        <div>
            <label class="form-label">Statut</label>
            <select name="is_published" class="form-input">
                <option value="">Tous</option>
                <option value="1" {{ request('is_published') === '1' ? 'selected' : '' }}>Publiées</option>
                <option value="0" {{ request('is_published') === '0' ? 'selected' : '' }}>Brouillons</option>
            </select>
        </div>
        <button type="submit" class="btn-primary btn-sm">Filtrer</button>
        <a href="{{ route('admin.publications.index') }}" class="btn-secondary btn-sm">Réinitialiser</a>
    </form>

    {{-- Alerts --}}
    @if (session('success'))
        <div class="alert-success mb-4">{{ session('success') }}</div>
    @endif

    {{-- Table --}}
    <div class="card overflow-hidden">
        <table class="table w-full">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Statut</th>
                    <th>Fichier joint</th>
                    <th>Date publication</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($publications as $pub)
                    <tr>
                        <td class="font-medium text-gray-800 max-w-xs truncate">{{ $pub->title }}</td>
                        <td>
                            @if ($pub->is_published)
                                <span class="badge-green">Publiée</span>
                            @else
                                <span class="badge-gray">Brouillon</span>
                            @endif
                        </td>
                        <td>
                            @if ($pub->file_path)
                                <span class="text-xs text-blue-600 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                    {{ Str::limit($pub->file_name ?? basename($pub->file_path), 24) }}
                                </span>
                            @else
                                <span class="text-gray-400 text-xs">—</span>
                            @endif
                        </td>
                        <td class="text-sm text-gray-500">
                            {{ $pub->published_at?->format('d/m/Y') ?? '—' }}
                        </td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if ($pub->is_published)
                                    <form method="POST" action="{{ route('admin.publications.unpublish', $pub) }}">
                                        @csrf
                                        <button type="submit" class="btn-secondary btn-xs">Dépublier</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.publications.publish', $pub) }}">
                                        @csrf
                                        <button type="submit" class="btn-primary btn-xs">Publier</button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.publications.edit', $pub) }}" class="btn-secondary btn-xs">Modifier</a>
                                <form method="POST" action="{{ route('admin.publications.destroy', $pub) }}"
                                    onsubmit="return confirm('Supprimer cette publication ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-danger btn-xs">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-gray-400 py-10">Aucune publication.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $publications->withQueryString()->links() }}</div>
@endsection

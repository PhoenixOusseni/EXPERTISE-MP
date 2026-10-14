@extends('layouts.admin')
@section('title', 'Catégories')
@section('page-title', 'Catégories')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form -->
        <div class="card card-body h-fit">
            <h3 class="font-semibold text-gray-800 mb-5">{{ isset($editCategory) ? 'Modifier' : 'Ajouter' }} une catégorie
            </h3>
            <form method="POST"
                action="{{ isset($editCategory) ? route('admin.categories.update', $editCategory) : route('admin.categories.store') }}"
                class="space-y-4">
                @csrf
                @if (isset($editCategory))
                    @method('PUT')
                @endif
                <div>
                    <label class="form-label">Nom <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $editCategory->name ?? '') }}"
                        class="form-input" placeholder="Nom de la catégorie" required>
                    @error('name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="3" class="form-input resize-none" placeholder="Description optionnelle...">{{ old('description', $editCategory->description ?? '') }}</textarea>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="btn-primary flex-1 justify-center">
                        {{ isset($editCategory) ? 'Enregistrer' : 'Créer' }}
                    </button>
                    @if (isset($editCategory))
                        <a href="{{ route('admin.categories.index') }}" class="btn-secondary">Annuler</a>
                    @endif
                </div>
            </form>
        </div>

        <!-- List -->
        <div class="lg:col-span-2 card">
            <div class="card-header flex items-center justify-between">
                <h3 class="font-semibold text-gray-800">Toutes les catégories</h3>
                <span class="badge-blue">{{ $categories->total() }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Vidéos</th>
                            <th class="text-right pr-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $cat)
                            <tr>
                                <td>
                                    <p class="font-medium text-gray-900">{{ $cat->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $cat->slug }}</p>
                                </td>
                                <td class="text-sm text-gray-500 max-w-xs truncate">{{ $cat->description ?? '—' }}</td>
                                <td>
                                    <span class="badge-blue">{{ $cat->videos_count }}</span>
                                </td>
                                <td>
                                    <div class="flex items-center justify-end gap-1 pr-2">
                                        <a href="{{ route('admin.categories.edit', $cat) }}"
                                            class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}"
                                            onsubmit="return confirm('Supprimer cette catégorie ? Les vidéos associées ne seront pas supprimées.')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
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
                                <td colspan="4" class="text-center py-10 text-gray-400">Aucune catégorie créée</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($categories->hasPages())
                <div class="px-4 py-4 border-t border-gray-100">{{ $categories->links() }}</div>
            @endif
        </div>
    </div>
@endsection

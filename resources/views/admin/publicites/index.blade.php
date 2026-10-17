@extends('layouts.admin')
@section('title', 'Publicités')
@section('page-title', 'Publicités')

@section('header-actions')
    <a href="{{ route('admin.publicites.create') }}" class="btn-primary btn-sm">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nouvelle publicité
    </a>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert-success mb-4">{{ session('success') }}</div>
    @endif

    <div class="card overflow-hidden">
        <table class="table w-full">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Titre</th>
                    <th>Date début</th>
                    <th>Date fin</th>
                    <th>Statut</th>
                    <th>Ordre</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($publicites as $pub)
                    @php
                        $now = now()->toDateString();
                        $isRunning = $pub->is_active
                            && $pub->start_date->toDateString() <= $now
                            && $pub->end_date->toDateString() >= $now;
                        $isExpired = $pub->end_date->toDateString() < $now;
                    @endphp
                    <tr>
                        <td>
                            <img src="{{ Storage::disk('public')->url($pub->image_path) }}"
                                class="w-20 h-12 object-cover rounded-lg border border-gray-100" alt="">
                        </td>
                        <td class="font-medium text-gray-800">{{ $pub->title ?? '—' }}</td>
                        <td class="text-sm text-gray-500">{{ $pub->start_date->format('d/m/Y') }}</td>
                        <td class="text-sm text-gray-500">{{ $pub->end_date->format('d/m/Y') }}</td>
                        <td>
                            @if ($isExpired)
                                <span class="badge-gray">Expirée</span>
                            @elseif ($isRunning)
                                <span class="badge-green">Active</span>
                            @elseif (!$pub->is_active)
                                <span class="badge-gray">Désactivée</span>
                            @else
                                <span class="badge-blue">Programmée</span>
                            @endif
                        </td>
                        <td class="text-sm text-gray-500">{{ $pub->sort_order }}</td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.publicites.edit', $pub) }}" class="btn-secondary btn-xs">Modifier</a>
                                <form method="POST" action="{{ route('admin.publicites.destroy', $pub) }}"
                                    onsubmit="return confirm('Supprimer cette publicité ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-danger btn-xs">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-gray-400 py-10">Aucune publicité.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $publicites->links() }}</div>
@endsection

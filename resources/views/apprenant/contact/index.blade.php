@extends('layouts.app')
@section('title', 'Mes demandes')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Mes demandes</h1>
            <p class="text-sm text-gray-500 mt-1">Suivez l'état de vos messages et consultez les réponses.</p>
        </div>
        <a href="{{ route('contact.create') }}" class="btn btn-primary btn-sm gap-1.5">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nouveau message
        </a>
    </div>

    @if($requests->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center">
        <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </div>
        <p class="text-gray-500 text-sm">Vous n'avez encore envoyé aucune demande.</p>
        <a href="{{ route('contact.create') }}" class="btn btn-primary btn-sm mt-4 inline-flex gap-1.5">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Envoyer un message
        </a>
    </div>
    @else
    <div class="space-y-3">
        @foreach($requests as $req)
        <a href="{{ route('contact.show', $req) }}"
            class="block bg-white rounded-2xl border border-gray-200 hover:border-indigo-200 hover:shadow-sm transition p-5">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        @php
                            $typeBadge = match($req->type->value) {
                                'demande' => 'bg-blue-100 text-blue-700',
                                'rdv'     => 'bg-purple-100 text-purple-700',
                                default   => 'bg-gray-100 text-gray-600',
                            };
                        @endphp
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $typeBadge }}">{{ $req->type->label() }}</span>
                        @if($req->isReplied())
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Répondu
                        </span>
                        @endif
                    </div>
                    <p class="font-semibold text-gray-900 truncate">{{ $req->subject }}</p>
                    <p class="text-sm text-gray-500 mt-0.5 line-clamp-1">{{ $req->message }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <span class="{{ $req->status->badgeClass() }} text-xs px-2.5 py-1 rounded-full font-medium">{{ $req->status->label() }}</span>
                    <p class="text-xs text-gray-400 mt-1">{{ $req->created_at->diffForHumans() }}</p>
                </div>
            </div>
        </a>
        @endforeach
    </div>

    @if($requests->hasPages())
    <div class="mt-6">{{ $requests->links() }}</div>
    @endif
    @endif
</div>
@endsection

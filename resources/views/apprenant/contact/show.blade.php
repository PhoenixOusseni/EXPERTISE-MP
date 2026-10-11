@extends('layouts.app')
@section('title', 'Détail de la demande')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('contact.index') }}" class="text-gray-400 hover:text-gray-600 transition">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-gray-900">{{ $contactRequest->subject }}</h1>
            <p class="text-xs text-gray-400 mt-0.5">Envoyée {{ $contactRequest->created_at->diffForHumans() }}</p>
        </div>
        <div class="ml-auto">
            <span class="{{ $contactRequest->status->badgeClass() }} text-xs px-3 py-1 rounded-full font-medium">
                {{ $contactRequest->status->label() }}
            </span>
        </div>
    </div>

    <!-- Votre message -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 mb-4">
        <div class="flex items-center gap-2 mb-3">
            @php
                $typeBadge = match($contactRequest->type->value) {
                    'demande' => 'bg-blue-100 text-blue-700',
                    'rdv'     => 'bg-purple-100 text-purple-700',
                    default   => 'bg-gray-100 text-gray-600',
                };
            @endphp
            <span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $typeBadge }}">{{ $contactRequest->type->label() }}</span>
            <span class="text-xs text-gray-400">de {{ $contactRequest->name }}</span>
        </div>
        <p class="text-sm text-gray-800 whitespace-pre-wrap leading-relaxed">{{ $contactRequest->message }}</p>
    </div>

    <!-- Réponse de l'administrateur -->
    @if($contactRequest->admin_reply)
    <div class="bg-indigo-50 border border-indigo-200 rounded-2xl p-5">
        <div class="flex items-center gap-2 mb-3">
            <div class="w-7 h-7 bg-indigo-600 rounded-full flex items-center justify-center">
                <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-indigo-900">Réponse de l'équipe</p>
                @if($contactRequest->replied_at)
                <p class="text-xs text-indigo-400">{{ $contactRequest->replied_at->diffForHumans() }}</p>
                @endif
            </div>
        </div>
        <p class="text-sm text-indigo-900 whitespace-pre-wrap leading-relaxed">{{ $contactRequest->admin_reply }}</p>
    </div>
    @else
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 flex items-center gap-3">
        <svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="text-sm text-amber-800">Votre demande est en cours de traitement. Vous serez notifié dès qu'une réponse sera disponible.</p>
    </div>
    @endif

    <div class="mt-5 text-center">
        <a href="{{ route('contact.create') }}" class="text-sm text-indigo-600 hover:underline">Envoyer une nouvelle demande</a>
    </div>
</div>
@endsection

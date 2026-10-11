@extends('layouts.admin')
@section('title', 'Message — ' . $contactRequest->subject)
@section('page-title', 'Détail du message')
@section('breadcrumb')
    <a href="{{ route('admin.contact.index') }}">Messages</a>
    <span class="mx-1">/</span>
    <span>{{ Str::limit($contactRequest->subject, 40) }}</span>
@endsection

@section('content')
<div class="max-w-3xl">

    <!-- Header card -->
    <div class="card p-5 mb-5">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-gray-900">{{ $contactRequest->subject }}</h2>
                <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                    @php
                        $typeBadge = match($contactRequest->type->value) {
                            'demande' => 'bg-blue-100 text-blue-700',
                            'rdv'     => 'bg-purple-100 text-purple-700',
                            default   => 'bg-gray-100 text-gray-600',
                        };
                    @endphp
                    <span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $typeBadge }}">{{ $contactRequest->type->label() }}</span>
                    <span class="{{ $contactRequest->status->badgeClass() }} text-xs px-2.5 py-1 rounded-full font-medium">{{ $contactRequest->status->label() }}</span>
                    <span class="text-xs text-gray-400">Reçu le {{ $contactRequest->created_at->format('d/m/Y à H:i') }}</span>
                </div>
            </div>

            <!-- Quick status update -->
            <form method="POST" action="{{ route('admin.contact.status', $contactRequest) }}" class="flex items-center gap-2">
                @csrf
                <select name="status" class="form-input py-1.5 text-sm">
                    @foreach(\App\Enums\ContactRequestStatus::cases() as $s)
                    <option value="{{ $s->value }}" {{ $contactRequest->status === $s ? 'selected' : '' }}>{{ $s->label() }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-secondary btn-sm">Changer</button>
            </form>
        </div>

        <!-- Sender info -->
        <div class="mt-4 flex items-center gap-3 bg-gray-50 rounded-xl p-3">
            <div class="w-9 h-9 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-sm font-semibold text-indigo-700">{{ strtoupper(substr($contactRequest->name, 0, 1)) }}</span>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-800">{{ $contactRequest->name }}</p>
                <p class="text-xs text-gray-500">{{ $contactRequest->email }}</p>
            </div>
            @if($contactRequest->user)
            <a href="{{ route('admin.users.show', $contactRequest->user) }}" class="ml-auto text-xs text-indigo-600 hover:underline">Voir le profil →</a>
            @endif
        </div>
    </div>

    <!-- Message de l'apprenant -->
    <div class="card p-5 mb-5">
        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Message</h3>
        <p class="text-sm text-gray-800 whitespace-pre-wrap leading-relaxed">{{ $contactRequest->message }}</p>
    </div>

    <!-- Formulaire de réponse -->
    <div class="card p-5">
        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">
            {{ $contactRequest->isReplied() ? 'Modifier la réponse' : 'Répondre' }}
        </h3>

        <form method="POST" action="{{ route('admin.contact.reply', $contactRequest) }}" class="space-y-4">
            @csrf

            <div>
                <label class="form-label">Réponse à l'apprenant <span class="text-red-500">*</span></label>
                <textarea name="admin_reply" rows="7"
                    class="form-input mt-1 resize-none @error('admin_reply') border-red-300 @enderror"
                    placeholder="Tapez votre réponse ici...">{{ old('admin_reply', $contactRequest->admin_reply) }}</textarea>
                @error('admin_reply') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">Notes internes <span class="text-xs text-gray-400">(non visible par l'apprenant)</span></label>
                <textarea name="admin_notes" rows="3"
                    class="form-input mt-1 resize-none @error('admin_notes') border-red-300 @enderror"
                    placeholder="Notes pour l'équipe...">{{ old('admin_notes', $contactRequest->admin_notes) }}</textarea>
                @error('admin_notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">Statut après réponse</label>
                <select name="status" class="form-input mt-1">
                    @foreach(\App\Enums\ContactRequestStatus::cases() as $s)
                    <option value="{{ $s->value }}" {{ $contactRequest->status === $s ? 'selected' : '' }}
                        {{ $s->value === 'resolu' && !$contactRequest->isReplied() ? 'selected' : '' }}>
                        {{ $s->label() }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="pt-2 flex items-center justify-between">
                <a href="{{ route('admin.contact.index') }}" class="text-sm text-gray-500 hover:text-gray-700 transition">← Retour à la liste</a>
                <button type="submit" class="btn-primary gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    {{ $contactRequest->isReplied() ? 'Mettre à jour la réponse' : 'Envoyer la réponse' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

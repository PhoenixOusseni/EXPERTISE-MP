<?php

namespace App\Http\Controllers\Web\Admin;

use App\Enums\ContactRequestStatus;
use App\Http\Controllers\Controller;
use App\Models\ContactRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(Request $request): View
    {
        $requests = ContactRequest::query()
            ->with('user')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('search'), fn ($q) => $q->where(fn ($q) => $q
                ->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('email', 'like', '%'.$request->search.'%')
                ->orWhere('subject', 'like', '%'.$request->search.'%')))
            ->latest()
            ->paginate(20);

        $pendingCount = ContactRequest::where('status', ContactRequestStatus::EnAttente)->count();

        return view('admin.contact.index', compact('requests', 'pendingCount'));
    }

    public function show(ContactRequest $contactRequest): View
    {
        $contactRequest->load('user');

        // Mark as in_progress when admin opens it for the first time
        if ($contactRequest->status === ContactRequestStatus::EnAttente) {
            $contactRequest->update(['status' => ContactRequestStatus::EnCours]);
        }

        return view('admin.contact.show', compact('contactRequest'));
    }

    public function reply(Request $request, ContactRequest $contactRequest): RedirectResponse
    {
        $data = $request->validate([
            'admin_reply' => ['required', 'string', 'max:5000'],
            'admin_notes' => ['nullable', 'string', 'max:2000'],
            'status'      => ['required', 'string', 'in:en_attente,en_cours,resolu,ferme'],
        ]);

        $contactRequest->update([
            'admin_reply' => $data['admin_reply'],
            'admin_notes' => $data['admin_notes'] ?? null,
            'status'      => $data['status'],
            'replied_at'  => now(),
        ]);

        return back()->with('success', 'Réponse envoyée avec succès.');
    }

    public function updateStatus(Request $request, ContactRequest $contactRequest): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'in:en_attente,en_cours,resolu,ferme'],
        ]);

        $contactRequest->update(['status' => $data['status']]);

        return back()->with('success', 'Statut mis à jour.');
    }
}

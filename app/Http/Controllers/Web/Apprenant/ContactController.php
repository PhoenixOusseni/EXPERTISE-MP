<?php

namespace App\Http\Controllers\Web\Apprenant;

use App\Enums\ContactRequestStatus;
use App\Enums\ContactRequestType;
use App\Http\Controllers\Controller;
use App\Models\ContactRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function create(): View
    {
        $types = ContactRequestType::cases();

        return view('apprenant.contact.create', compact('types'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'subject' => ['required', 'string', 'max:150'],
            'type'    => ['required', 'string', 'in:demande,rdv,autre'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        ContactRequest::create([
            'user_id' => auth()->id(),
            'name'    => auth()->user()->name,
            'email'   => auth()->user()->email,
            'subject' => $data['subject'],
            'type'    => $data['type'],
            'message' => $data['message'],
            'status'  => ContactRequestStatus::EnAttente,
        ]);

        return redirect()->route('contact.index')
            ->with('success', 'Votre message a été envoyé. Nous vous répondrons dans les meilleurs délais.');
    }

    public function index(): View
    {
        $requests = ContactRequest::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('apprenant.contact.index', compact('requests'));
    }

    public function show(ContactRequest $contactRequest): View
    {
        abort_if($contactRequest->user_id !== auth()->id(), 403);

        return view('apprenant.contact.show', compact('contactRequest'));
    }
}

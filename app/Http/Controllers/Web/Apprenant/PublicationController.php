<?php

namespace App\Http\Controllers\Web\Apprenant;

use App\Http\Controllers\Controller;
use App\Models\Publication;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PublicationController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $user = auth()->user();

        if (! $user->isAdmin() && ! $user->hasActiveSubscription()) {
            return redirect()->route('formules')
                ->with('info', 'Les publications sont réservées aux abonnés. Souscrivez à une formule pour y accéder.');
        }

        $publications = Publication::published()->latest('published_at')->paginate(12);

        return view('apprenant.publications.index', compact('publications'));
    }

    public function show(Publication $publication): View|RedirectResponse
    {
        if (! $publication->is_published && ! auth()->user()->isAdmin()) {
            abort(404);
        }

        $user = auth()->user();

        if (! $user->isAdmin() && ! $user->hasActiveSubscription()) {
            return redirect()->route('formules')
                ->with('info', 'Les publications sont réservées aux abonnés.');
        }

        return view('apprenant.publications.show', compact('publication'));
    }
}

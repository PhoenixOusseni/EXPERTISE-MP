<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PublicationController extends Controller
{
    public function index(Request $request): View
    {
        $publications = Publication::with('user')
            ->when($request->filled('search'), fn ($q) => $q->where('title', 'like', '%'.$request->search.'%'))
            ->when($request->has('is_published') && $request->is_published !== '', fn ($q) => $q->where('is_published', $request->boolean('is_published')))
            ->latest()
            ->paginate(20);

        return view('admin.publications.index', compact('publications'));
    }

    public function create(): View
    {
        return view('admin.publications.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateAndPrepare($request);
        Publication::create($data + ['user_id' => auth()->id()]);

        return redirect()->route('admin.publications.index')->with('success', 'Publication créée avec succès.');
    }

    public function edit(Publication $publication): View
    {
        return view('admin.publications.form', compact('publication'));
    }

    public function update(Request $request, Publication $publication): RedirectResponse
    {
        $publication->update($this->validateAndPrepare($request, $publication));

        return redirect()->route('admin.publications.index')->with('success', 'Publication mise à jour.');
    }

    public function destroy(Publication $publication): RedirectResponse
    {
        if ($publication->file_path) {
            Storage::disk(Publication::FILE_DISK)->delete($publication->file_path);
        }
        $publication->delete();

        return back()->with('success', 'Publication supprimée.');
    }

    public function publish(Publication $publication): RedirectResponse
    {
        $publication->update(['is_published' => true, 'published_at' => $publication->published_at ?? now()]);

        return back()->with('success', '"'.$publication->title.'" est maintenant publiée.');
    }

    public function unpublish(Publication $publication): RedirectResponse
    {
        $publication->update(['is_published' => false]);

        return back()->with('success', '"'.$publication->title.'" a été dépubliée.');
    }

    protected function validateAndPrepare(Request $request, ?Publication $publication = null): array
    {
        $creating = $publication === null;

        $data = $request->validate([
            'title'   => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'file'    => ['nullable', 'file', 'max:20480'],
        ]);

        unset($data['file']);

        if ($request->hasFile('file')) {
            if ($publication?->file_path) {
                Storage::disk(Publication::FILE_DISK)->delete($publication->file_path);
            }
            $uploadedFile = $request->file('file');
            $data['file_path'] = $uploadedFile->store('publications/files', Publication::FILE_DISK);
            $data['file_name'] = $uploadedFile->getClientOriginalName();
        }

        return $data;
    }
}

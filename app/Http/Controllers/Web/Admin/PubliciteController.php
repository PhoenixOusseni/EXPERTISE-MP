<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publicite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PubliciteController extends Controller
{
    public function index(): View
    {
        $publicites = Publicite::orderBy('sort_order')->orderBy('start_date')->paginate(20);

        return view('admin.publicites.index', compact('publicites'));
    }

    public function create(): View
    {
        return view('admin.publicites.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateAndPrepare($request);
        Publicite::create($data);

        return redirect()->route('admin.publicites.index')->with('success', 'Publicité créée avec succès.');
    }

    public function edit(Publicite $publicite): View
    {
        return view('admin.publicites.form', compact('publicite'));
    }

    public function update(Request $request, Publicite $publicite): RedirectResponse
    {
        $publicite->update($this->validateAndPrepare($request, $publicite));

        return redirect()->route('admin.publicites.index')->with('success', 'Publicité mise à jour.');
    }

    public function destroy(Publicite $publicite): RedirectResponse
    {
        Storage::disk(Publicite::IMAGE_DISK)->delete($publicite->image_path);
        $publicite->delete();

        return back()->with('success', 'Publicité supprimée.');
    }

    protected function validateAndPrepare(Request $request, ?Publicite $publicite = null): array
    {
        $creating = $publicite === null;

        $data = $request->validate([
            'title'      => ['nullable', 'string', 'max:255'],
            'image'      => [$creating ? 'required' : 'nullable', 'image', 'max:5120'],
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date', 'after_or_equal:start_date'],
            'is_active'  => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        unset($data['image']);

        if ($request->hasFile('image')) {
            if ($publicite?->image_path) {
                Storage::disk(Publicite::IMAGE_DISK)->delete($publicite->image_path);
            }
            $data['image_path'] = $request->file('image')->store('publicites', Publicite::IMAGE_DISK);
        }

        return $data;
    }
}

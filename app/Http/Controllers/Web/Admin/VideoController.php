<?php

namespace App\Http\Controllers\Web\Admin;

use App\Enums\VideoSourceType;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Video;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class VideoController extends Controller
{
    public function index(Request $request): View
    {
        $videos = Video::with(['category'])
            ->when($request->filled('search'), fn ($q) => $q->where('title', 'like', '%'.$request->search.'%'))
            ->when($request->filled('category_id'), fn ($q) => $q->where('category_id', $request->category_id))
            ->when($request->filled('source_type'), fn ($q) => $q->where('source_type', $request->source_type))
            ->when($request->has('is_published') && $request->is_published !== '', fn ($q) => $q->where('is_published', $request->boolean('is_published')))
            ->latest()
            ->paginate(20);

        $categories = Category::orderBy('name')->get();

        return view('admin.videos.index', compact('videos', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.videos.form', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateAndPrepare($request);

        Video::create($data + ['user_id' => auth()->id()]);

        return redirect()->route('admin.videos.index')->with('success', 'Vidéo créée avec succès.');
    }

    public function edit(Video $video): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.videos.form', compact('video', 'categories'));
    }

    public function update(Request $request, Video $video): RedirectResponse
    {
        $video->update($this->validateAndPrepare($request, $video));

        return redirect()->route('admin.videos.index')->with('success', 'Vidéo mise à jour.');
    }

    public function destroy(Video $video): RedirectResponse
    {
        $video->delete();

        return back()->with('success', 'Vidéo supprimée.');
    }

    public function publish(Video $video): RedirectResponse
    {
        $video->update(['is_published' => true, 'published_at' => $video->published_at ?? now()]);

        return back()->with('success', '"'.$video->title.'" est maintenant publiée.');
    }

    public function unpublish(Video $video): RedirectResponse
    {
        $video->update(['is_published' => false]);

        return back()->with('success', '"'.$video->title.'" a été dépubliée.');
    }

    protected function validateAndPrepare(Request $request, ?Video $video = null): array
    {
        $sourceType = $request->input('source_type', $video?->source_type?->value ?? VideoSourceType::Upload->value);
        $isUpload = $sourceType === VideoSourceType::Upload->value;
        $creating = $video === null;
        $fileRequired = $isUpload && ($creating || ! $video?->file_path);
        $urlRequired = ! $isUpload && ($creating || ! $video?->external_url);

        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'source_type' => ['required', 'string'],
            'file' => [$fileRequired ? 'required' : 'nullable', 'file', 'mimetypes:video/*', 'max:'.config('formation.max_video_size_kb')],
            'external_url' => [$urlRequired ? 'required' : 'nullable', 'nullable', 'url', 'max:2048'],
            'thumbnail' => ['nullable', 'image', 'max:5120'],
            'duration' => ['nullable', 'integer', 'min:0'],
            'level' => ['nullable', 'string', 'in:debutant,intermediaire,avance'],
            'is_free' => ['sometimes', 'boolean'],
            'is_published' => ['sometimes', 'boolean'],
        ];

        $data = $request->validate($rules);
        unset($data['file'], $data['thumbnail']);

        if ($isUpload) {
            $data['external_url'] = null;
            if ($request->hasFile('file')) {
                if ($video?->file_path) {
                    Storage::disk(Video::VIDEO_DISK)->delete($video->file_path);
                }
                $data['file_path'] = $request->file('file')->store('videos', Video::VIDEO_DISK);
            }
        } else {
            if ($video?->file_path) {
                Storage::disk(Video::VIDEO_DISK)->delete($video->file_path);
            }
            $data['file_path'] = null;
        }

        if ($request->hasFile('thumbnail')) {
            if ($video?->thumbnail_path) {
                Storage::disk(Video::THUMBNAIL_DISK)->delete($video->thumbnail_path);
            }
            $data['thumbnail_path'] = $request->file('thumbnail')->store('thumbnails', Video::THUMBNAIL_DISK);
        }

        return $data;
    }
}

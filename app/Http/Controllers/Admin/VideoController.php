<?php

namespace App\Http\Controllers\Admin;

use App\Enums\VideoSourceType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VideoRequest;
use App\Http\Resources\VideoResource;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $videos = Video::query()
            ->with(['category', 'author'])
            ->when($request->filled('search'), fn ($q) => $q->where('title', 'like', '%'.$request->search.'%'))
            ->when($request->filled('category_id'), fn ($q) => $q->where('category_id', $request->category_id))
            ->when($request->filled('source_type'), fn ($q) => $q->where('source_type', $request->source_type))
            ->when($request->has('is_published'), fn ($q) => $q->where('is_published', $request->boolean('is_published')))
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return VideoResource::collection($videos);
    }

    public function store(VideoRequest $request): JsonResponse
    {
        $data = $this->prepareData($request);
        $data['user_id'] = $request->user()->id;

        $video = Video::create($data);

        return (new VideoResource($video->load(['category', 'author'])))
            ->withPlayer()
            ->response()
            ->setStatusCode(201);
    }

    public function show(Video $video): VideoResource
    {
        return (new VideoResource($video->load(['category', 'author'])))->withPlayer();
    }

    /**
     * Pour envoyer un fichier en mise à jour, utiliser POST avec le champ _method=PUT
     * (PHP ne lit pas les fichiers multipart sur une requête PUT/PATCH).
     */
    public function update(VideoRequest $request, Video $video): VideoResource
    {
        $video->update($this->prepareData($request, $video));

        return (new VideoResource($video->load(['category', 'author'])))->withPlayer();
    }

    public function destroy(Video $video): JsonResponse
    {
        $video->delete();

        return response()->json(null, 204);
    }

    public function publish(Video $video): VideoResource
    {
        $video->update(['is_published' => true, 'published_at' => $video->published_at ?? now()]);

        return new VideoResource($video->load('category'));
    }

    public function unpublish(Video $video): VideoResource
    {
        $video->update(['is_published' => false]);

        return new VideoResource($video->load('category'));
    }

    /**
     * Gère l'upload du fichier vidéo / de la miniature et nettoie les anciens fichiers.
     */
    protected function prepareData(VideoRequest $request, ?Video $video = null): array
    {
        $data = $request->safe()->except(['file', 'thumbnail']);
        $sourceType = VideoSourceType::from($data['source_type'] ?? $video?->source_type?->value ?? VideoSourceType::Upload->value);

        if ($sourceType === VideoSourceType::Upload) {
            $data['external_url'] = null;

            if ($request->hasFile('file')) {
                $this->deleteFile(Video::VIDEO_DISK, $video?->file_path);
                $data['file_path'] = $request->file('file')->store('videos', Video::VIDEO_DISK);
            }
        } else {
            $this->deleteFile(Video::VIDEO_DISK, $video?->file_path);
            $data['file_path'] = null;
        }

        if ($request->hasFile('thumbnail')) {
            $this->deleteFile(Video::THUMBNAIL_DISK, $video?->thumbnail_path);
            $data['thumbnail_path'] = $request->file('thumbnail')->store('thumbnails', Video::THUMBNAIL_DISK);
        }

        return $data;
    }

    protected function deleteFile(string $disk, ?string $path): void
    {
        if ($path) {
            Storage::disk($disk)->delete($path);
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VideoResource;
use App\Models\Category;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Catalogue visible par les apprenants. Les métadonnées des vidéos sont consultables
 * par tout utilisateur connecté ; seul le contenu (lecteur) est réservé aux abonnés.
 */
class CatalogController extends Controller
{
    public function categories(): JsonResponse
    {
        return response()->json(
            Category::withCount(['videos' => fn ($q) => $q->published()])
                ->orderBy('name')
                ->get()
        );
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $videos = Video::published()
            ->with('category')
            ->when($request->filled('search'), fn ($q) => $q->where(fn ($q) => $q
                ->where('title', 'like', '%'.$request->search.'%')
                ->orWhere('description', 'like', '%'.$request->search.'%')))
            ->when($request->filled('category'), fn ($q) => $q->whereHas('category', fn ($c) => $c
                ->where(fn ($c) => $c->where('slug', $request->category)->orWhere('categories.id', $request->category))))
            ->when($request->filled('level'), fn ($q) => $q->where('level', $request->level))
            ->when($request->has('is_free'), fn ($q) => $q->where('is_free', $request->boolean('is_free')))
            ->orderByDesc('published_at')
            ->paginate($request->integer('per_page', 12));

        return VideoResource::collection($videos);
    }

    /**
     * Détail d'une vidéo. Le bloc "player" n'est renvoyé que si l'utilisateur
     * a le droit de la regarder (vidéo gratuite ou abonnement actif).
     */
    public function show(Request $request, Video $video): VideoResource
    {
        abort_unless($video->isAvailable() || $request->user()->isAdmin(), 404);

        if ($request->user()->canWatch($video)) {
            $video->increment('views_count');
        }

        return (new VideoResource($video->load('category')))->withPlayer();
    }
}

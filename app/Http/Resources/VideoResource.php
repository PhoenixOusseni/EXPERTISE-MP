<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

/**
 * @mixin \App\Models\Video
 */
class VideoResource extends JsonResource
{
    /** Inclure les informations de lecture (URL du flux) si l'utilisateur y a droit. */
    protected bool $withPlayer = false;

    public function withPlayer(bool $value = true): static
    {
        $this->withPlayer = $value;

        return $this;
    }

    public function toArray(Request $request): array
    {
        $user = $request->user();
        $canWatch = $user?->canWatch($this->resource) ?? false;
        $isAdmin = $user?->isAdmin() ?? false;

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'source_type' => $this->source_type,
            'thumbnail_url' => $this->thumbnailUrl(),
            'duration' => $this->duration,
            'level' => $this->level,
            'is_free' => $this->is_free,
            'published_at' => $this->published_at,
            'views_count' => $this->views_count,
            'category' => $this->whenLoaded('category', fn () => $this->category?->only(['id', 'name', 'slug'])),
            'can_watch' => $canWatch,
            'player' => $this->when($this->withPlayer && $canWatch, fn () => $this->playerData($user)),

            // Champs réservés à l'administration
            $this->mergeWhen($isAdmin, fn () => [
                'is_published' => $this->is_published,
                'file_path' => $this->file_path,
                'external_url' => $this->external_url,
                'author' => $this->whenLoaded('author', fn () => $this->author?->only(['id', 'name'])),
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ]),
        ];
    }

    protected function playerData($user): array
    {
        if ($this->isUploaded()) {
            $expiresAt = now()->addHours(3);

            // URL signée : un lecteur <video> ne peut pas envoyer de jeton Bearer.
            return [
                'type' => 'stream',
                'url' => URL::temporarySignedRoute(
                    'videos.stream',
                    $expiresAt,
                    ['video' => $this->id, 'user' => $user->id],
                ),
                'expires_at' => $expiresAt,
            ];
        }

        return [
            'type' => $this->source_type,
            'url' => $this->external_url,
        ];
    }
}

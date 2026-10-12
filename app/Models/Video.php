<?php

namespace App\Models;

use App\Enums\LearnerProfile;
use App\Enums\VideoSourceType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Video extends Model
{
    /** Disque privé : les fichiers vidéo ne sont jamais exposés publiquement. */
    public const VIDEO_DISK = 'local';

    /** Disque public pour les miniatures. */
    public const THUMBNAIL_DISK = 'public';

    protected $fillable = [
        'category_id',
        'audience',
        'user_id',
        'title',
        'slug',
        'description',
        'source_type',
        'file_path',
        'external_url',
        'thumbnail_path',
        'duration',
        'level',
        'is_free',
        'is_published',
        'published_at',
    ];

    protected $hidden = [
        'file_path',
        'external_url',
    ];

    protected function casts(): array
    {
        return [
            'source_type' => VideoSourceType::class,
            'audience' => LearnerProfile::class,
            'duration' => 'integer',
            'is_free' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
            'views_count' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Video $video) {
            if (blank($video->slug)) {
                $video->slug = static::uniqueSlug($video->title);
            }

            if ($video->is_published && ! $video->published_at) {
                $video->published_at = now();
            }
        });

        static::deleted(function (Video $video) {
            $video->deleteFiles();
        });
    }

    public static function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'video';
        $slug = $base;
        $i = 2;

        while (static::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeForProfile(Builder $query, LearnerProfile $profile): Builder
    {
        return $query->where('audience', $profile->value);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->where(fn ($q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()));
    }

    public function isAvailable(): bool
    {
        return $this->is_published && (! $this->published_at || $this->published_at->lte(now()));
    }

    public function isUploaded(): bool
    {
        return $this->source_type === VideoSourceType::Upload;
    }

    public function thumbnailUrl(): ?string
    {
        return $this->thumbnail_path
            ? Storage::disk(self::THUMBNAIL_DISK)->url($this->thumbnail_path)
            : null;
    }

    public function deleteFiles(): void
    {
        if ($this->file_path) {
            Storage::disk(self::VIDEO_DISK)->delete($this->file_path);
        }

        if ($this->thumbnail_path) {
            Storage::disk(self::THUMBNAIL_DISK)->delete($this->thumbnail_path);
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Publicite extends Model
{
    const IMAGE_DISK = 'public';
    const FILE_DISK  = 'public';

    protected $fillable = [
        'title',
        'image_path',
        'file_path',
        'file_name',
        'start_date',
        'end_date',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date'   => 'date',
            'is_active'  => 'boolean',
        ];
    }

    /** Publicités en cours (actives + dans la plage de dates). */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where('start_date', '<=', now()->toDateString())
            ->where('end_date', '>=', now()->toDateString())
            ->orderBy('sort_order')
            ->orderBy('start_date');
    }
}

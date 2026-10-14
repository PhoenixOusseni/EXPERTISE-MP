<?php

namespace App\Models;

use App\Enums\LearnerProfile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Formule d'abonnement proposée aux apprenants (mensuel, trimestriel, annuel...).
 */
class Plan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'price_xof',
        'currency',
        'duration_days',
        'features',
        'is_active',
        'profile_type',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'duration_days' => 'integer',
            'features' => 'array',
            'is_active' => 'boolean',
            'profile_type' => LearnerProfile::class,
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Plan $plan) {
            if (blank($plan->slug)) {
                $plan->slug = Str::slug($plan->name);
            }
        });
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeForProfile(Builder $query, LearnerProfile $profile): Builder
    {
        return $query->where('profile_type', $profile->value);
    }
}

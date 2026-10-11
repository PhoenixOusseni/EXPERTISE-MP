<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /** Mémorise le résultat de hasActiveSubscription() le temps d'une requête. */
    protected ?bool $hasActiveSubscriptionCache = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'is_active',
        'avatar',
        'profession',
        'address',
        'city',
        'country',
        'bio',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'is_active' => 'boolean',
        ];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * L'abonnement en cours de validité (le plus long s'il y en a plusieurs).
     */
    public function activeSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class)
            ->ofMany(['ends_at' => 'max'], fn ($query) => $query->currentlyActive());
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    public function avatarUrl(): ?string
    {
        return $this->avatar
            ? \Illuminate\Support\Facades\Storage::disk('public')->url($this->avatar)
            : null;
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function hasActiveSubscription(bool $fresh = false): bool
    {
        if ($fresh || $this->hasActiveSubscriptionCache === null) {
            $this->hasActiveSubscriptionCache = $this->subscriptions()->currentlyActive()->exists();
        }

        return $this->hasActiveSubscriptionCache;
    }

    /**
     * Un utilisateur peut regarder une vidéo s'il est admin, si la vidéo est gratuite
     * ou s'il possède un abonnement actif.
     */
    public function canWatch(Video $video): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        return $video->isAvailable() && ($video->is_free || $this->hasActiveSubscription());
    }

    public function scopeApprenants($query)
    {
        return $query->where('role', UserRole::Apprenant);
    }

    public function scopeWithActiveSubscription($query)
    {
        return $query->whereHas('subscriptions', fn ($q) => $q->currentlyActive());
    }
}

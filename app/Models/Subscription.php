<?php

namespace App\Models;

use App\Enums\SubscriptionStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'status',
        'amount',
        'currency',
        'payment_method',
        'payment_reference',
        'starts_at',
        'ends_at',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => SubscriptionStatus::class,
            'amount' => 'decimal:2',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Abonnements actifs dont la période couvre l'instant présent.
     */
    public function scopeCurrentlyActive(Builder $query): Builder
    {
        return $query->where('subscriptions.status', SubscriptionStatus::Active)
            ->where('subscriptions.starts_at', '<=', now())
            ->where('subscriptions.ends_at', '>', now());
    }

    /**
     * Abonnements marqués actifs mais dont la date de fin est dépassée.
     */
    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where('status', SubscriptionStatus::Active)
            ->where('ends_at', '<=', now());
    }

    public function isCurrentlyActive(): bool
    {
        return $this->status === SubscriptionStatus::Active
            && $this->starts_at?->lte(now())
            && $this->ends_at?->gt(now());
    }

    /**
     * Active l'abonnement. Si l'utilisateur a déjà un abonnement en cours,
     * la nouvelle période démarre à la fin de celui-ci (renouvellement anticipé).
     */
    public function activate(?Carbon $startsAt = null): self
    {
        $currentEnd = Subscription::query()
            ->where('user_id', $this->user_id)
            ->whereKeyNot($this->getKey())
            ->currentlyActive()
            ->max('ends_at');

        $start = $startsAt ?? ($currentEnd ? Carbon::parse($currentEnd) : now());

        $this->forceFill([
            'status' => SubscriptionStatus::Active,
            'starts_at' => $start,
            'ends_at' => $start->copy()->addDays($this->plan->duration_days),
            'cancelled_at' => null,
        ])->save();

        return $this;
    }

    public function cancel(): self
    {
        $this->forceFill([
            'status' => SubscriptionStatus::Cancelled,
            'cancelled_at' => now(),
        ])->save();

        return $this;
    }

    public function daysRemaining(): int
    {
        if (! $this->isCurrentlyActive()) {
            return 0;
        }

        return (int) ceil(now()->diffInDays($this->ends_at));
    }
}

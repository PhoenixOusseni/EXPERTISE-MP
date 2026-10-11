<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Subscription
 */
class SubscriptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'is_currently_active' => $this->isCurrentlyActive(),
            'days_remaining' => $this->daysRemaining(),
            'amount' => $this->amount,
            'currency' => $this->currency,
            'payment_method' => $this->payment_method,
            'payment_reference' => $this->payment_reference,
            'starts_at' => $this->starts_at,
            'ends_at' => $this->ends_at,
            'cancelled_at' => $this->cancelled_at,
            'plan' => $this->whenLoaded('plan'),
            'user' => $this->whenLoaded('user', fn () => $this->user->only(['id', 'name', 'email', 'phone'])),
            'created_at' => $this->created_at,
        ];
    }
}

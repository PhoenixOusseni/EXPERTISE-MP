<?php

namespace App\Models;

use App\Enums\ContactRequestStatus;
use App\Enums\ContactRequestType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactRequest extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'subject',
        'type',
        'message',
        'status',
        'admin_notes',
        'admin_reply',
        'replied_at',
    ];

    protected function casts(): array
    {
        return [
            'type'        => ContactRequestType::class,
            'status'      => ContactRequestStatus::class,
            'replied_at'  => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', ContactRequestStatus::EnAttente);
    }

    public function isReplied(): bool
    {
        return $this->replied_at !== null;
    }
}

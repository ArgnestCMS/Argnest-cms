<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerReview extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'user_id',
        'rating',
        'title',
        'comment',
        'hide_name',
        'hide_contact',
        'status',
        'admin_note',
        'approved_at',
    ];

    protected static function booted(): void
    {
        static::saving(function (CustomerReview $review): void {
            if ($review->status === self::STATUS_APPROVED && blank($review->approved_at)) {
                $review->approved_at = now();
            }

            if ($review->status !== self::STATUS_APPROVED) {
                $review->approved_at = null;
            }
        });
    }

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'hide_name' => 'boolean',
            'hide_contact' => 'boolean',
            'approved_at' => 'datetime',
        ];
    }

    public static function statusOptions(): array
    {
        return [
            self::STATUS_PENDING => 'Beklemede',
            self::STATUS_APPROVED => 'Onaylandi',
            self::STATUS_REJECTED => 'Reddedildi',
        ];
    }

    public static function statusColors(): array
    {
        return [
            self::STATUS_PENDING => 'warning',
            self::STATUS_APPROVED => 'success',
            self::STATUS_REJECTED => 'danger',
        ];
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function publicName(): string
    {
        return $this->hide_name ? 'Argnest Müşterisi' : ($this->user?->name ?: $this->customer?->name ?: 'Argnest Müşterisi');
    }
}

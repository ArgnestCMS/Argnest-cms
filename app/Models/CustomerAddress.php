<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerAddress extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'country',
        'city',
        'district',
        'neighborhood',
        'street',
        'building_no',
        'apartment_no',
        'postal_code',
        'address',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function (CustomerAddress $address): void {
            if (! $address->is_default) {
                return;
            }

            static::query()
                ->where('user_id', $address->user_id)
                ->whereKeyNot($address->getKey())
                ->update(['is_default' => false]);
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

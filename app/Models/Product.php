<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public const STATUS_ACTIVE = 'active';

    public const STATUS_COMING_SOON = 'coming_soon';

    public const STATUS_BETA = 'beta';

    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'description',
        'cover_image',
        'gallery',
        'product_status',
        'is_featured',
        'is_active',
        'sort_order',
        'seo_title',
        'seo_description',
    ];

    protected function casts(): array
    {
        return [
            'gallery' => 'array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public static function statusOptions(): array
    {
        return [
            self::STATUS_ACTIVE => 'Aktif',
            self::STATUS_COMING_SOON => 'Yakında',
            self::STATUS_BETA => 'Beta',
        ];
    }

    public static function statusColors(): array
    {
        return [
            self::STATUS_ACTIVE => 'success',
            self::STATUS_COMING_SOON => 'warning',
            self::STATUS_BETA => 'info',
        ];
    }
}

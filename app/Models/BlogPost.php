<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogPost extends Model
{
    protected $fillable = [
        'blog_category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'gallery',
        'author',
        'published_at',
        'is_featured',
        'is_active',
        'seo_title',
        'seo_description',
        'view_count',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'gallery' => 'array',
            'published_at' => 'datetime',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'view_count' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }
}

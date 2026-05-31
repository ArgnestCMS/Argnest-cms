<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class CustomerFile extends Model
{
    public const CATEGORY_OFFER = 'teklif';

    public const CATEGORY_CONTRACT = 'sozlesme';

    public const CATEGORY_HOSTING = 'hosting';

    public const CATEGORY_DOMAIN = 'domain';

    public const CATEGORY_LICENSE = 'lisans';

    public const CATEGORY_PROJECT = 'proje';

    public const CATEGORY_INVOICE = 'fatura';

    public const CATEGORY_OTHER = 'diger';

    protected $fillable = [
        'user_id',
        'title',
        'category',
        'description',
        'file_name',
        'file_path',
        'file_size',
        'mime_type',
        'is_visible',
        'uploaded_by',
    ];

    protected static function booted(): void
    {
        static::deleting(function (CustomerFile $file): void {
            if ($file->file_path && Storage::disk('local')->exists($file->file_path)) {
                Storage::disk('local')->delete($file->file_path);
            }
        });
    }

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
            'is_visible' => 'boolean',
        ];
    }

    public static function categoryOptions(): array
    {
        return [
            self::CATEGORY_OFFER => 'Teklif',
            self::CATEGORY_CONTRACT => 'Sozlesme',
            self::CATEGORY_HOSTING => 'Hosting',
            self::CATEGORY_DOMAIN => 'Domain',
            self::CATEGORY_LICENSE => 'Lisans',
            self::CATEGORY_PROJECT => 'Proje',
            self::CATEGORY_INVOICE => 'Fatura',
            self::CATEGORY_OTHER => 'Diger',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function formattedSize(): string
    {
        if ($this->file_size >= 1048576) {
            return number_format($this->file_size / 1048576, 2) . ' MB';
        }

        if ($this->file_size >= 1024) {
            return number_format($this->file_size / 1024, 1) . ' KB';
        }

        return $this->file_size . ' B';
    }
}

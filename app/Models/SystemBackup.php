<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemBackup extends Model
{
    public const TYPE_FULL = 'full';

    public const STATUS_PROCESSING = 'processing';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_FAILED = 'failed';

    protected $fillable = [
        'file_name',
        'file_path',
        'file_size',
        'type',
        'status',
        'created_by',
        'completed_at',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
            'completed_at' => 'datetime',
        ];
    }

    public static function statusOptions(): array
    {
        return [
            self::STATUS_PROCESSING => 'Hazirlaniyor',
            self::STATUS_COMPLETED => 'Tamamlandi',
            self::STATUS_FAILED => 'Basarisiz',
        ];
    }

    public static function statusColors(): array
    {
        return [
            self::STATUS_PROCESSING => 'warning',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_FAILED => 'danger',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function absolutePath(): string
    {
        return storage_path('app/' . ltrim($this->file_path, '/\\'));
    }

    public function formattedSize(): string
    {
        if (! $this->file_size) {
            return '-';
        }

        if ($this->file_size >= 1073741824) {
            return number_format($this->file_size / 1073741824, 2) . ' GB';
        }

        if ($this->file_size >= 1048576) {
            return number_format($this->file_size / 1048576, 2) . ' MB';
        }

        return number_format($this->file_size / 1024, 1) . ' KB';
    }
}

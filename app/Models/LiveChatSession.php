<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LiveChatSession extends Model
{
    public const STATUS_OPEN = 'open';

    public const STATUS_ANSWERED = 'answered';

    public const STATUS_CLOSED = 'closed';

    protected $fillable = [
        'visitor_name',
        'visitor_email',
        'visitor_phone',
        'ip_address',
        'user_agent',
        'status',
        'assigned_user_id',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'closed_at' => 'datetime',
        ];
    }

    public static function statusOptions(): array
    {
        return [
            self::STATUS_OPEN => 'Acik',
            self::STATUS_ANSWERED => 'Yanitlandi',
            self::STATUS_CLOSED => 'Kapali',
        ];
    }

    public static function statusColors(): array
    {
        return [
            self::STATUS_OPEN => 'warning',
            self::STATUS_ANSWERED => 'success',
            self::STATUS_CLOSED => 'gray',
        ];
    }

    public function messages(): HasMany
    {
        return $this->hasMany(LiveChatMessage::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }
}

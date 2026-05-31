<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class SupportTicket extends Model
{
    public const STATUS_OPEN = 'open';

    public const STATUS_ANSWERED = 'answered';

    public const STATUS_PENDING = 'pending';

    public const STATUS_CLOSED = 'closed';

    public const PRIORITY_LOW = 'low';

    public const PRIORITY_NORMAL = 'normal';

    public const PRIORITY_HIGH = 'high';

    public const PRIORITY_URGENT = 'urgent';

    protected $fillable = [
        'user_id',
        'ticket_no',
        'subject',
        'category',
        'status',
        'priority',
    ];

    protected static function booted(): void
    {
        static::creating(function (SupportTicket $ticket): void {
            if (blank($ticket->ticket_no)) {
                $ticket->ticket_no = static::generateTicketNo();
            }
        });
    }

    public static function generateTicketNo(): string
    {
        do {
            $ticketNo = 'ARG-' . now()->format('Ymd') . '-' . Str::upper(Str::random(5));
        } while (static::query()->where('ticket_no', $ticketNo)->exists());

        return $ticketNo;
    }

    public static function statusOptions(): array
    {
        return [
            self::STATUS_OPEN => 'Acik',
            self::STATUS_ANSWERED => 'Cevaplandi',
            self::STATUS_PENDING => 'Beklemede',
            self::STATUS_CLOSED => 'Kapali',
        ];
    }

    public static function statusColors(): array
    {
        return [
            self::STATUS_OPEN => 'info',
            self::STATUS_ANSWERED => 'success',
            self::STATUS_PENDING => 'warning',
            self::STATUS_CLOSED => 'gray',
        ];
    }

    public static function priorityOptions(): array
    {
        return [
            self::PRIORITY_LOW => 'Dusuk',
            self::PRIORITY_NORMAL => 'Normal',
            self::PRIORITY_HIGH => 'Yuksek',
            self::PRIORITY_URGENT => 'Acil',
        ];
    }

    public static function priorityColors(): array
    {
        return [
            self::PRIORITY_LOW => 'gray',
            self::PRIORITY_NORMAL => 'info',
            self::PRIORITY_HIGH => 'warning',
            self::PRIORITY_URGENT => 'danger',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(SupportMessage::class, 'ticket_id');
    }

    public function latestMessage(): HasMany
    {
        return $this->messages()->latest('created_at')->limit(1);
    }
}

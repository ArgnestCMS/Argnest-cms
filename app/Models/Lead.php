<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    public const STATUS_NEW = 'new';

    public const STATUS_CONTACTED = 'contacted';

    public const STATUS_PROPOSAL_SENT = 'proposal_sent';

    public const STATUS_WON = 'won';

    public const STATUS_LOST = 'lost';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'service_type',
        'budget_range',
        'message',
        'status',
        'source',
        'admin_note',
        'last_contacted_at',
    ];

    protected function casts(): array
    {
        return [
            'last_contacted_at' => 'datetime',
        ];
    }

    public static function statusOptions(): array
    {
        return [
            self::STATUS_NEW => 'Yeni',
            self::STATUS_CONTACTED => 'Görüşüldü',
            self::STATUS_PROPOSAL_SENT => 'Teklif Gönderildi',
            self::STATUS_WON => 'Kazanıldı',
            self::STATUS_LOST => 'Kaybedildi',
        ];
    }

    public static function statusColors(): array
    {
        return [
            self::STATUS_NEW => 'warning',
            self::STATUS_CONTACTED => 'info',
            self::STATUS_PROPOSAL_SENT => 'primary',
            self::STATUS_WON => 'success',
            self::STATUS_LOST => 'danger',
        ];
    }
}

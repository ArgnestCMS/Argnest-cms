<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerService extends Model
{
    public const RENEWAL_STATUS_EXPIRED = 'expired';

    public const RENEWAL_STATUS_CRITICAL = 'critical';

    public const RENEWAL_STATUS_WARNING = 'warning';

    public const RENEWAL_STATUS_SAFE = 'safe';

    public const RENEWAL_STATUS_UNKNOWN = 'unknown';

    protected $fillable = [
        'user_id',
        'service_name',
        'domain_name',
        'hosting_package',
        'server_ip',
        'server_panel',
        'username',
        'password_hint',
        'expiry_date',
        'notes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'expiry_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function daysUntilExpiry(): ?int
    {
        if (! $this->expiry_date) {
            return null;
        }

        return (int) now()->startOfDay()->diffInDays($this->expiry_date->copy()->startOfDay(), false);
    }

    public function isExpired(): bool
    {
        return $this->daysUntilExpiry() !== null && $this->daysUntilExpiry() < 0;
    }

    public function isExpiringSoon(): bool
    {
        $days = $this->daysUntilExpiry();

        return $days !== null && $days >= 0 && $days <= 90;
    }

    public function renewalStatus(): string
    {
        $days = $this->daysUntilExpiry();

        return match (true) {
            $days === null => self::RENEWAL_STATUS_UNKNOWN,
            $days < 0 => self::RENEWAL_STATUS_EXPIRED,
            $days <= 30 => self::RENEWAL_STATUS_CRITICAL,
            $days <= 90 => self::RENEWAL_STATUS_WARNING,
            default => self::RENEWAL_STATUS_SAFE,
        };
    }

    public static function renewalStatusOptions(): array
    {
        return [
            self::RENEWAL_STATUS_EXPIRED => 'Suresi Gecti',
            self::RENEWAL_STATUS_CRITICAL => 'Kritik',
            self::RENEWAL_STATUS_WARNING => 'Yaklasiyor',
            self::RENEWAL_STATUS_SAFE => 'Guvenli',
            self::RENEWAL_STATUS_UNKNOWN => 'Tarih Yok',
        ];
    }

    public static function renewalStatusColors(): array
    {
        return [
            self::RENEWAL_STATUS_EXPIRED => 'danger',
            self::RENEWAL_STATUS_CRITICAL => 'danger',
            self::RENEWAL_STATUS_WARNING => 'warning',
            self::RENEWAL_STATUS_SAFE => 'success',
            self::RENEWAL_STATUS_UNKNOWN => 'gray',
        ];
    }
}

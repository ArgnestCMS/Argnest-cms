<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminActivityLog extends Model
{
    public const UPDATED_AT = null;

    public const ACTION_LOGIN = 'login';

    public const ACTION_LOGOUT = 'logout';

    public const ACTION_ADMIN_USER_CREATED = 'admin_user_created';

    public const ACTION_ADMIN_USER_UPDATED = 'admin_user_updated';

    public const ACTION_ADMIN_USER_DELETED = 'admin_user_deleted';

    public const ACTION_ROLE_CREATED = 'role_created';

    public const ACTION_ROLE_UPDATED = 'role_updated';

    public const ACTION_ROLE_DELETED = 'role_deleted';

    public const ACTION_PERMISSION_CREATED = 'permission_created';

    public const ACTION_PERMISSION_UPDATED = 'permission_updated';

    public const ACTION_PERMISSION_DELETED = 'permission_deleted';

    public const ACTION_SITE_SETTINGS_UPDATED = 'site_settings_updated';

    public const ACTION_MAIL_SETTINGS_UPDATED = 'mail_settings_updated';

    public const ACTION_SUPPORT_TICKET_REPLIED = 'support_ticket_replied';

    public const ACTION_LIVE_CHAT_REPLIED = 'live_chat_replied';

    public const ACTION_LIVE_CHAT_CLOSED = 'live_chat_closed';

    public const ACTION_BACKUP_CREATED = 'backup_created';

    public const ACTION_BACKUP_DOWNLOADED = 'backup_downloaded';

    public const ACTION_BACKUP_DELETED = 'backup_deleted';

    public const ACTION_BACKUP_FAILED = 'backup_failed';

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public static function actionOptions(): array
    {
        return [
            self::ACTION_LOGIN => 'Admin giris yapti',
            self::ACTION_LOGOUT => 'Admin cikis yapti',
            self::ACTION_ADMIN_USER_CREATED => 'Admin kullanici olusturdu',
            self::ACTION_ADMIN_USER_UPDATED => 'Admin kullanici duzenledi',
            self::ACTION_ADMIN_USER_DELETED => 'Admin kullanici sildi',
            self::ACTION_ROLE_CREATED => 'Rol olusturdu',
            self::ACTION_ROLE_UPDATED => 'Rol duzenledi',
            self::ACTION_ROLE_DELETED => 'Rol sildi',
            self::ACTION_PERMISSION_CREATED => 'Yetki olusturdu',
            self::ACTION_PERMISSION_UPDATED => 'Yetki duzenledi',
            self::ACTION_PERMISSION_DELETED => 'Yetki sildi',
            self::ACTION_SITE_SETTINGS_UPDATED => 'Site ayarlari duzenlendi',
            self::ACTION_MAIL_SETTINGS_UPDATED => 'Mail ayarlari duzenlendi',
            self::ACTION_SUPPORT_TICKET_REPLIED => 'Destek talebi cevaplandi',
            self::ACTION_LIVE_CHAT_REPLIED => 'Canli destek cevaplandi',
            self::ACTION_LIVE_CHAT_CLOSED => 'Canli destek kapatildi',
            self::ACTION_BACKUP_CREATED => 'Yedek olusturuldu',
            self::ACTION_BACKUP_DOWNLOADED => 'Yedek indirildi',
            self::ACTION_BACKUP_DELETED => 'Yedek silindi',
            self::ACTION_BACKUP_FAILED => 'Yedek basarisiz oldu',
        ];
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

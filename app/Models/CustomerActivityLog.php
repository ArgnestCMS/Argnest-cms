<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerActivityLog extends Model
{
    public const UPDATED_AT = null;

    public const ACTION_REGISTERED = 'registered';

    public const ACTION_LOGIN = 'login';

    public const ACTION_LOGOUT = 'logout';

    public const ACTION_PROFILE_UPDATED = 'profile_updated';

    public const ACTION_PASSWORD_CHANGED = 'password_changed';

    public const ACTION_SUPPORT_TICKET_CREATED = 'support_ticket_created';

    public const ACTION_SUPPORT_TICKET_REPLIED = 'support_ticket_replied';

    public const ACTION_FILE_UPLOADED = 'file_uploaded';

    public const ACTION_REVIEW_SUBMITTED = 'review_submitted';

    public const ACTION_REVIEW_UPDATED = 'review_updated';

    public const ACTION_SERVICES_VIEWED = 'services_viewed';

    public const ACTION_SERVICE_HISTORY_VIEWED = 'service_history_viewed';

    public const ACTION_FILE_VIEWED = 'file_viewed';

    public const ACTION_FILE_DOWNLOADED = 'file_downloaded';

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
            self::ACTION_REGISTERED => 'Musteri kayit oldu',
            self::ACTION_LOGIN => 'Musteri giris yapti',
            self::ACTION_LOGOUT => 'Musteri cikis yapti',
            self::ACTION_PROFILE_UPDATED => 'Profil guncelledi',
            self::ACTION_PASSWORD_CHANGED => 'Sifre degistirdi',
            self::ACTION_SUPPORT_TICKET_CREATED => 'Destek talebi olusturdu',
            self::ACTION_SUPPORT_TICKET_REPLIED => 'Destek talebine cevap yazdi',
            self::ACTION_FILE_UPLOADED => 'Dosya yukledi',
            self::ACTION_REVIEW_SUBMITTED => 'Yorum gonderdi',
            self::ACTION_REVIEW_UPDATED => 'Yorum guncelledi',
            self::ACTION_SERVICES_VIEWED => 'Hizmetlerini goruntuledi',
            self::ACTION_SERVICE_HISTORY_VIEWED => 'Hizmet gecmisini goruntuledi',
            self::ACTION_FILE_VIEWED => 'Dosya goruntulendi',
            self::ACTION_FILE_DOWNLOADED => 'Dosya indirildi',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

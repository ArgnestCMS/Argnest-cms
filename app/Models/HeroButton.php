<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroButton extends Model
{
    public const STYLE_PRIMARY = 'primary';

    public const STYLE_SECONDARY = 'secondary';

    public const STYLE_OUTLINE = 'outline';

    public const STYLE_WHATSAPP = 'whatsapp';

    public const TARGET_SELF = 'self';

    public const TARGET_BLANK = 'blank';

    protected $fillable = [
        'title',
        'url',
        'style',
        'target',
        'icon',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public static function styleOptions(): array
    {
        return [
            self::STYLE_PRIMARY => 'Ana Buton',
            self::STYLE_SECONDARY => 'İkincil Buton',
            self::STYLE_OUTLINE => 'Çerçeveli Buton',
            self::STYLE_WHATSAPP => 'WhatsApp Butonu',
        ];
    }

    public static function styleColors(): array
    {
        return [
            self::STYLE_PRIMARY => 'primary',
            self::STYLE_SECONDARY => 'info',
            self::STYLE_OUTLINE => 'gray',
            self::STYLE_WHATSAPP => 'success',
        ];
    }

    public static function targetOptions(): array
    {
        return [
            self::TARGET_SELF => 'Aynı Sekme',
            self::TARGET_BLANK => 'Yeni Sekme',
        ];
    }
}

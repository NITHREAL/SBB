<?php

namespace Domain\Order\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self mobile()
 * @method static self site()
 * @method static self sbermarket()
 * @method static self offline()
 */
class OrderSourceEnum extends Enum
{
    private const SITE = 'site';
    private const MOBILE = 'mobile';
    private const SBERMARKET = 'sbermarket';
    private const OFFLINE = 'offline';

    protected static function values(): array
    {
        return [
            'mobile'        => 'mobile',
            'site'          => 'site',
            'sbermarket'    => 'sbermarket',
            'offline'       => 'offline',
        ];
    }

    protected static function labels(): array
    {
        return [
            'mobile'        => 'Мобильное приложение',
            'site'          => 'Сайт',
            'sbermarket'    => 'Сбермаркет',
            'offline'       => 'Оффлайн',
        ];
    }

    public static function preparedForExportValues(): array
    {
        return [
            self::SITE          => 'Сайт',
            self::MOBILE        => 'МП',
            self::OFFLINE       => 'Оффлайн',
            self::SBERMARKET    => 'Сбер',
        ];
    }
}

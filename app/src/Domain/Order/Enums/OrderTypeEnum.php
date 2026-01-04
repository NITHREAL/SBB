<?php

namespace Domain\Order\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self offline()
 * @method static self online()
 */
class OrderTypeEnum extends Enum
{
    private const TYPE_ONLINE = 'online';

    private const TYPE_OFFLINE = 'offline';

    protected static function labels(): array
    {
        return [
            self::TYPE_OFFLINE  => 'Оффлайн',
            self::TYPE_ONLINE   => 'Онлайн',
        ];
    }
}

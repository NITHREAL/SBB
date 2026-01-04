<?php

namespace Domain\MobileVersion\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self recommend()
 * @method static self need_update()
 */
class MobileVersionStatusEnum extends Enum
{
    const STATUS_RECOMMEND   = 'recommend';
    const STATUS_NEED_UPDATE    = 'need_update';

    protected static function labels(): array
    {
        return [
            self::STATUS_RECOMMEND    => 'Рекомендуется обновление',
            self::STATUS_NEED_UPDATE   => 'Требуется обновление',
        ];
    }
}

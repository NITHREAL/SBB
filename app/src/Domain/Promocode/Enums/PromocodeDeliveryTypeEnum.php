<?php

namespace Domain\Promocode\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self any()
 * @method static self delivery()
 * @method static self pickup()
 */
class PromocodeDeliveryTypeEnum extends Enum
{
    protected static function labels(): array
    {
        return [
            'any'       => 'Любой способ',
            'pickup'    => 'Самовывоз',
            'delivery'  => 'Доставка курьером',
        ];
    }
}

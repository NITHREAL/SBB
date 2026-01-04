<?php

namespace Domain\Order\Enums\Delivery;

use Spatie\Enum\Enum;

/**
 * @method static self pickup()
 * @method static self delivery()
 */
class DeliveryTypeEnum extends Enum
{
    protected static function labels(): array
    {
        return [
            'pickup'            => 'Самовывоз',
            'delivery'          => 'Доставка курьером',
        ];
    }
}

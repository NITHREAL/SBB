<?php

namespace Domain\Order\Enums\Delivery;

use Spatie\Enum\Enum;

/**
 * @method static self fast()
 * @method static self extended()
 * @method static self other()
 */
class PolygonDeliveryTypeEnum extends Enum
{
    protected static function labels(): array
    {
        return [
            'fast'      => 'Быстрая доставка',
            'extended'  => 'Доставка сегодня',
            'other'     => 'Доставка на другой день',
        ];
    }
}

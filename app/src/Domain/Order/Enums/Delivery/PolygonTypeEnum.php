<?php

namespace Domain\Order\Enums\Delivery;

use Spatie\Enum\Enum;

/**
 * @method static self delivery()
 * @method static self pickup()
 */
class PolygonTypeEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'delivery'  => 'delivery',
            'pickup'    => 'pickup',
        ];
    }
}

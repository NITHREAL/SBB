<?php

namespace Domain\Order\Enums\Delivery;

use Spatie\Enum\Enum;

/**
 * @method static self today()
 * @method static self other()
 */
class PickupTypeEnum extends Enum
{
    protected static function labels(): array
    {
        return [
            'today' => 'Забрать сегодня',
            'other' => 'Доставка на другой день',
        ];
    }
}

<?php

namespace Domain\Order\Enums\Delivery;

use Spatie\Enum\Enum;

/**
 * @method static self post()
 * @method static self cdek()
 */
class DeliveryServiceEnum extends Enum
{
    protected static function labels(): array
    {
        return [
            'post' => 'Почта России',
            'cdek' => 'СДЭК'
        ];
    }
}

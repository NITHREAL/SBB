<?php

namespace Domain\Exchange\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self order()
 */
class RabbitMQQueueEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'order' => 'shopper-order-queue',
        ];
    }
}

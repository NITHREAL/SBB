<?php

namespace Domain\Order\Enums\Sbermarket;

use Spatie\Enum\Enum;

/**
 * @method static self created()
 * @method static self updated()
 * @method static self payed()
 * @method static self delivering()
 * @method static self delivered()
 * @method static self canceled()
 */
class OrderSbermarketStatusEnum extends Enum
{
    private const CREATED = 'created';

    private const UPDATED = 'updated';

    private const PAYED = 'payed';

    private const DELIVERING = 'delivering';

    private const DELIVERED = 'delivered';

    private const CANCELED = 'canceled';

    protected static function values(): array
    {
        return [
            'created'       => self::CREATED,
            'updated'       => self::UPDATED,
            'payed'         => self::PAYED,
            'delivering'    => self::DELIVERING,
            'delivered'     => self::DELIVERED,
            'canceled'      => self::CANCELED,
        ];
    }
}

<?php

namespace Domain\Order\Enums\Sbermarket;

use Spatie\Enum\Enum;

/**
 * @method static self approved()
 */
class OrderSbermarketOperationEnum extends Enum
{
    private const APPROVED = 'approved';

    protected static function values(): array
    {
        return [
            'approved' => self::APPROVED,
        ];
    }

}

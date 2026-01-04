<?php

namespace Infrastructure\Services\Loyalty\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self manzana()
 */
class LoyaltyTypeEnum extends Enum
{
    private const MANZANA = 'manzana';

    protected static function labels()
    {
        return [
            self::MANZANA => 'Манзана',
        ];
    }
}

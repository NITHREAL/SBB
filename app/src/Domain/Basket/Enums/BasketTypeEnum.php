<?php

namespace Domain\Basket\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self current()
 * @method static self preorder()
 */
class BasketTypeEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'current'       => 'current',
            'preorder'      => 'preorder',
        ];
    }
}

<?php

namespace Domain\Product\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self g()
 * @method static self kg()
 * @method static self pcs()
 */
class ProductRateTypeEnum extends Enum
{
    protected static function labels(): array
    {
        return [
            'g'     => 'гр',
            'kg'    => 'кг',
            'pcs'   => 'шт'
        ];
    }
}

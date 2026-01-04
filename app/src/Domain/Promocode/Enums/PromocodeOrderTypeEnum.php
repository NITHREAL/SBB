<?php

namespace Domain\Promocode\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self any()
 * @method static self first()
 * @method static self notFirst()
 */
class PromocodeOrderTypeEnum extends Enum
{
    protected static function values()
    {
        return [
            'any'       => 'any',
            'first'     => 'first',
            'notFirst'  => 'not_first'
        ];
    }

    protected static function labels(): array
    {
        return [
            'any'       => 'Любой заказ',
            'first'     => 'Только первый заказ',
            'notFirst'  => 'Не первый заказ'
        ];
    }
}

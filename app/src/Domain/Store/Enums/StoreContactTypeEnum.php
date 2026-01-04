<?php

namespace Domain\Store\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self email()
 * @method static self phone()
 */
class StoreContactTypeEnum extends Enum
{
    protected static function labels(): array
    {
        return [
            'email' => 'Email',
            'phone' => 'Телефон'
        ];
    }
}

<?php

namespace Domain\User\Enums\LoyaltyCard;

use Spatie\Enum\Enum;

/**
 * @method static self virtual()
 * @method static self plastic()
 */
class LoyaltyCardTypeEnum extends Enum
{
    private const VIRTUAL = 'virtual';

    private const PLASTIC = 'plastic';

    protected static function labels(): array
    {
        return [
            self::VIRTUAL => 'Виртуальная',
            self::PLASTIC => 'Пластиковая',
        ];
    }
}

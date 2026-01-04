<?php

namespace Domain\Order\Enums\OrderSetting;

use Spatie\Enum\Enum;

/**
 * @method static self callAndAsk()
 */
class WeightProductOrderSettingEnum extends Enum
{
    private const CALL_AND_ASK = 'call_and_ask';

    public static function values(): array
    {
        return [
            'callAndAsk'        => self::CALL_AND_ASK,
        ];
    }
}

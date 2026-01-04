<?php

namespace Domain\Order\Enums\OrderSetting;

use Spatie\Enum\Enum;

/**
 * @method static self electronicCheck()
 * @method static self paperCheck()
 */
class CheckTypeProductOrderSettingEnum extends Enum
{
    private const ELECTRONIC_CHECK = 'electronic_check';
    private const PAPER_CHECK = 'paper_check';

    public static function values(): array
    {
        return [
            'electronicCheck'   => self::ELECTRONIC_CHECK,
            'paperCheck'        => self::PAPER_CHECK,
        ];
    }
}

<?php

namespace Domain\Order\Enums\OrderSetting;

use Spatie\Enum\Enum;

/**
 * @method static self requestAndChange()
 * @method static self noChange()
 * @method static self noRequestAndChange()
 */
class UnavailableProductOrderSettingEnum extends Enum
{
    private const REQUEST_AND_CHANGE = 'request_and_change';
    private const NO_CHANGE = 'no_change';
    private const NO_REQUEST_AND_CHANGE = 'no_request_and_change';

    public static function values(): array
    {
        return [
            'requestAndChange'      => self::REQUEST_AND_CHANGE,
            'noChange'              => self::NO_CHANGE,
            'noRequestAndChange'    => self::NO_REQUEST_AND_CHANGE,
        ];
    }
}

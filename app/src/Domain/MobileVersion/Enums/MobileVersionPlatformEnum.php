<?php

namespace Domain\MobileVersion\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self ios()
 * @method static self android()
 */
class MobileVersionPlatformEnum extends Enum
{
    const PLATFORM_IOS = 'ios';
    const PLATFORM_ANDROID   = 'android';

    protected static function labels(): array
    {
        return [
            self::PLATFORM_IOS => 'IOS',
            self::PLATFORM_ANDROID => 'Android',
        ];
    }
}

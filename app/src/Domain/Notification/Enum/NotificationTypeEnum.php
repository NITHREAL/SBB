<?php

namespace Domain\Notification\Enum;

use Spatie\Enum\Enum;

/**
 * @method static self push()
 * @method static self custom()
 * @method static self customMass()
 */
class NotificationTypeEnum extends Enum
{
    private const TYPE_PUSH = 'push';
    private const TYPE_CUSTOM = 'custom';
    private const TYPE_CUSTOM_MASS = 'customMass';

    protected static function values(): array
    {
        return [
            self::TYPE_PUSH         => 'Infrastructure\Notifications\PushNotification',
            self::TYPE_CUSTOM       => 'Infrastructure\Notifications\CustomInternalNotification',
            self::TYPE_CUSTOM_MASS  => 'Infrastructure\Notifications\CustomMassInternalNotification',
        ];
    }

    protected static function labels(): array
    {
        return [
            self::TYPE_PUSH         => self::TYPE_PUSH ,
            self::TYPE_CUSTOM       => self::TYPE_CUSTOM ,
            self::TYPE_CUSTOM_MASS  => self::TYPE_CUSTOM_MASS,
        ];
    }
}

<?php

namespace Domain\Notification\Enum;

use Spatie\Enum\Enum;

/**
 * @method static self audience()
 * @method static self custom()
 * @method static self personalized()
 */
class NotificationRecipientTypeEnum extends Enum
{
    private const TYPE_AUDIENCE = 'audience';
    private const TYPE_CUSTOM = 'custom';
    private const TYPE_PERSONALIZED = 'personalized';

    protected static function values(): array
    {
        return [
            self::TYPE_AUDIENCE => 'audience',
            self::TYPE_CUSTOM => 'custom',
            self::TYPE_PERSONALIZED => 'personalized',
        ];
    }

    protected static function labels(): array
    {
        return [
            self::TYPE_AUDIENCE => __('admin.notifications.audience'),
            self::TYPE_CUSTOM => __('admin.notifications.custom'),
            self::TYPE_PERSONALIZED => __('admin.notifications.personalized'),
        ];
    }
}

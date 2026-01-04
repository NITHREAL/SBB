<?php

namespace Domain\Notification\Enum;

use Spatie\Enum\Enum;

/**
 * @method static self sms()
 * @method static self email()
 * @method static self app()
 */
class NotificationSendMethodEnum extends Enum
{
    private const TYPE_SMS = 'sms';
    private const TYPE_EMAIL = 'email';
    private const TYPE_APP = 'app';

    protected static function values(): array
    {
        return [
            self::TYPE_SMS => 'sms',
            self::TYPE_EMAIL => 'email',
            self::TYPE_APP => 'app',
        ];
    }

    protected static function labels(): array
    {
        return [
            self::TYPE_SMS => __('admin.notifications.sms'),
            self::TYPE_EMAIL => __('admin.notifications.email'),
            self::TYPE_APP => __('admin.notifications.app'),
        ];
    }
}

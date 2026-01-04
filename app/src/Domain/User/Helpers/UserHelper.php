<?php

namespace Domain\User\Helpers;

class UserHelper
{
    private const DEFAULT_USER_NAME = 'Аноним';

    private const QRCODE_FORMAT = '.svg';

    private const USER_QRCODE_DIRECTORY = 'qrcodes';

    public static function getDefaultUserName(): string
    {
        return self::DEFAULT_USER_NAME;
    }

    public static function getUserQrCodePath($cardNumber): string
    {
        return sprintf('/%s/%u%s', self::USER_QRCODE_DIRECTORY, $cardNumber, self::QRCODE_FORMAT);
    }
}

<?php

namespace Infrastructure\Services\Auth\Helpers;

use Random\RandomException;

class SmsCodeHelper
{
    /**
     * @throws RandomException
     */
    public static function generateCode(): string
    {
        return random_int(1000, 9999);
    }

    public static function generateTechCode(string $phone): string
    {
        return substr($phone, -4);
    }
}

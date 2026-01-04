<?php

namespace Infrastructure\Helpers;

class PhoneFormatterHelper
{
    public static function format(?string $phone): string
    {
        if (is_null($phone))
        {
            return '';
        }
        $phone = self::unformat($phone);

        return "+7 (" . substr($phone, 0, 3) . ") " .
            substr($phone, 3, 3) . "-" .
            substr($phone, 6, 2) . "-" .
            substr($phone, 8, 2);
    }

    public static function unformat(?string $phone): string
    {
        if (is_null($phone)) {
            return '';
        }
        $phone = preg_replace('/[^0-9]/', '', $phone);

        return  substr($phone, -10);
    }

    public static function addPrefixToPhone(string $phone): string
    {
        return sprintf('+7%s', $phone);
    }
}

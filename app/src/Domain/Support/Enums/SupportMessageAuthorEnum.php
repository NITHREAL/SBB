<?php

namespace Domain\Support\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self user()
 * @method static self administrator()
 */
class SupportMessageAuthorEnum extends Enum
{
    private const USER = 'user';
    private const ADMINISTRATOR = 'administrator';

    protected static function values(): array
    {
        return [
            self::USER          => 'user',
            self::ADMINISTRATOR => 'administrator',
        ];
    }

    protected static function labels(): array
    {
       return [
           self::USER          => 'Пользователь',
           self::ADMINISTRATOR => 'Администратор',
       ];
    }
}

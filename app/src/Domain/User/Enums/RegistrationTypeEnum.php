<?php

namespace Domain\User\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self app()
 * @method static self web()
 * @method static self admin()
 */
class RegistrationTypeEnum extends Enum
{
    private const TYPE_APP = 'app';
    private const TYPE_WEB = 'web';
    private const TYPE_ADMIN = 'admin';

    protected static function labels(): array
    {
        return [
            self::TYPE_APP  => 'В приложении ',
            self::TYPE_WEB   => 'На сайте',
            self::TYPE_ADMIN   => 'В админ-панели',
        ];
    }
}

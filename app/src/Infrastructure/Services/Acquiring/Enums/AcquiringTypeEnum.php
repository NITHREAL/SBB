<?php

namespace Infrastructure\Services\Acquiring\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self sberbank()
 * @method static self yookassa()
 */
class AcquiringTypeEnum extends Enum
{
    private const SBERBANK = 'sberbank';

    private const YOOKASSA = 'yookassa';

    protected static function labels(): array
    {
        return [
            self::SBERBANK  => 'Сбербанк',
            self::YOOKASSA  => 'Юкасса',
        ];
    }
}

<?php

namespace Infrastructure\Services\Acquiring\Helpers;

use Infrastructure\Services\Acquiring\Enums\AcquiringTypeEnum;

class AcquiringHelper
{
    public static function getAcquiringType(): string
    {
        return config('api.acquiring.type');
    }

    public static function getAcquiringConfig(): array
    {
        return config('api.acquiring');
    }

    public static function isTypeSberbank(): bool
    {
        return self::getAcquiringType() === AcquiringTypeEnum::sberbank()->value;
    }

    public static function isTypeYookassa(): bool
    {
        return self::getAcquiringType() === AcquiringTypeEnum::yookassa()->value;
    }
}

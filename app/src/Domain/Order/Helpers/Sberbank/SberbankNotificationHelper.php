<?php

namespace Domain\Order\Helpers\Sberbank;

use Domain\Order\Enums\Sberbank\SberbankOperationEnum;

class SberbankNotificationHelper
{
    public static function isPaymentHeld(string $operation): bool
    {
        return $operation === SberbankOperationEnum::approved()->value;
    }

    public static function isPaymentDeposited(string $operation): bool
    {
        return $operation === SberbankOperationEnum::deposited()->value;
    }
}

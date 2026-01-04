<?php

namespace Domain\Order\Helpers\Yookassa;

use Domain\Order\Enums\Yookassa\YookassaNotificationEventEnum;

class YookassaNotificationHelper
{
    public static function isPaymentHeld(string $eventType): bool
    {
        return $eventType === YookassaNotificationEventEnum::held()->value;
    }

    public static function isPaymentDeposit(string $eventType): bool
    {
        return $eventType === YookassaNotificationEventEnum::deposit()->value;
    }

    public static function isPaymentCanceled(string $eventType): bool
    {
        return $eventType === YookassaNotificationEventEnum::canceled()->value;
    }
}

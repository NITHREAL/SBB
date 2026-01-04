<?php

namespace Domain\Order\Enums\Yookassa;

use Spatie\Enum\Enum;

/**
 * @method static self held()
 * @method static self deposit()
 * @method static self canceled()
 */
class YookassaNotificationEventEnum extends Enum
{
    private const HELD = 'payment.waiting_for_capture';

    private const DEPOSIT = 'payment.succeeded';

    private const CANCELED = 'payment.canceled';

    protected static function labels(): array
    {
        return [
            'held'      => 'Средства захолдированы',
            'deposit'   => 'Средства списаны',
            'canceled'  => 'Отмена операции',
        ];
    }

    protected static function values(): array
    {
        return [
            'held'      => self::HELD,
            'deposit'   => self::DEPOSIT,
            'canceled'  => self::CANCELED,
        ];
    }
}

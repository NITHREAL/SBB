<?php

namespace Infrastructure\Services\Acquiring\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self registered()
 * @method static self hold()
 * @method static self deposit()
 * @method static self reverse()
 * @method static self refund()
 * @method static self initAuth()
 * @method static self decline()
 */
class SberbankPaymentStatusEnum extends Enum
{
    private const REGISTERED = 0;

    private const HOLD = 1;

    private const DEPOSIT = 2;

    private const REVERSE = 3;

    private const REFUND = 4;

    private const INIT_AUTH = 5;

    private const DECLINE = 6;

    protected static function labels(): array
    {
        return [
            self::REGISTERED    => 'Зарегистрирован, но не оплачен',
            self::HOLD          => 'Сумма удержана',
            self::DEPOSIT       => 'Сумма списана',
            self::REVERSE       => 'Платеж отменен',
            self::REFUND        => 'Проведен возврат',
            self::INIT_AUTH     => 'Инициирован платеж',
            self::DECLINE       => 'Платеж отклонен'

        ];
    }

    protected static function values(): array
    {
        return [
            'registered' => self::REGISTERED,
            'hold'       => self::HOLD,
            'deposit'    => self::DEPOSIT,
            'reverse'    => self::REVERSE,
            'refund'     => self::REFUND,
            'initAuth'   => self::INIT_AUTH,
            'decline'    => self::DECLINE,
        ];
    }
}

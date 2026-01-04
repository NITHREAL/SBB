<?php

namespace Domain\Order\Enums\Payment;

use Spatie\Enum\Enum;

/**
 * @method static self registered()
 * @method static self hold()
 * @method static self deposit()
 * @method static self reverse()
 * @method static self decline()
 * @method static self refund()
 * @method static self initAuth()
 * @method static self error()
 */
class PaymentStatusEnum extends Enum
{
    protected static function labels(): array
    {
        return [
            'registered'    => 'Не оплачен',
            'hold'          => 'Сумма удержана',
            'deposit'       => 'Средства получены',
            'reverse'       => 'Отмена оплаты',
            'decline'       => 'Авторизация отклонена',
            'initAuth'      => 'Init auth',
            'refund'        => 'Средства возвращены',
            'error'         => 'Ошибка'
        ];
    }
}

<?php

namespace Domain\Order\Enums\Sberbank;

use Spatie\Enum\Enum;

/**
 * @method static self created()
 * @method static self approved()
 * @method static self deposited()
 * @method static self reversed()
 * @method static self refunded()
 * @method static self declinedByTimeout()
 * @method static self subscriptionCreated()
 */
class SberbankOperationEnum extends Enum
{
    private const CREATED = 'created';

    private const APPROVED = 'approved';

    private const DEPOSITED = 'deposited';

    private const REVERSED = 'reversed';

    private const REFUNDED = 'refunded';

    private const DECLINED_BY_TIMEOUT = 'declinedByTimeout';

    private const SUBSCRIPTION_CREATED = 'subscriptionCreated';

    protected static function labels(): array
    {
        return [
            self::CREATED               => 'Заказ создан',
            self::APPROVED              => 'Операция холдирования суммы',
            self::DEPOSITED             => 'Операция завершения',
            self::REVERSED              => 'Операция отмены',
            self::REFUNDED              => 'Операция возврата',
            self::DECLINED_BY_TIMEOUT   => 'Истекло время, отведенное на оплату заказа',
            self::SUBSCRIPTION_CREATED  => 'Подписка была создана плательщиком',
        ];
    }
}

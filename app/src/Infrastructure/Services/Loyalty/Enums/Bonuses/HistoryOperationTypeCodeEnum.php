<?php

namespace Infrastructure\Services\Loyalty\Enums\Bonuses;

use Spatie\Enum\Enum;

/**
 * @method static self check()
 * @method static self order()
 * @method static self refundByCheck()
 * @method static self refundByOrder()
 * @method static self chargeByTask()
 * @method static self expired()
 * @method static self manualBonuses()
 * @method static self byRequest()
 * @method static self couponByPersonalPanel()
 * @method static self transferOfBonuses()
 * @method static self preCalculatedBonus()
 * @method static self bonusForRefererByCoupon()
 * @method static self couponSale()
 * @method static self couponBuy()
 */
class HistoryOperationTypeCodeEnum extends Enum
{
    private const CHECK = 1;
    private const ORDER = 2;
    private const REFUND_BY_CHECK = 3;
    private const REFUND_BY_ORDER = 4;
    private const CHARGE_BY_TASK = 5;
    private const EXPIRED = 6;
    private const MANUAL_BONUSES = 7;
    private const BY_REQUEST = 8;
    private const COUPON_BY_PERSONAL_PANEL = 9;
    private const TRANSFER_OF_BONUSES = 10;
    private const PRE_CALCULATED_BONUSES = 11;
    private const BONUS_FOR_REFERER_BY_COUPON = 12;
    private const COUPON_SALE = 13;
    private const COUPON_BUY = 14;

    protected static function labels(): array
    {
        return [
            'check'                     => 'Чек',
            'order'                     => 'Заказ',
            'refundByCheck'             => 'Возврат по чеку',
            'refundByOrder'             => 'Возврат по заказу',
            'chargeByTask'              => 'Начисление по заданию',
            'expired'                   => 'Сгорание бонусов',
            'manualBonuses'             => 'Ручной бонус',
            'byRequest'                 => 'По запросу',
            'couponByPersonalPanel'     => 'Выпуск купона через ЛК',
            'transferOfBonuses'         => 'Перевод бонусов',
            'preCalculatedBonus'        => 'Предначисленный бонус',
            'bonusForRefererByCoupon'   => 'Начисление баллов рефереру за применение купона',
            'couponSale'                => 'Продажа купона',
            'couponBuy'                 => 'Покупка купона',
        ];
    }

    protected static function values(): array
    {
        return [
            'check'                     => self::CHECK,
            'order'                     => self::ORDER,
            'refundByCheck'             => self::REFUND_BY_CHECK,
            'refundByOrder'             => self::REFUND_BY_ORDER,
            'chargeByTask'              => self::CHARGE_BY_TASK,
            'expired'                   => self::EXPIRED,
            'manualBonuses'             => self::MANUAL_BONUSES,
            'byRequest'                 => self::BY_REQUEST,
            'couponByPersonalPanel'     => self::COUPON_BY_PERSONAL_PANEL,
            'transferOfBonuses'         => self::TRANSFER_OF_BONUSES,
            'preCalculatedBonus'        => self::PRE_CALCULATED_BONUSES,
            'bonusForRefererByCoupon'   => self::BONUS_FOR_REFERER_BY_COUPON,
            'couponSale'                => self::COUPON_SALE,
            'couponBuy'                 => self::COUPON_BUY,
        ];
    }

}

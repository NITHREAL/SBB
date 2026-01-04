<?php

namespace Domain\Order\Enums\Payment;

use Spatie\Enum\Enum;

/**
 * @method static self byOnline()
 * @method static self byCardOnDelivery()
 * @method static self byCashOnDelivery()
 * @method static self byStore()
 * @method static self sbp()
 * @method static self sberpay()
 **/
class PaymentTypeEnum extends Enum
{
    private const BY_ONLINE = 'by_online';
    private const BY_CARD_ON_DELIVERY = 'by_card_on_delivery';
    private const BY_CASH_ON_DELIVERY = 'by_cash_on_delivery';
    private const BY_STORE = 'by_store';
    private const SBP = 'sbp';
    private const SBERPAY = 'sberpay';

    protected static function labels(): array
    {
        return [
            'byOnline'          => 'Онлайн',
            'byCardOnDelivery'  => 'По карте курьеру',
            'byCashOnDelivery'  => 'Наличными курьеру',
            'byStore'           => 'Оплата в магазине',
            'sbp'               => 'Система быстрых платежей',
            'sberpay'   => 'SberPay',
        ];
    }

    protected static function values(): array
    {
        return [
            'byOnline'          => self::BY_ONLINE,
            'byCardOnDelivery'  => self::BY_CARD_ON_DELIVERY,
            'byCashOnDelivery'  => self::BY_CASH_ON_DELIVERY,
            'byStore'           => self::BY_STORE,
            'sbp'               => self::SBP,
            'sberpay'           => self::SBERPAY,
        ];
    }
}

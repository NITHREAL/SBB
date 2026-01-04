<?php

namespace Domain\Order\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self waitingPayment()
 * @method static self payed()
 * @method static self created()
 * @method static self accepted()
 * @method static self collecting()
 * @method static self collected()
 * @method static self delivering()
 * @method static self completed()
 * @method static self canceled()
 * @method static self canceledByCustomer()
 * @method static self surcharge()
 */
class OrderStatusEnum extends Enum
{
    private const STATUS_PAYED = 'payed';
    private const STATUS_CREATED = 'created';
    private const STATUS_ACCEPTED = 'accepted';
    private const STATUS_COLLECTING = 'collecting';
    private const STATUS_COLLECTED = 'collected';
    private const STATUS_DELIVERING = 'delivering';
    private const STATUS_COMPLETED = 'completed';
    private const STATUS_CANCELED = 'canceled';
    private const STATUS_CANCELED_BY_CUSTOMER = 'canceled_by_customer';
    private const STATUS_WAITING_PAYMENT = 'waiting_payment';
    private const STATUS_ADDITIONAL_PAYMENT = 'surcharge';


    protected static function labels(): array
    {
        return [
            'waitingPayment'                    => 'Ожидание оплаты',
            self::STATUS_PAYED                  => 'Оплачен',
            self::STATUS_CREATED                => 'Создан',
            self::STATUS_ACCEPTED               => 'Принят',
            self::STATUS_COLLECTING             => 'Собирается',
            self::STATUS_COLLECTED              => 'Собран',
            self::STATUS_DELIVERING             => 'Передан курьеру',
            self::STATUS_COMPLETED              => 'Завершен',
            self::STATUS_CANCELED               => 'Отменен',
            'canceledByCustomer'                => 'Отменен покупателем',
            self::STATUS_ADDITIONAL_PAYMENT     => 'Ожидание доплаты',
        ];
    }

    protected static function values(): array
    {
        return [
            'waitingPayment'        => self::STATUS_WAITING_PAYMENT,
            'payed'                 => self::STATUS_PAYED,
            'created'               => self::STATUS_CREATED,
            'accepted'              => self::STATUS_ACCEPTED,
            'collecting'            => self::STATUS_COLLECTING,
            'collected'             => self::STATUS_COLLECTED,
            'delivering'            => self::STATUS_DELIVERING,
            'completed'             => self::STATUS_COMPLETED,
            'canceled'              => self::STATUS_CANCELED,
            'canceledByCustomer'    => self::STATUS_CANCELED_BY_CUSTOMER,
            'surcharge'             => self::STATUS_ADDITIONAL_PAYMENT,
        ];
    }

}

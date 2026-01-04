<?php

namespace Domain\Order\Helpers\Payment;

use Domain\Order\Enums\Payment\PaymentTypeEnum;

class PaymentHelper
{
    public static function isPaymentOnline(string $paymentType): bool
    {
        return $paymentType === PaymentTypeEnum::byOnline()->value;
    }

    public static function isPaymentSBP(string $paymentType): bool
    {
        return $paymentType === PaymentTypeEnum::sbp()->value;
    }

    public static function isPaymentTypeSberpay(string $paymentType): bool
    {
        return $paymentType === PaymentTypeEnum::sberpay()->value;
    }

    public static function isAvailableOnlinePaymentType(string $paymentType): bool
    {
        return in_array($paymentType, self::getAvailableOnlinePaymentTypes());
    }

    public static function isPermanentAvailableType(string $paymentType): bool
    {
        return in_array($paymentType, self::getPermanentAvailableTypes());
    }

    public static function isPaymentCashlessOnline(string $paymentType): bool
    {
        return in_array(
            $paymentType,
            [
                PaymentTypeEnum::byOnline()->value,
                PaymentTypeEnum::sbp()->value,
                PaymentTypeEnum::sberpay()->value,
            ],
        );
    }

    public static function getPermanentAvailableTypes(): array
    {
        return [
            PaymentTypeEnum::byOnline()->value,
            PaymentTypeEnum::byStore()->value,
            PaymentTypeEnum::sbp()->value,
            PaymentTypeEnum::sberpay()->value,
        ];
    }

    public static function getAvailableOnlinePaymentTypes(): array
    {
        return [
            PaymentTypeEnum::byOnline()->value,
            PaymentTypeEnum::sbp()->value,
            PaymentTypeEnum::sberpay()->value,
        ];
    }
}

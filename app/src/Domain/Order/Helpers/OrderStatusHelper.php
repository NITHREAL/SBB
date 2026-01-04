<?php

namespace Domain\Order\Helpers;

use Domain\Order\Enums\OrderStatusEnum;

class OrderStatusHelper
{
    public static function getPendingStatuses(): array
    {
        return [
            OrderStatusEnum::waitingPayment()->value,
            OrderStatusEnum::surcharge()->value,
            OrderStatusEnum::created()->value,
            OrderStatusEnum::accepted()->value,
            OrderStatusEnum::collecting()->value,
            OrderStatusEnum::collected()->value,
            OrderStatusEnum::payed()->value,
            OrderStatusEnum::delivering()->value,
        ];
    }

    public static function getFinishedStatuses(): array
    {
        return [
            OrderStatusEnum::canceled()->value,
            OrderStatusEnum::canceledByCustomer()->value,
            OrderStatusEnum::completed()->value
        ];
    }

    public static function getCanceledStatuses(): array
    {
        return [
            OrderStatusEnum::canceled()->value,
            OrderStatusEnum::canceledByCustomer()->value,
        ];
    }

    public static function getSupportableStatuses(): array
    {
        return [
            OrderStatusEnum::payed()->value,
            OrderStatusEnum::created()->value,
            OrderStatusEnum::waitingPayment()->value,
            OrderStatusEnum::accepted()->value,
            OrderStatusEnum::collected()->value,
            OrderStatusEnum::surcharge()->value,
            OrderStatusEnum::canceledByCustomer()->value,
            OrderStatusEnum::canceled()->value,
            OrderStatusEnum::completed()->value
        ];
    }
}

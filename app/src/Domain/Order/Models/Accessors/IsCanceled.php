<?php

namespace Domain\Order\Models\Accessors;

use Domain\Order\Helpers\OrderStatusHelper;
use Domain\Order\Models\Order;

final class IsCanceled
{
    public function __construct(
        private readonly Order $order,
    ) {
    }

    public function __invoke(): bool
    {
        return in_array($this->order->status, OrderStatusHelper::getCanceledStatuses());
    }
}

<?php

namespace Domain\Order\Models\Accessors;

use Domain\Order\Enums\OrderStatusEnum;
use Domain\Order\Models\Order;

final class IsRepeatable
{
    public function __construct(
        private readonly Order $order,
    ) {
    }

    public function __invoke(): bool
    {
        $statuses = OrderStatusEnum::toValues();

        return in_array($this->order->status, $statuses);
    }
}

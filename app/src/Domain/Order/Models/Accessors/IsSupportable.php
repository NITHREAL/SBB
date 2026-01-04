<?php

namespace Domain\Order\Models\Accessors;

use Domain\Order\Enums\Delivery\DeliveryTypeEnum;
use Domain\Order\Enums\OrderStatusEnum;
use Domain\Order\Helpers\OrderStatusHelper;
use Domain\Order\Models\Order;

final class IsSupportable
{
    public array $supportableStatuses;

    public function __construct(
        private readonly Order $order,
    ) {
        $this->supportableStatuses = OrderStatusHelper::getSupportableStatuses();
    }

    public function __invoke(): bool
    {
        $isSupportable = in_array($this->order->status, $this->supportableStatuses);

        if (
            $this->order->status == OrderStatusEnum::collected()->value
            && !$this->order->delivery_type == DeliveryTypeEnum::pickup()->value
        ) {
            $isSupportable = false;
        }

        return $isSupportable;
    }
}

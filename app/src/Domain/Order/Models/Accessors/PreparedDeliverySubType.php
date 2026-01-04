<?php

namespace Domain\Order\Models\Accessors;

use Domain\Order\Helpers\Delivery\OrderDeliveryHelper;
use Domain\Order\Models\Order;
use Illuminate\Support\Arr;

final class PreparedDeliverySubType
{
    public function __construct(
        private readonly Order $order,
    ) {
    }

    public function __invoke(): ?string
    {
        $deliverySubType = $this->order->delivery_sub_type;

        return $deliverySubType
            ? Arr::get(
                OrderDeliveryHelper::getDeliverySubTypesData($this->order->delivery_type),
                $this->order->delivery_sub_type
            )
            : null;
    }
}

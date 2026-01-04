<?php

namespace Domain\Order\Models\Accessors;

use Domain\Order\Helpers\Delivery\OrderDeliveryHelper;
use Domain\Order\Models\Order;
use Domain\Product\Models\Product;

final class Total
{
    private string $orderProductTotalFieldName = 'total';

    private string $orderProductTotalWithoutDiscountFieldName = 'total_without_discount';

    public function __construct(
        private readonly Order $order,
        private readonly bool $withDiscount = false,
        private readonly bool $withDelivery = false,
    ) {
    }

    public function __invoke(): float
    {
        $prop = $this->getOrderProductTotalFieldName();

        $total = $this->order
            ->products
            ->sum(fn(Product $product) => $product->pivot->$prop);

        if ($this->withDelivery && OrderDeliveryHelper::isDelivery($this->order->delivery_type)) {
            $total += $this->order->delivery_cost;
        }

        if ($this->withDiscount) {
            $total = max($total - $this->order->discount, 0);
        }

        return round($total, 2);
    }

    private function getOrderProductTotalFieldName(): string
    {
        return $this->withDiscount
            ? $this->orderProductTotalFieldName
            : $this->orderProductTotalWithoutDiscountFieldName;
    }
}

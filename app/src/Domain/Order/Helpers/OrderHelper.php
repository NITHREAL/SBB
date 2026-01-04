<?php

namespace Domain\Order\Helpers;

use Domain\Order\Enums\OrderStatusEnum;
use Domain\Order\Helpers\Delivery\OrderDeliveryHelper;
use Domain\Order\Models\Order;
use Domain\Product\Models\Product;
use Illuminate\Support\Str;

class OrderHelper
{
    public static function makeUuid(): string
    {
        return Str::uuid();
    }

    public static function makeSystemId(int $orderId): string
    {
        return substr(sprintf('0000000000%s', $orderId), -10);
    }

    public static function getIdFromSystemId(string $systemId): int
    {
        return (int) $systemId;
    }

    public static function isPaymentNeed(Order $order): bool
    {
        return in_array(
            $order->status,
            [
                OrderStatusEnum::surcharge()->value,
                OrderStatusEnum::waitingPayment()->value,
            ]
        );
    }

    public static function isCancellationAvailable(Order $order): bool
    {
        // Если сопособ доставки заказа "доставка" и статус заказа "Доставляется"
        if (
            OrderDeliveryHelper::isDelivery($order->delivery_type)
            && $order->status === OrderStatusEnum::delivering()->value
        ) {
            return false;
        }

        // Если заказ завершен
        if ($order->isCompleted) {
            return false;
        }

        // Если в заказе есть товары по предзаказу
        $existsPreorderProducts = $order->products->contains(fn($product) => $product->by_preorder);

        if ($existsPreorderProducts) {
            return false;
        }

        return true;
    }

    public static function getTotal(
        Order $order,
        bool $withDiscount = false,
        bool $withDeliveryCost = false,
    ): float {
        $total = $order->products->sum(
            fn(Product $product) => (float) ($product->total_without_discount ?? $product->pivot?->total_without_discount)
        );

        if ($withDeliveryCost && OrderDeliveryHelper::isDelivery($order->delivery_type)) {
            $total += $order->delivery_cost;
        }

        if ($withDiscount) {
            $total = max($total - $order->discount, 0);
        }

        return round($total, 2);
    }

    public static function getBatchNumber(): string
    {
        do {
            $currentBatchNumber = (string) Str::uuid();

            $order = Order::query()->whereBatch($currentBatchNumber)->first();

        } while(!empty($order));

        return $currentBatchNumber;
    }
}

<?php

namespace Domain\Order\Services;

use Domain\Order\Enums\OrderStatusEnum;
use Domain\Order\Exceptions\OrderException;
use Domain\Order\Models\Order;
use Domain\Order\Models\OrderProduct;
use Domain\Order\Services\Payment\Exceptions\PaymentException;
use Domain\Order\Services\Payment\InitPaymentService;

readonly class OrderTestService
{
    public function __construct(
        private InitPaymentService $initPaymentService,
    ) {
    }

    public function makeOrderCollected(int $orderId, int $amount): void
    {
        $amount = round($amount, 2);

        $order = Order::query()->whereId($orderId)->firstOrFail();

        if ($amount) {
            $this->changeOrderTotal($order, $amount);

            $order->status = OrderStatusEnum::surcharge()->value;
        } else {
            $order->status = OrderStatusEnum::collected()->value;
        }

        $order->save();
    }

    public function makeOrderCompleted(int $orderId): void
    {
        $order = Order::query()->whereId($orderId)->firstOrFail();
        $order->status = OrderStatusEnum::completed()->value;
        $order->save();
    }

    /**
     * @throws OrderException
     * @throws PaymentException
     */
    public function checkOrderInitPayment(int $orderId): void
    {
        /** @var Order $order */
        $order = Order::query()->findOrFail($orderId);

        $initPayment = $order->getLastRegisteredOnlinePayment();

        if (empty($initPayment)) {
            throw new OrderException('Не найден первичный платеж');
        }

        $this->initPaymentService->processOrderInitPayment($initPayment, $order);
    }

    private function changeOrderTotal(Order $order, float $amount): void
    {
        $orderTotal = $order->getHeldOnlinePayments()->sum(fn($payment) => $payment->amount);
        $randomOrderProduct = OrderProduct::query()
            ->where('order_id', $order->id)
            ->first();

        if ($amount > $orderTotal) {
            $changeValue = $amount - $orderTotal;
            $newTotal = $randomOrderProduct->total + $changeValue;

            $randomOrderProduct->total = $newTotal;
            $randomOrderProduct->total_without_discount = $newTotal;
        } else {
            $changeValue = $orderTotal - $amount;
            $newTotal = $randomOrderProduct->total - $changeValue;

            $randomOrderProduct->total = $newTotal;
            $randomOrderProduct->total_without_discount = $newTotal;
        }

        $randomOrderProduct->save();
    }
}

<?php

namespace Domain\Order\Services\Payment;

use Domain\Order\Enums\OrderStatusEnum;
use Domain\Order\Models\Order;

class PaymentResultProcessService
{
    public function __construct(
        protected PaymentBindingService    $onlinePaymentBindingService,
        protected PaymentService           $paymentService,
    ) {
    }

    public function processPreAuthPaymentResult(Order $order): void
    {
        if (in_array($order->status, [OrderStatusEnum::surcharge()->value, OrderStatusEnum::waitingPayment()->value])) {
            $this->paymentService->registerHoldByBinding($order);
        }
    }
}

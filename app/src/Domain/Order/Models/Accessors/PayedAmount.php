<?php

namespace Domain\Order\Models\Accessors;

use Domain\Order\Models\Order;
use Domain\Order\Models\Payment\OnlinePayment;

final class PayedAmount
{
    public function __construct(
        private readonly Order $order,
    ) {
    }

    public function __invoke(): float
    {
        $amount = $this->order
            ->getPayedOnlinePayments()
            ->sum(fn (OnlinePayment $payment) => $payment->amount);

        return round($amount, 2);
    }
}

<?php

namespace Domain\Order\Jobs\Payment;

use Domain\Order\Models\Payment\OnlinePayment;
use Infrastructure\Jobs\BaseOrderJob;

class RefundPaymentJob extends BaseOrderJob
{
    public function __construct(
        private readonly OnlinePayment $payment,
    ) {
    }

    public function handle(): void
    {
        $this->payment->refund();
        $this->payment->getOrderStatus();
    }
}

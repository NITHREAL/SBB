<?php

namespace Domain\Order\Jobs\Payment;

use Domain\Order\Helpers\Payment\OnlinePaymentHelper;
use Domain\Order\Models\Payment\OnlinePayment;
use Infrastructure\Jobs\BaseOrderJob;

class CheckPaymentStatusJob extends BaseOrderJob
{
    public function __construct(
        private readonly OnlinePayment $payment,
    ) {
    }

    public function handle(): void
    {
        $status = $this->payment->getOrderStatus();

        if ($status->payed()) {
            OnlinePaymentHelper::completePayment($this->payment);
        }
    }
}

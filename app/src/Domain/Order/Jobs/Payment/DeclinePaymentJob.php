<?php

namespace Domain\Order\Jobs\Payment;

use Domain\Order\Models\Payment\OnlinePayment;
use Infrastructure\Jobs\BaseOrderJob;

class DeclinePaymentJob extends BaseOrderJob
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        private readonly OnlinePayment $payment,
    ) {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->payment->decline();
        $this->payment->getOrderStatus();
    }
}

<?php

namespace Domain\Order\Jobs\Payment;

use Domain\Order\Helpers\OrderHelper;
use Domain\Order\Models\Order;
use Domain\Order\Services\Payment\Exceptions\PaymentException;
use Domain\Order\Services\Payment\PaymentResultProcessService;
use Infrastructure\Jobs\BaseOrderJob;

class CheckInitPaymentStatusJob extends BaseOrderJob
{
    public function __construct(
        private readonly Order $order,
    ) {
    }

    /**
     * @throws PaymentException
     */
    public function handle(PaymentResultProcessService $paymentResultProcessService): void
    {
        if (OrderHelper::isPaymentNeed($this->order)) {
            $paymentResultProcessService->processOrderInitPayment($this->order);
        }
    }
}

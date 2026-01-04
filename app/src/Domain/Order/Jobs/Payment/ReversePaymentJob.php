<?php

namespace Domain\Order\Jobs\Payment;

use Domain\Order\Models\Payment\OnlinePayment;
use Domain\Order\Services\Payment\Exceptions\PaymentException;
use Illuminate\Support\Facades\Log;
use Infrastructure\Jobs\BaseOrderJob;

class ReversePaymentJob extends BaseOrderJob
{
    public function __construct(
        private readonly OnlinePayment $payment,
    ) {
    }

    public function handle(): void
    {
        try {
            $this->payment->reverse();

            $this->payment->getOrderStatus();
        } catch (PaymentException $exception) {
            Log::channel('payment')->error(
                sprintf(
                    'Ошибка во время отмены авторизации платежа. ID платежа - [%s]. Ошибка - [%s]',
                    $this->payment->id,
                    $exception->getMessage(),
                )
            );
        }
    }
}

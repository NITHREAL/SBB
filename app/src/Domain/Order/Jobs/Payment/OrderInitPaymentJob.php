<?php

namespace Domain\Order\Jobs\Payment;

use Domain\Order\Models\Order;
use Domain\Order\Models\Payment\OnlinePayment;
use Domain\Order\Services\Payment\Exceptions\PaymentException;
use Domain\Order\Services\Payment\InitPaymentService;
use Illuminate\Support\Facades\Log;
use Infrastructure\Jobs\BaseOrderJob;

class OrderInitPaymentJob extends BaseOrderJob
{
    public function __construct(
        private readonly Order $order,
        private readonly OnlinePayment $initPayment,
    ) {
    }

    public function handle(InitPaymentService $initPaymentService): void
    {
        try {
            Log::channel('payment')->info(
                sprintf(
                    '%s. order_id [%s]. payment_id [%s]',
                    'OrderInitPaymentJob initialized',
                    $this->order->id,
                    $this->initPayment->id
                )
            );
            $initPaymentService->processOrderInitPayment($this->initPayment, $this->order);
        } catch (PaymentException $exception) {
            Log::channel('payment')->error(
                sprintf(
                    '%s. %s. Заказ - [%s]',
                    'Ошибка во время обработки первичного платежа для заказа',
                    $exception->getMessage(),
                    $this->order->id,
                )
            );
        }
    }
}

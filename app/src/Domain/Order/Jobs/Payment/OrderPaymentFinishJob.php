<?php

namespace Domain\Order\Jobs\Payment;

use Domain\Order\Models\Order;
use Domain\Order\Services\Payment\PaymentService;
use Exception;
use Illuminate\Support\Facades\Log;
use Infrastructure\Jobs\BaseOrderJob;

class OrderPaymentFinishJob extends BaseOrderJob
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        protected Order $order
    ) {
    }

    /**
     * Execute the job.
     *
     * @param PaymentService $paymentService
     * @return void
     */
    public function handle(PaymentService $paymentService): void
    {
        try {
            $paymentService->registerDepositDo($this->order);
        } catch (Exception $exception) {
            Log::channel('payment')->error(
                sprintf(
                    '%s. Заказ [%s]. Ошибка - %s',
                    'Ошибка при попытке списания замороженных средств с карты клиента',
                    $this->order->id,
                    $exception->getMessage(),
                )
            );
        }
    }
}

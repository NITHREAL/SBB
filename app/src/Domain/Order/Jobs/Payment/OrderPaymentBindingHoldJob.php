<?php

namespace Domain\Order\Jobs\Payment;

use Domain\Order\Models\Order;
use Domain\Order\Services\Payment\PaymentResultProcessService;
use Illuminate\Support\Facades\Log;
use Infrastructure\Jobs\BaseOrderJob;

class OrderPaymentBindingHoldJob extends BaseOrderJob
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
     * @param PaymentResultProcessService $paymentResultProcessService
     * @return void
     */
    public function handle(PaymentResultProcessService $paymentResultProcessService): void
    {
        try {
            $paymentResultProcessService->processPreAuthPaymentResult($this->order);
        } catch (\Exception $exception) {
            Log::channel('payment')->error(
                sprintf(
                    '%s. Заказ [%s]. Ошибка - %s',
                    'Ошибка при попытке создания предавторизации оплаты',
                    $this->order->id,
                    $exception->getMessage(),
                )
            );
        }
    }
}

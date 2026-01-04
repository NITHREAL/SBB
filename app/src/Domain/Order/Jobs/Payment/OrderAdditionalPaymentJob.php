<?php

namespace Domain\Order\Jobs\Payment;

use Domain\Order\Models\Order;
use Domain\Order\Services\Payment\PaymentService;
use Exception;
use Illuminate\Support\Facades\Log;
use Infrastructure\Jobs\BaseOrderJob;

class OrderAdditionalPaymentJob extends BaseOrderJob
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        protected Order $order,
        protected float $amount,
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
            Log::channel('payment')->info(
                sprintf(
                    '%s. Заказ [%s]. сумма - %s',
                    'инициализированно холдирование доплаты по заказу',
                    $this->order->id,
                    $this->amount,
                )
            );

            $response = $paymentService->registerAdditionalPayment($this->amount, $this->order);

            if ($response->isError()) {
                Log::channel('payment')->error(
                    sprintf(
                        '%s. Заказ [%s]. Ошибка - %s',
                        'Сообщение об ошибке от эквайринга при попытке создания доплаты для заказа',
                        $this->order->id,
                        $response->errorMessage,
                    )
                );
            }
        } catch (Exception $exception) {
            Log::channel('payment')->error(
                sprintf(
                    '%s. Заказ [%s]. Ошибка - %s',
                    'Ошибка при попытке создания доплаты для заказа',
                    $this->order->id,
                    $exception->getMessage(),
                )
            );
        }
    }
}

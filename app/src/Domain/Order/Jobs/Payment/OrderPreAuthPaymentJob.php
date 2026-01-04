<?php

namespace Domain\Order\Jobs\Payment;

use Domain\Order\Models\Order;
use Domain\Order\Services\Payment\PaymentService;
use Illuminate\Support\Facades\Log;
use Infrastructure\Jobs\BaseOrderJob;
use Infrastructure\Services\Acquiring\Helpers\AcquiringHelper;

class OrderPreAuthPaymentJob extends BaseOrderJob
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        protected Order $order,
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
            $response = $paymentService->registerPayment($this->order);

            if ($response->isError()) {
                Log::channel('payment')->error($response->getErrorMessage());
            } elseif (AcquiringHelper::isTypeSberbank()) {
                OrderPaymentBindingHoldJob::dispatch($this->order);
            }
        } catch (\Exception $exception) {
            Log::channel('payment')->error(
                $this->getErrorMessage($exception->getMessage())
            );
        }
    }

    private function getErrorMessage(string $exceptionMessage): string
    {
        return sprintf(
            '%s. Заказ [%s]. Ошибка - %s',
            'Ошибка при попытке создания предавторизации оплаты',
            $this->order->id,
            $exceptionMessage,
        );
    }
}

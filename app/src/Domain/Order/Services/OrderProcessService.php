<?php

namespace Domain\Order\Services;

use Domain\Order\Enums\OrderStatusEnum;
use Domain\Order\Helpers\Payment\OnlinePaymentHelper;
use Domain\Order\Jobs\Payment\OrderAdditionalPaymentJob;
use Domain\Order\Jobs\Payment\OrderPaymentFinishJob;
use Domain\Order\Models\Order;
use Domain\Order\Services\Payment\PaymentService;
use Illuminate\Support\Facades\Log;

class OrderProcessService
{
    public function __construct(
        protected PaymentService $paymentService,
    ) {
    }

    public function processOrderCollected(Order $order): void
    {
        $additionalAmount = OnlinePaymentHelper::getNeededPaymentAmount($order);

        if ($additionalAmount > 0) {
            $order->status = OrderStatusEnum::surcharge()->value;

            $order->save();
        }
    }

    public function processOrderSurcharged(Order $order): void
    {
        Log::channel('payment')->info(
            sprintf(
                '%s. Заказ [%s].',
                'Запущен процесс холдирование доплаты по заказу',
                $order->id,
            )
        );

        $additionalAmount = OnlinePaymentHelper::getNeededPaymentAmount($order);

        Log::channel('payment')->info(
            sprintf(
                '%s. Заказ [%s]. сумма - %s',
                'Расчёт суммы холдирование доплаты по заказу',
                $order->id,
                $additionalAmount,
            )
        );

        if ($additionalAmount > 0) {
            Log::channel('payment')->info(
                sprintf(
                    '%s. Заказ [%s]. Запуск задания OrderAdditionalPaymentJob с суммой - %s',
                    'Запуск задания OrderAdditionalPaymentJob',
                    $order->id,
                    $additionalAmount,
                )
            );

            OrderAdditionalPaymentJob::dispatch($order, $additionalAmount)->delay(10);
        }
    }

    public function processOrderCompleted(Order $order): void
    {
        OrderPaymentFinishJob::dispatch($order)->delay(15);
    }
}

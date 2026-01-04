<?php

namespace Domain\Order\Services\Sberbank;

use Domain\Order\DTO\Sberbank\SberbankNotificationDTO;
use Domain\Order\Helpers\OrderHelper;
use Domain\Order\Jobs\Payment\CheckPaymentStatusJob;
use Domain\Order\Models\Order;
use Domain\Order\Models\Payment\OnlinePayment;
use Illuminate\Support\Facades\Log;

readonly class SberbankDepositedPaymentService
{
    public function processDepositedPayment(SberbankNotificationDTO $sberbankNotificationDTO): void
    {
        /** @var OnlinePayment $payment */
        $payment = OnlinePayment::query()->where('sber_order_id', $sberbankNotificationDTO->getMdOrder())->first();

        if (empty($payment)) {
            Log::channel('payment')->error(
                sprintf(
                    '%s. Идентификатор платежа в эквайринге [%s]',
                    'Не найден указанный платёж при обработке подтвержденного платежа',
                    $sberbankNotificationDTO->getMdOrder(),
                )
            );

            return;
        }

        if ($order = $payment->orders->first()) {
            $this->processOrderPayment($payment, $order, $sberbankNotificationDTO);
        }
    }

    private function processOrderPayment(
        OnlinePayment $payment,
        Order $order,
        SberbankNotificationDTO $sberbankNotificationDTO,
    ): void {
        Log::channel('payment')->info(
            sprintf(
                '%s. Идентификатор платежа в эквайринге [%s]. Идентификатор заказа [%s]',
                'Инициализация обработка платежа по СБП.',
                $sberbankNotificationDTO->getMdOrder(),
                $order->id,
            )
        );

        Log::channel('payment')->info(
            sprintf(
                '%s. is payment need [%s]',
                'Проверка условий необходимости оплаты для заказа СБП',
                OrderHelper::isPaymentNeed($order),
            )
        );

        if (OrderHelper::isPaymentNeed($order)) {
            CheckPaymentStatusJob::dispatch($payment);
        }
    }
}

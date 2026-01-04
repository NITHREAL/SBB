<?php

namespace Domain\Order\Services\Sberbank;

use Domain\Order\DTO\Sberbank\SberbankNotificationDTO;
use Domain\Order\Helpers\OrderHelper;
use Domain\Order\Jobs\Payment\BindingRequestInitPaymentJob;
use Domain\Order\Jobs\Payment\OrderInitPaymentJob;
use Domain\Order\Models\Order;
use Domain\Order\Models\Payment\OnlinePayment;
use Domain\Order\Models\Payment\OnlinePaymentBindingRequest;
use Illuminate\Support\Facades\Log;

readonly class SberbankHeldPaymentService
{
    public function processHeldPayment(SberbankNotificationDTO $sberbankNotificationDTO): void
    {
        /** @var OnlinePayment $payment */
        $payment = OnlinePayment::query()->where('sber_order_id', $sberbankNotificationDTO->getMdOrder())->first();

        if (empty($payment)) {
            Log::channel('payment')->error(
                sprintf(
                    '%s. Идентификатор платежа в эквайринге [%s]',
                    'Не найден указанный платёж',
                    $sberbankNotificationDTO->getMdOrder(),
                )
            );

            return;
        }

        if ($order = $payment->orders->first()) {
            $this->processOrderPayment($payment, $order, $sberbankNotificationDTO);
        }  elseif ($bindingRequest = $payment->bindingRequest) {
            $this->processBindingPayment($payment, $bindingRequest, $sberbankNotificationDTO);
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
                'Инициализация обработка платежа.',
                $sberbankNotificationDTO->getMdOrder(),
                $order->id,
            )
        );

        Log::channel('payment')->info(
            sprintf(
                '%s. is payment need [%s]. отсутствует binding id [%s]',
                'Проверка условий',
                OrderHelper::isPaymentNeed($order),
                empty($order->binding_id),
            )
        );

        if (OrderHelper::isPaymentNeed($order) && empty($order->binding_id)) {
            OrderInitPaymentJob::dispatch($order, $payment);
        }
    }

    private function processBindingPayment(
        OnlinePayment $payment,
        OnlinePaymentBindingRequest $bindingRequest,
        SberbankNotificationDTO $sberbankNotificationDTO,
    ): void {
        Log::channel('payment')->info(
            sprintf(
                '%s. Идентификатор платежа в эквайринге [%s]. Идентификатор пользователя [%s]',
                'Обработка первичного платежа для добавления карты',
                $sberbankNotificationDTO->getMdOrder(),
                $bindingRequest->user_id,
            ),
        );

        BindingRequestInitPaymentJob::dispatch($bindingRequest, $payment);
    }
}

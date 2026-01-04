<?php

namespace Domain\Order\Services\Yookassa;

use Domain\Order\DTO\Yookassa\YookassaNotificationObjectDTO;
use Domain\Order\Helpers\OrderHelper;
use Domain\Order\Jobs\Payment\BindingRequestInitPaymentJob;
use Domain\Order\Jobs\Payment\OrderInitPaymentJob;
use Domain\Order\Models\Order;
use Domain\Order\Models\Payment\OnlinePayment;
use Domain\Order\Models\Payment\OnlinePaymentBindingRequest;
use Illuminate\Support\Facades\Log;

readonly class YookassaHeldPaymentService
{
    public function processHeldPayment(YookassaNotificationObjectDTO $yookassaObjectDTO): void
    {
        /** @var OnlinePayment $payment */
        $payment = OnlinePayment::query()->whereSberId($yookassaObjectDTO->getId())->first();

        if (empty($payment)) {
            Log::channel('payment')->error(
                sprintf(
                    '%s. Идентификатор платежа в эквайринге [%s]',
                    'Не найден указанный платёж',
                    $yookassaObjectDTO->getId(),
                )
            );

            return;
        }

        if ($order = $payment->orders->first()) {
            $this->processOrderPayment($payment, $order, $yookassaObjectDTO);
        } elseif ($bindingRequest = $payment->bindingRequest) {
            $this->processBindingPayment($payment, $bindingRequest, $yookassaObjectDTO);
        }
    }

    private function processOrderPayment(
        OnlinePayment $payment,
        Order $order,
        YookassaNotificationObjectDTO $yookassaObjectDTO,
    ): void {
        Log::channel('payment')->info(
            sprintf(
                '%s. Идентификатор платежа в эквайринге [%s]. Идентификатор заказа [%s]',
                'Инициализация обработка платежа.',
                $yookassaObjectDTO->getId(),
                $order->id,
            )
        );

        Log::channel('payment')->info(
            sprintf(
                '%s. is payment need [%s]. проверка на binding id [%s]',
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
        YookassaNotificationObjectDTO $yookassaObjectDTO,
    ): void {
        Log::channel('payment')->info(
            sprintf(
                '%s. Идентификатор платежа в эквайринге [%s]. Идентификатор пользователя [%s]',
                'Обработка первичного платежа для добавления карты',
                $yookassaObjectDTO->getId(),
                $bindingRequest->user_id,
            ),
        );

        BindingRequestInitPaymentJob::dispatch($bindingRequest, $payment);
    }
}

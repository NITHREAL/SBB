<?php

namespace Domain\Order\Services\Payment;

use Domain\Order\DTO\Payment\OnlinePaymentBindingDTO;
use Domain\Order\Jobs\Payment\ReversePaymentJob;
use Domain\Order\Models\Order;
use Domain\Order\Models\Payment\OnlinePayment;
use Domain\Order\Models\Payment\OnlinePaymentBinding;
use Domain\Order\Models\Payment\OnlinePaymentBindingRequest;
use Domain\Order\Services\Payment\Exceptions\PaymentException;
use Domain\Order\Services\Payment\Exceptions\RegisterPaymentDoException;
use Domain\Order\Services\Payment\Exceptions\RegisterPreAuthException;
use Domain\User\Services\Payment\UserPaymentService;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

readonly class InitPaymentService
{
    public function __construct(
        private PaymentBindingService $paymentBindingService,
        private PaymentService $paymentService,
        private UserPaymentService $userPaymentService,
    ) {
    }

    /**
     * @throws PaymentException
     */
    public function processOrderInitPayment(OnlinePayment $initPayment, Order $order): void
    {
        try {
            Log::channel('payment')->info(
                sprintf(
                    '%s. order_id [%s]. payment_id [%s]',
                    'Инициализирован процесс завершения первичного платежа по заказу',
                    $order->id,
                    $initPayment->id
                )
            );

            $binding = $this->getBinding($initPayment, $order->user_id);

            ReversePaymentJob::dispatch($initPayment)->delay(10);

            $orderBatch = $order->batch;

            // Если у заказа определен идентификатор группы заказов,
            // то используем полученную связку для оплаты всех заказов в этой группе
            if ($orderBatch) {
                $orders = $this->getBatchOrders($orderBatch);

                foreach ($orders as $order) {
                    $this->processOrderPaymentHolding($order, $binding);
                }
            } else {
                $this->processOrderPaymentHolding($order, $binding);
            }
        } catch (Exception $e) {
            Log::channel('payment')->error('Ошибка в процессе initPayment для заказа: ' . $e->getMessage());
        }
    }

    public function processBindingRequestInitPayment(
        OnlinePayment $initPayment,
        OnlinePaymentBindingRequest $bindingRequest,
    ): void {
        try {
            Log::channel('payment')->info(
                sprintf(
                    '%s. user_id [%s]. payment_id [%s]',
                    'Инициализирован процесс завершения первичного платежа для добавления карты',
                    $bindingRequest->user_id,
                    $initPayment->id,
                ),
            );

            $this->getBinding($initPayment, $bindingRequest->user_id);

            $initPayment->reverse();
        } catch (Exception $e) {
            Log::channel('payment')->error('Ошибка в процессе initPayment для добавления карты: ' . $e->getMessage());
        }
    }

    /**
     * @throws PaymentException
     */
    private function getBinding(OnlinePayment $payment, int $userId): OnlinePaymentBinding
    {
        $paymentStatusData = $payment->getOrderStatus();

        if ($paymentStatusData->payed() === false) {
            throw new PaymentException(
                sprintf(
                    '%s. Платеж - [%s]',
                    'Первичный платеж на завершен или возникла ошибка',
                    $payment->id,
                ),
            );
        }

        $bindingDTO = OnlinePaymentBindingDTO::make(
            $paymentStatusData->getBindingId(),
            $userId,
            $paymentStatusData->getAcquiringType(),
            $paymentStatusData->getCardData(),
        );

        Log::channel('payment')->info('Binding создан: ' . json_encode($bindingDTO, JSON_THROW_ON_ERROR));

        return $this->paymentBindingService->getBindingOrCreate($bindingDTO);
    }

    /**
     * @throws RegisterPaymentDoException
     * @throws RegisterPreAuthException
     */
    private function processOrderPaymentHolding(Order $order, OnlinePaymentBinding $binding): void
    {
        $order->binding()->associate($binding);
        $order->save();

        $response = $this->paymentService->registerPayment($order);

        if ($response->isError()) {
            Log::channel('payment')->error($response->getErrorMessage());
        }

        // Делаем для пользователя последний использованный способ оплаты по умолчанию
        // TODO скрываем до реализации способа оплаты по умолчанию
        //$this->userPaymentService->updateDefaultPaymentMethodByOrder($order);
    }

    private function getBatchOrders(string $batch): Collection
    {
        return Order::query()
            ->whereBatch($batch)
            ->whereNull('orders.binding_id')
            ->get();
    }
}

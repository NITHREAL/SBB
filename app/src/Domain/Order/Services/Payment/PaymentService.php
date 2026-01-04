<?php

namespace Domain\Order\Services\Payment;

use Domain\Order\Helpers\OrderHelper;
use Domain\Order\Helpers\Payment\OnlinePaymentHelper;
use Domain\Order\Models\Order;
use Domain\Order\Models\Payment\OnlinePayment;
use Domain\Order\Models\Payment\OnlinePaymentBinding;
use Domain\Order\Services\Payment\Exceptions\RegisterPaymentDoException;
use Domain\Order\Services\Payment\Exceptions\RegisterPreAuthException;
use Illuminate\Support\Facades\Log;
use Infrastructure\Services\Acquiring\Helpers\AcquiringHelper;
use Infrastructure\Services\Acquiring\Responses\GatewayResponseInterface;

class PaymentService
{
    private float $initPaymentAmount;

    public function __construct(
    ) {
        $this->initPaymentAmount = round(config('api.payment_online.init_amount'), 2);
    }

    /**
     * Регистрация оплаты заказа.
     * Если у заказа уже есть связка, то сразу будет выполняться холдирование на сумму заказа.
     * Если у заказа связки нет, то будет выполняться первичный платеж для формирования связки на стороне эквайринга
     *
     * @param Order $order
     * @return GatewayResponseInterface
     * @throws RegisterPaymentDoException
     * @throws RegisterPreAuthException
     */
    public function registerPayment(Order $order): GatewayResponseInterface
    {
        if (empty($order->binding_id)) {
            $response = $this->makeInitPayment($order);
        } else {
            $response = $this->registerPreAuthPayment($order);
        }

        return $response;
    }

    /**
     * @throws RegisterPreAuthException
     */
    public function registerSBPPayment(Order $order): GatewayResponseInterface
    {
        return $this->makeSBPPayment($order);
    }

    /**
     * @throws RegisterPreAuthException
     */
    public function registerSberbankOnlinePayment(Order $order): GatewayResponseInterface
    {
        return $this->makeSberbankOnlinePayment($order);
    }

    /**
     * Регистрация доплаты.
     * Если после сборки заказа его сумма увеличилась, то создается новый платеж с суммой доплаты
     *
     * @throws RegisterPreAuthException
     */
    public function registerAdditionalPayment(float $amount, Order $order): GatewayResponseInterface
    {
        return $this->makePreAuthPayment($amount, $order);
    }

    /**
     * Выполнение холдирования по связке для заказа с предавторизованной оплатой
     *
     * @param Order $order
     * @return void
     */
    public function registerHoldByBinding(Order $order): void
    {
        $payment = $order->getLastRegisteredOnlinePayment();

        $payment->holdByBinding($order);

        $paymentStatus = $payment->getOrderStatus();

        if ($paymentStatus->payed()) {
            OnlinePaymentHelper::completePayment($payment);
        } else {
            Log::channel('payment')->error('Платеж не прошел при холдировании через связку. Заказ - [%s], Связка - [%s]');
        }
    }

    /**
     * Подготовка к списанию средств для онлайн оплат заказа, которые находятся в статусе hold
     *
     * @param Order $order
     * @return void
     * @throws RegisterPaymentDoException
     */
    public function registerDepositDo(Order $order): void
    {
        $this->makeDepositDo($order);
    }

    /**
     * Выполнение первичного платежа для формирования связки клиент/карта на стороне эквайринга
     *
     * @param Order $order
     * @return GatewayResponseInterface
     * @throws RegisterPaymentDoException
     */
    private function makeInitPayment(Order $order): GatewayResponseInterface
    {
        $amount = $this->initPaymentAmount;

        if ($amount <= 0) {
            $message = __('messages.payment_amount_invalid', ['amount' => $amount]);

            throw new RegisterPaymentDoException($message);
        }

        /** @var OnlinePayment $payment */
        $payment = OnlinePayment::query()
            ->create([
                'amount' => $amount,
            ]);

        $order->payments()->attach(
            $payment->id,
            [
                'amount' => $amount,
                'batch'  => $order->batch,
            ]
        );

        $response = $payment->registerInitPayment($amount, $order);

        if ($response->isError()) {
            Log::channel('payment')->info(
                sprintf(
                    '%s. Заказ [%s]. Ошибка - %s',
                    'Сообщение об ошибке от эквайринга при попытке создания доплаты для заказа',
                    $order->id,
                    $response->getErrorMessage(),
                )
            );

            throw new RegisterPaymentDoException($response->getErrorMessage());
        }

        return $response;
    }

    /**
     * Подготовка к выполнению заморозки неоплаченной суммы для заказа (при первоначальной оплате или доплате)
     *
     * @param Order $order
     * @return GatewayResponseInterface
     * @throws RegisterPreAuthException
     */
    private function registerPreAuthPayment(Order $order): GatewayResponseInterface
    {
        $amount = OnlinePaymentHelper::getNeededPaymentAmount($order);

        return $this->makePreAuthPayment($amount, $order);
    }


    /**
     * Выполнение заморозки неоплаченной суммы для заказа (при первоначальной оплате или доплате)
     *
     * @param float $amount
     * @param Order $order
     * @return GatewayResponseInterface
     * @throws RegisterPreAuthException
     */
    private function makePreAuthPayment(float $amount, Order $order): GatewayResponseInterface
    {
        $amount = $this->validateAmount($amount);

        $binding = $this->validateBinding($order);

        /** @var OnlinePayment $payment */
        $payment = OnlinePayment::query()
            ->create([
                'amount'        => $amount,
                'binding_id'    => $binding->id,
            ]);

        $order->payments()->attach($payment->id, ['amount' => $amount]);

        $response = $payment->registerPreAuth($amount, $order, $binding->acquiring_binding_id);

        if ($response->isError()) {
            Log::channel('payment')->error(
                sprintf(
                    '%s. Заказ [%s]. Ошибка - %s',
                    'Сообщение об ошибке от эквайринга при попытке создания доплаты для заказа',
                    $order->id,
                    $response->getErrorMessage(),
                )
            );

            throw new RegisterPreAuthException($response->getErrorMessage());
        }

        // Если тип эквайринга Юкасса, то после проведения предавторизации необходимо изменить статус оплаты заказа
        if (AcquiringHelper::isTypeYookassa()) {
            $paymentStatus = $payment->getOrderStatus();

            if ($paymentStatus->payed()) {
                OnlinePaymentHelper::completePayment($payment);
            }
        } elseif (AcquiringHelper::isTypeSberbank()) {
            $this->registerHoldByBinding($order);
        }

        return $response;
    }

    /**
     * @throws RegisterPreAuthException
     */
    private function makeSBPPayment(Order $order): GatewayResponseInterface
    {
        $amount = OnlinePaymentHelper::getNeededPaymentAmount($order);

        $amount = $this->validateAmount($amount);

        /** @var OnlinePayment $payment */
        $payment = OnlinePayment::query()
            ->create([
                'amount'        => $amount
            ]);

        $order->payments()->attach($payment->id, ['amount' => $amount]);

        $response = $payment->registerSBPPayment($amount, $order);

        if ($response->isError()) {
            Log::channel('payment')->error(
                sprintf(
                    '%s. Заказ [%s]. Ошибка - %s',
                    'Сообщение об ошибке от эквайринга при попытке оплаты через Систему Быстрых Платежей',
                    $order->id,
                    $response->getErrorMessage(),
                )
            );

            throw new RegisterPreAuthException($response->getErrorMessage());
        }

        return $response;
    }

    /**
     * @throws RegisterPreAuthException
     */
    private function makeSberbankOnlinePayment(
        Order $order
    ): GatewayResponseInterface {
        $amount = OnlinePaymentHelper::getNeededPaymentAmount($order);

        $amount = $this->validateAmount($amount);

        /** @var OnlinePayment $payment */
        $payment = OnlinePayment::query()
            ->create([
                'amount'        => $amount
            ]);

        $order->payments()->attach($payment->id, ['amount' => $amount]);

        $response = $payment->registerSberbankOnlinePayment($amount, $order);

        if ($response->isError()) {
            Log::channel('payment')->error(
                sprintf(
                    '%s. Заказ [%s]. Ошибка - %s',
                    'Сообщение об ошибке от эквайринга при попытке оплаты через Sberbank Online',
                    $order->id,
                    $response->getErrorMessage(),
                )
            );

            throw new RegisterPreAuthException($response->getErrorMessage());
        }

        return $response;
    }

    /**
     * @throws RegisterPreAuthException
     */
    private function validateBinding(Order $order): OnlinePaymentBinding
    {
        $binding = $order->binding;

        if (!$order->binding) {
            Log::channel('payment')->error(
                sprintf(
                    '%s. Заказ [%s]',
                    'Ошибка при создании оплаты заказа. У заказа отсутствует связка!',
                    $order->id,
                )
            );

            throw new RegisterPreAuthException(
                sprintf(
                    '%s. Заказ [%s]',
                    'Ошибка при создании оплаты заказа. У заказа отсутствует связка!',
                    $order->id,
                ));
        }

        return $binding;
    }

    /**
     * @throws RegisterPreAuthException
     */
    private function validateAmount(float $amount): float
    {
        if ($amount <= 0) {
            $message = __('messages.payment_amount_invalid', ['amount' => $amount]);

            throw new RegisterPreAuthException($message);
        }

        // Минимальная сумма для холдирования 1 рубль
        return max($amount, 1);
    }

    /**
     * Выполнение списания средств для онлайн оплат заказа, которые находятся в статусе hold
     *
     * @param Order $order
     * @return void
     * @throws RegisterPaymentDoException
     */
    private function makeDepositDo(Order $order): void
    {
        $heldPayments = $order->getHeldOnlinePayments();

        foreach ($heldPayments as $payment) {
            /** @var OnlinePayment $payment */

            $depositItems = [];

            $neededDepositAmount = OnlinePaymentHelper::getNeededDepositAmount($order);

            if ($neededDepositAmount < $payment->amount) {
                Log::channel('payment')->info(
                    sprintf(
                        '%s. Заказ - [%s] orderTotal [%s]. paymentTotal - %s',
                        'Сумма для списывания по заказу, в случае уменьшения захолдированной',
                        $order->id,
                        $neededDepositAmount,
                        $payment->amount,
                    )
                );

                //$depositItems = $this->getDepositItems($order);

                $amount = round($neededDepositAmount, 2);
            } else {
                $amount = round($payment->amount, 2);
            }

            $response = $payment->deposit($amount, $depositItems);

            if ($response->isError()) {
                Log::channel('payment')->info(
                    sprintf(
                        '%s. Заказ [%s]. Ошибка - %s',
                        'Сообщение об ошибке от эквайринга при попытке списания средств',
                        $order->id,
                        $response->getErrorMessage(),
                    )
                );

                throw new RegisterPaymentDoException($response->getErrorMessage());
            } else {
                $payment->getOrderStatus();
            }
        }
    }

    /**
     * Получение списка товаров в заказе для эквайринга
     *
     * @param Order $order
     * @return array
     */
    private function getDepositItems(Order $order): array
    {
        $items = [];

        foreach ($order->products as $orderItem) {
            $items[] = [
                'positionId'    => $orderItem->itemId,
                'name'          => $orderItem->title,
                'quantity'      => (int) $orderItem->count,
                'itemPrice'     => $orderItem->price_discount ?? $orderItem->price_buy,
                'itemCode'      => $orderItem->unit_1c_id,
            ];
        }

        return compact('items');
    }
}

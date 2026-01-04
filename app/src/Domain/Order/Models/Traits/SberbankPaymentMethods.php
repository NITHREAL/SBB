<?php

namespace Domain\Order\Models\Traits;

use Domain\Order\Enums\Payment\PaymentStatusEnum;
use Domain\Order\Helpers\OrderHelper;
use Domain\Order\Models\Order;
use Domain\Order\Models\Payment\OnlinePaymentBindingRequest;
use Domain\Order\Models\Payment\OnlinePaymentLog;
use Domain\User\Models\User;
use Illuminate\Support\Carbon;
use Infrastructure\Services\Acquiring\Facades\Acquiring;
use Infrastructure\Services\Acquiring\Responses\GatewayResponseInterface;
use Infrastructure\Services\Acquiring\Responses\SberbankResponse;

trait SberbankPaymentMethods
{
    protected string $paymentPreAuthOperationDescription = 'Абсолют. Регистрация заказа с использованием автоплатежа';
    protected string $paymentInitialOperationDescription = 'Абсолют. Первичный платеж';
    protected string $paymentWithSbpDescription = 'Абсолют. Регистрация заказа для оплаты через СБП';
    protected string $paymentLanguage = 'ru';

    public function registerInitPayment(float $amount, Order $order): GatewayResponseInterface
    {
        $user = $order->user;

        $orderSystemId = OrderHelper::makeSystemId($order->id);

        $params = [
            'orderNumber'       => $this->getOrderNumber($orderSystemId),
            'returnUrl'         => $this->getInitPaymentReturnUrl($orderSystemId),
            'amount'            => $amount,
            'description'       => $this->paymentInitialOperationDescription,
            'language'          => $this->paymentLanguage,
            'clientId'          => (string) $user->id,
            'phone'             => $user->phone,
            'expirationDate'    => $this->getExpirationDate(),
        ];

        $response = $this->do('registerPreAuth', $params);

        $this->update([
            'sber_order_id'     => $response->orderId,
            'status'            => $response->errorCode
                ? PaymentStatusEnum::error()->value
                : PaymentStatusEnum::registered()->value,
            'form_url'          => $response->formUrl,
        ]);

        return $response;
    }

    public function registerSBPPayment(float $amount, Order $order): GatewayResponseInterface
    {
        $user = $order->user;

        $orderSystemId = OrderHelper::makeSystemId($order->id);

        $params = [
            'orderNumber'       => $this->getOrderNumber($orderSystemId),
            'returnUrl'         => $this->getInitPaymentReturnUrl($orderSystemId),
            'amount'            => $amount,
            'description'       => $this->paymentWithSbpDescription,
            'language'          => $this->paymentLanguage,
            'clientId'          => (string) $user->id,
            'phone'             => $user->phone,
            'expirationDate'    => $this->getExpirationDate(),
            'jsonParams'        => [
                'qrType'        => 'DYNAMIC_QR_SBP',
                'sbp.scenario'  => 'C2B',
            ],
        ];

        $response = $this->do('registerSbp', $params);

        $this->update([
            'sber_order_id'     => $response->orderId,
            'status'            => $response->errorCode
                ? PaymentStatusEnum::error()->value
                : PaymentStatusEnum::registered()->value,
            'form_url'          => $response->formUrl,
        ]);

        return $response;
    }

    public function registerSberbankOnlinePayment(
        float $amount,
        Order $order
    ): GatewayResponseInterface {
        $user = $order->user;
        $orderSystemId = OrderHelper::makeSystemId($order->id);

        $params = [
            'orderNumber'      => $this->getOrderNumber($orderSystemId),
            'amount'           => $amount,
            'returnUrl'        => config('api.front_url'),
            'language'         => $this->paymentLanguage,
            'jsonParams'       => [
                'app2app'      => true,
            ],
            'phone'            => $user->phone,
        ];

        $registeredOrder = $this->do('registerPreAuth', $params);

        $this->update([
            'sber_order_id'     => $registeredOrder->orderId,
            'status'            => $registeredOrder->errorCode
                ? PaymentStatusEnum::error()->value
                : PaymentStatusEnum::registered()->value,
            'form_url'          => $registeredOrder->formUrl,
        ]);

        return $registeredOrder;
    }

    public function registerInitPaymentWithoutOrder(
        float $amount,
        OnlinePaymentBindingRequest $paymentBindingRequest,
    ): GatewayResponseInterface {
        $requestId = $paymentBindingRequest->id;
        $user = $paymentBindingRequest->user;

        $params = [
            'orderNumber'       => (string) $requestId,
            'returnUrl'         => $this->getInitPaymentReturnUrl($requestId),
            'amount'            => $amount,
            'description'       => 'Первичный платеж для добавления карты',
            'language'          => $this->paymentLanguage,
            'clientId'          => (string) $user->id,
            'phone'             => $user->phone,
            'expirationDate'    => $this->getExpirationDate(),
        ];

        $response = $this->do('registerPreAuth', $params);

        $this->update([
            'sber_order_id'     => $response->orderId,
            'status'            => $response->errorCode
                ? PaymentStatusEnum::error()->value
                : PaymentStatusEnum::registered()->value,
            'form_url'          => $response->formUrl,
        ]);

        return $response;
    }

    public function registerPreAuth(float $amount, Order $order, string $bindingId): GatewayResponseInterface
    {
        /** @var User $user */
        $user = $order->user;

        $orderSystemId = OrderHelper::makeSystemId($order->id);
        $expiration = Carbon::now()->addSeconds(config('api.acquiring_ttl'));

        $params = [
            'orderNumber'       => $this->getOrderNumber($orderSystemId),
            'returnUrl'         => config('api.front_url'),
            'amount'            => $amount,
            'description'       => $this->paymentPreAuthOperationDescription,
            'language'          => $this->paymentLanguage,
            'clientId'          => (string) $user->id,
            'phone'             => $user->phone,
            'bindingId'         => $bindingId,
            'expirationDate'    => $this->getExpirationDate($expiration),
            'features'          => 'AUTO_PAYMENT', // параметр, указывающий, что списание будет происходить при помощи автоплатежа
        ];

        /** @var GatewayResponseInterface $response */
        $response = $this->do('registerPreAuth', $params);

        $this->update([
            'sber_order_id' => $response->orderId,
            'status'        => $response->errorCode
                ? PaymentStatusEnum::error()->value
                : PaymentStatusEnum::registered()->value,
            'form_url'      => $response->formUrl,
            'expires_in'    => $expiration
        ]);

        return $response;
    }

    public function holdByBinding(Order $order): GatewayResponseInterface
    {
        $data = [
            'mdOrder'   => $this->sber_order_id,
            'bindingId' => $order->binding->acquiring_binding_id,
            'ip'        => $order->payer_ip,
        ];

        /** @var SberbankResponse $response */
        $response = $this->do('registerPreAuthAuto', $data);

        $this->update([
            'status'        => $response->errorCode
                ? PaymentStatusEnum::error()->value
                : PaymentStatusEnum::hold()->value,
            'payed'         => $response->payed(),
        ]);

        return $response;
    }

    public function deposit(float $amount = 0, array $depositItems = []): GatewayResponseInterface
    {
        $data = [
            'orderId'   => $this->sber_order_id,
            'amount'    => $amount,
        ];

        // Если финальная сумма списания меньше чем та, которая была захолдирована,
        // необходимо передавать товарные позиции корзины
        if (count($depositItems)) {
            $data['depositItems'] = $depositItems;
        }

        return $this->do('deposit', $data);
    }

    public function refund(float $amount = 0): GatewayResponseInterface
    {
        return $this->do(
            'refund',
            [
                'orderId'   => $this->sber_order_id,
                'amount'    => $amount,
            ]
        );
    }

    public function reverse(): GatewayResponseInterface
    {
        $response = $this->do(
            'reverse',
            [
                'orderId' => $this->sber_order_id,
            ]
        );

        if (empty($response->errorCode)) {
            $this->update([
                'status' => PaymentStatusEnum::reverse()->value
            ]);
        }

        return $response;
    }

    public function decline(): GatewayResponseInterface
    {
        return $this->do(
            'decline',
            ['orderId' => $this->sber_order_id]
        );
    }

    public function getOrderStatus(): GatewayResponseInterface
    {
        $status = $this->do(
            'getOrderStatus',
            ['orderId' => $this->sber_order_id]
        );

        $this->update([
            'payed'     => $status->payed(),
            'status'    => $status->getPreparedPaymentStatus()
        ]);

        return $status;
    }

    private function do(string $method, array $params = [])
    {
        if (isset($params['amount']) && $params['amount']) {
            $params['amount'] = $this->prepareAmountParam($params['amount']);
        }

        /** @var OnlinePaymentLog $log */
        $log = OnlinePaymentLog::create([
            'online_payment_id' => $this->id,
            'method'            => $method,
            'request'           => $params
        ]);

        $response = Acquiring::$method($params);

        $log->update([
            'error_message' => $response->errorMessage,
            'error_code'    => $response->errorCode,
            'response'      => $response->toArray()
        ]);

        return $response;
    }


    private function getOrderNumber(string $orderSystemId): string
    {
        return sprintf('%s_%s', $orderSystemId, Carbon::now()->timestamp);
    }

    private function getInitPaymentReturnUrl(string $orderSystemId): string
    {
        $frontUrl = config('api.front_url');

        return sprintf('%s%s%s', $frontUrl, '/basket?orderId=', $orderSystemId);
    }

    private function getExpirationDate(Carbon $expiration = null): string
    {
        if (empty($expiration)) {
            $acquiringTtl = config('api.acquiring_ttl');
            $expiration = Carbon::now()->addSeconds($acquiringTtl);
        }

        return sprintf('%sT%s', $expiration->format('Y-m-d'), $expiration->format('H:i:s'));
    }

    private function prepareAmountParam(float $amount): int
    {
        return (int)(round($amount, 2) * 100);
    }
}

<?php

namespace Domain\Order\Models\Traits;

use Domain\Order\Enums\Payment\PaymentStatusEnum;
use Domain\Order\Enums\Payment\PaymentTypeEnum;
use Domain\Order\Helpers\OrderHelper;
use Domain\Order\Helpers\Payment\OnlinePaymentHelper;
use Domain\Order\Models\Order;
use Domain\Order\Models\Payment\OnlinePaymentBindingRequest;
use Domain\Order\Models\Payment\OnlinePaymentLog;
use Illuminate\Support\Facades\Log;
use Infrastructure\Services\Acquiring\Facades\Acquiring;
use Infrastructure\Services\Acquiring\Responses\GatewayResponseInterface;
use Infrastructure\Services\Acquiring\Responses\YookassaResponse;

trait YookassaPaymentMethods
{
    private string $paymentMethodType = 'bank_card';
    private string $type = 'bindCard';

    private string $currency = 'RUB';

    private string $confirmationTypeRedirect = 'redirect';

    private string $confirmationTypeExternal = 'external';

    public function registerInitPayment(float $amount, Order $order): GatewayResponseInterface
    {
        $orderSystemId = OrderHelper::makeSystemId($order->id);

        $params = [
            'amount'                => [
                'value'     => $amount,
                'currency'  => $this->currency,
            ],
            'capture'               => false,
            'confirmation'          => [
                'type'          => $this->confirmationTypeRedirect,
                'return_url'    => $this->getRedirectUrl($order),
            ],
            'description'           => $this->getPaymentDescription($orderSystemId),
            'metadata'              => [
                'order_id'      => $orderSystemId,
                'order_uuid'    => $order->uuid,
            ],
            'save_payment_method'   => true,
        ];

        /** @var GatewayResponseInterface $response */
        $response = $this->do('registerPreAuth', $params);

        $this->update([
            'sber_order_id' => $response->id,
            'status'        => $response->isError()
                ? PaymentStatusEnum::error()->value
                : PaymentStatusEnum::registered()->value,
            'form_url'      => $response->getFormUrl(),
            'expires_in'    => OnlinePaymentHelper::getPaymentExpiresAt(),
        ]);

        return $response;
    }

    public function registerInitPaymentWithoutOrder(
        float $amount,
        OnlinePaymentBindingRequest $paymentBindingRequest
    ): GatewayResponseInterface {
        $params = [
            'amount'                => [
                'value'     => $amount,
                'currency'  => $this->currency,
            ],
            'capture'               => false,
            'confirmation'          => [
                'type'          => $this->confirmationTypeRedirect,
                'return_url'    => $this->getRedirectUrlWithoutOrder(),
            ],
            'description'           => 'Первичный платеж для привязки карты',
            'metadata'              => [
                'online_payment_id'      => $paymentBindingRequest->id,
                'type'    => $this->type,
            ],
            'save_payment_method'   => true,
        ];

        /** @var GatewayResponseInterface $response */
        $response = $this->do('registerPreAuth', $params);

        $this->update([
            'sber_order_id' => $response->id,
            'status'        => $response->isError()
                ? PaymentStatusEnum::error()->value
                : PaymentStatusEnum::registered()->value,
            'form_url'      => $response->getFormUrl(),
            'expires_in'    => OnlinePaymentHelper::getPaymentExpiresAt(),
        ]);

        return $response;
    }

    public function registerPreAuth(float $amount, Order $order, string $bindingId): GatewayResponseInterface
    {
        $orderSystemId = OrderHelper::makeSystemId($order->id);

        $params = [
            'amount'                => [
                'value'     => $amount,
                'currency'  => $this->currency,
            ],
            'capture'               => false,
            'payment_method_id'     => $bindingId,
            'description'           => $this->getPaymentDescription($orderSystemId),
            'metadata'              => [
                'order_id'      => $orderSystemId,
                'order_uuid'    => $order->uuid,
            ],
        ];

        /** @var YooKassaResponse $response */
        $response = $this->do('registerPreAuth', $params);

        $this->update([
            'sber_order_id' => $response->id,
            'status'        => !empty($response->cancellation_details)
                ? PaymentStatusEnum::error()->value
                : PaymentStatusEnum::hold()->value,
            'form_url'      => $response->getFormUrl(),
            'expires_in'    => OnlinePaymentHelper::getPaymentExpiresAt(),
        ]);

        return $response;
    }

    public function registerSBPPayment($amount, Order $order): GatewayResponseInterface
    {
        $orderSystemId = OrderHelper::makeSystemId($order->id);

        $params = [
            'amount' => [
                'value' => $amount,
                'currency'  => $this->currency,
            ],
            'payment_method_data' => [
                'type' => PaymentTypeEnum::sbp()->value,
            ],
            'capture' => true,
            'confirmation' => [
                'type' => $this->confirmationTypeRedirect,
                'return_url' => $this->getRedirectUrl($order),
            ],
            'description' => $this->getPaymentDescription($orderSystemId),
            'metadata' => [
                'order_id' => $orderSystemId,
                'order_uuid' => $order->uuid,
            ],
        ];

        /** @var YooKassaResponse $response */
        $response = $this->do('registerPreAuth', $params);

        $this->update([
            'sber_order_id' => $response->id,
            'status' => !empty($response->cancellation_details)
                ? PaymentStatusEnum::error()->value
                : PaymentStatusEnum::deposit()->value,
            'form_url'      => $response->getFormUrl(),
            'expires_in'    => OnlinePaymentHelper::getPaymentExpiresAt(),
        ]);

        return $response;
    }

    public function deposit(float $amount = 0): GatewayResponseInterface
    {
        $data['payment_id'] = $this->sber_order_id;

        if ($amount > 0) {
            $data['amount'] = [
                'value'     => $amount,
                'currency'  => $this->currency,
            ];
        }

        return $this->do('deposit', $data);
    }

    public function refund(float $amount = 0): GatewayResponseInterface
    {
        $data = [
            'payment_id'  => $this->sber_order_id,
            'amount'      => [
                'value'    => $amount,
                'currency'  => $this->currency,
            ],
        ];

        return $this->do('refund', $data);
    }

    public function reverse(): GatewayResponseInterface
    {
        Log::channel('payment')->info(
            sprintf(
                '%s. Идентификатор платежа в эквайринге - %s',
                'Возврат платежа',
                $this->sber_order_id,
            )
        );

        $data['payment_id'] = $this->sber_order_id;

        /** @var GatewayResponseInterface $response */
        $response = $this->do('reverse', $data);

        if (!$response->isError()) {
            $this->update([
                'status' => PaymentStatusEnum::reverse()->value
            ]);
        } else {
            Log::channel('payment')->info(
                sprintf(
                    '%s. Заказ [%s]. Ошибка - %s',
                    'Сообщение об ошибке от эквайринга при попытке отмены оплаты',
                    $this->order_id,
                    $response->getErrorMessage(),
                )
            );
        }

        return $response;
    }

    public function decline(): GatewayResponseInterface
    {
        $data['payment_id'] = $this->sber_order_id;

        return $this->do('decline', $data);
    }

    private function do(string $method, array $params = [])
    {
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

    public function getOrderStatus(): GatewayResponseInterface
    {
        /** @var GatewayResponseInterface $response */
        $response = $this->do(
            'getOrderStatus',
            [
                'payment_id' => $this->sber_order_id
            ]
        );

        $this->update([
            'payed'     => $response->payed(),
            'status'    => $response->getPreparedPaymentStatus(),
        ]);

        return $response;
    }

    private function getRedirectUrl(Order $order): string
    {
        $frontUrl = config('api.front_url');

        return sprintf('%s/%s%s', $frontUrl, 'basket?orderId=', OrderHelper::makeSystemId($order->id));
    }

    private function getRedirectUrlWithoutOrder(): string
    {
        $frontUrl = config('api.front_url');

        return sprintf('%s/%s', $frontUrl, 'profile/payment-cards');
    }

    private function getPaymentDescription(string $orderSystemId): string
    {
        return sprintf('Заказ №%s', $orderSystemId);
    }
}

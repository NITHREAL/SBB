<?php

namespace Domain\Order\Services\Payment;

use Domain\Order\Helpers\Payment\OnlinePaymentHelper;
use Domain\Order\Models\Payment\OnlinePayment;
use Domain\Order\Models\Payment\OnlinePaymentBindingRequest;
use Domain\Order\Services\Payment\Exceptions\RegisterPaymentDoException;
use Domain\User\Models\User;
use Illuminate\Support\Facades\Log;
use Infrastructure\Services\Acquiring\Responses\GatewayResponseInterface;

class OnlinePaymentBindingRequestService
{
    private float $initPaymentAmount;

    public function __construct(
    ) {
        $this->initPaymentAmount = round(config('api.payment_online.init_amount'), 2);
    }

    /**
     * @throws RegisterPaymentDoException
     */
    public function processPaymentBindingRequest(User $user): array
    {
        $bindingRequest = $this->getBindingRequestInstance($user);

        $response = $this->makeInitPayment($bindingRequest);

        return [
            'formUrl'   => $response->getFormUrl(),
        ];
    }

    /**
     * @throws RegisterPaymentDoException
     */
    private function makeInitPayment(OnlinePaymentBindingRequest $paymentBindingRequest): GatewayResponseInterface
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

        $paymentBindingRequest->payment()->associate($payment);
        $paymentBindingRequest->save();

        $response = $payment->registerInitPaymentWithoutOrder($amount, $paymentBindingRequest);

        if ($response->isError()) {
            Log::channel('payment')->info(
                sprintf(
                    '%s. Заказ [%s]. Ошибка - %s',
                    'Сообщение об ошибке от эквайринга при попытке регистрации первичного платежа',
                    $paymentBindingRequest->id,
                    $response->getErrorMessage(),
                )
            );

            throw new RegisterPaymentDoException($response->getErrorMessage());
        }

        return $response;
    }

    private function getBindingRequestInstance(User $user): object
    {
        $bindingRequest = new OnlinePaymentBindingRequest();

        $bindingRequest->fill([
            'amount'        => $this->initPaymentAmount,
            'expires_at'    => OnlinePaymentHelper::getPaymentExpiresAt(),
        ]);

        $bindingRequest->user()->associate($user);

        return $bindingRequest;
    }
}

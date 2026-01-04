<?php

namespace Domain\Order\Jobs\Payment;

use Domain\Order\Models\Payment\OnlinePayment;
use Domain\Order\Models\Payment\OnlinePaymentBindingRequest;
use Domain\Order\Services\Payment\Exceptions\PaymentException;
use Domain\Order\Services\Payment\InitPaymentService;
use Illuminate\Support\Facades\Log;
use Infrastructure\Jobs\BaseOrderJob;

class BindingRequestInitPaymentJob extends BaseOrderJob
{
    public function __construct(
        private readonly OnlinePaymentBindingRequest $bindingRequest,
        private readonly OnlinePayment $initPayment,
    ) {
    }

    public function handle(InitPaymentService $initPaymentService): void
    {
        try {
            Log::channel('payment')->info(
                sprintf(
                    '%s. binding_request_id [%s]. payment_id [%s]',
                    'OnlinePaymentBindingAddJob initialized',
                    $this->bindingRequest->id,
                    $this->initPayment->id
                )
            );

            $initPaymentService->processBindingRequestInitPayment($this->initPayment, $this->bindingRequest);
        } catch (PaymentException $exception) {
            Log::channel('payment')->error(
                sprintf(
                    '%s. %s. Пользователь - [%s]',
                    'Ошибка во время обработки первичного платежа для добавления карты',
                    $exception->getMessage(),
                    $this->bindingRequest->user_id,
                )
            );
        }
    }
}

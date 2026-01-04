<?php

namespace Domain\Order\Services\Sberbank;

use Domain\Order\DTO\Sberbank\SberbankNotificationDTO;
use Domain\Order\Helpers\Sberbank\SberbankNotificationHelper;
use Illuminate\Support\Facades\Log;

readonly class SberbankNotificationService
{
    public function __construct(
        private SberbankHeldPaymentService $sberbankHeldPaymentService,
        private SberbankDepositedPaymentService $sberbankDepositedPaymentService,
    ) {
    }

    public function processSberbankNotification(SberbankNotificationDTO $sberbankNotificationDTO): void
    {
        Log::channel('sberbank')->info(
            sprintf(
                '%s. ID платежа [%s]. Статус платежа  - [%s]. ID сущности - [%s]',
                'Вэбхук для платежа инициализирован',
                $sberbankNotificationDTO->getMdOrder(),
                $sberbankNotificationDTO->getOperation(),
                $sberbankNotificationDTO->getOrderNumber(),
            ),
        );

        if ($sberbankNotificationDTO->getStatus()) {
            if (SberbankNotificationHelper::isPaymentHeld($sberbankNotificationDTO->getOperation())) {
                $this->sberbankHeldPaymentService->processHeldPayment($sberbankNotificationDTO);
            } elseif (SberbankNotificationHelper::isPaymentDeposited($sberbankNotificationDTO->getOperation())) {
                $this->sberbankDepositedPaymentService->processDepositedPayment($sberbankNotificationDTO);
            }
        }


        Log::channel('sberbank')->info("Отработал вэбхук для платежа [{$sberbankNotificationDTO->getMdOrder()}]");
    }
}

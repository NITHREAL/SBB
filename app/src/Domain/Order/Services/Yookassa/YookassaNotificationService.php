<?php

namespace Domain\Order\Services\Yookassa;

use Domain\Order\DTO\Yookassa\YookassaNotificationDTO;
use Domain\Order\Helpers\Yookassa\YookassaNotificationHelper;
use Illuminate\Support\Facades\Log;

class YookassaNotificationService
{
    public function __construct(
        private YookassaHeldPaymentService $heldPaymentService,
    ) {
    }

    public function processYookassaNotification(YookassaNotificationDTO $yookassasNotificationDTO): void
    {
        Log::channel('yookassa')->info(
            sprintf(
                '%s. ID платежа [%s]. Статус платежа  - [%s]. Сумма - [%s]. Event - [%s]',
                'Вэбхук для платежа инициализирован',
                $yookassasNotificationDTO->getPayment()->getId(),
                $yookassasNotificationDTO->getPayment()->getStatus(),
                $yookassasNotificationDTO->getPayment()->getAmount(),
                $yookassasNotificationDTO->getEvent(),
            )
        );

        if (YookassaNotificationHelper::isPaymentHeld($yookassasNotificationDTO->getEvent())) {
            $this->heldPaymentService->processHeldPayment($yookassasNotificationDTO->getPayment());

            Log::channel('yookassa')->info("Отработал вэбхук для платежа [{$yookassasNotificationDTO->getPayment()->getId()}]");
        }
    }
}

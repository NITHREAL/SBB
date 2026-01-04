<?php

namespace Domain\Order\Services\Sbermarket;

use Domain\Order\DTO\Sbermarket\UpdateSbermarketPaymentDTO;
use Domain\Order\Enums\OrderStatusEnum;
use Domain\Order\Enums\Payment\PaymentStatusEnum;
use Domain\Order\Enums\Sbermarket\OrderSbermarketOperationEnum;
use Domain\Order\Helpers\Payment\OnlinePaymentHelper;
use Domain\Order\Models\Order;
use Domain\Order\Models\Payment\OnlinePayment;
use Domain\Order\Services\Payment\Exceptions\PaymentException;

class SbermarketPaymentService
{
    public function createSbermarketPayment(float $amount): OnlinePayment
    {
        $payment = new OnlinePayment();

        $payment->fill([
            'status'    => PaymentStatusEnum::deposit()->value,
            'payed'     => true,
            'amount'    => $amount,
            'value'     => $amount,
        ]);

        return $payment;
    }

    public function updateSbermarketPayment(UpdateSbermarketPaymentDTO $sbermarketPaymentDTO): bool
    {
        $result = false;

        $hashHmac = $this->getHashHmacByParams($sbermarketPaymentDTO->getParams());

        if (
            $hashHmac === $sbermarketPaymentDTO->getChecksum()
            && $sbermarketPaymentDTO->getOperation() === OrderSbermarketOperationEnum::approved()->value
            && $sbermarketPaymentDTO->getStatus() === 1
        ) {
            $payment = $this->updatePayment($sbermarketPaymentDTO->getMdOrder());

            $result = !empty($payment);
        }

        return $result;
    }

    /**
     * @throws PaymentException
     */
    public function checkSbermarketPaymentStatus(string $uuid, string $sberOrderId): object
    {
        /** @var OnlinePayment $payment */
        $payment = OnlinePayment::query()
            ->whereSberId($sberOrderId)
            ->whereHas('orders', function ($query) use ($uuid) {
                return $query->where('uuid', $uuid);
            })
            ->firstOrFail();

        if (!$payment->payed) {
            $paymentStatus = $payment->getOrderStatus();

            if ($paymentStatus->errorCode) {
                throw new PaymentException($paymentStatus->errorMessage, 500);
            }

            if ($paymentStatus->payed()) {
                OnlinePaymentHelper::completePayment($payment);
            }
        }

        return $payment;
    }

    public function checkSbermarketOrderPaymentNeed(Order $order): void
    {
        $additionalAmount = OnlinePaymentHelper::getAdditionalPaymentAmount($order);

        if ($additionalAmount > 0 && $order->sm_original_order_id) {
            $payment = $this->createSbermarketPayment($additionalAmount);

            $order->payments()->attach($payment->id, ['amount' => $additionalAmount]);

            $order->status = OrderStatusEnum::payed()->value;
            $order->need_exchange = true;
            $order->save();
        }
    }

    private function updatePayment(string $sberOrderId): ?object
    {
        /** @var OnlinePayment $payment */
        $payment = OnlinePayment::query()->whereSberId($sberOrderId)->first();

        if ($payment) {
            $payment = OnlinePaymentHelper::completePayment($payment);

            $payment->update([
                'payed'     => true,
                'status'    => PaymentStatusEnum::hold()->value,
            ]);
        }

        return $payment;
    }

    private function getHashHmacByParams(array $params): string
    {
        $secretKey = config('sberbank.callback_hash');

        ksort($params);

        $str = '';

        foreach ($params as $key => $value) {
            $str .= "$key;$value;";
        }

        $hmac = hash_hmac('sha256', $str, $secretKey);

        return mb_strtoupper($hmac);
    }
}

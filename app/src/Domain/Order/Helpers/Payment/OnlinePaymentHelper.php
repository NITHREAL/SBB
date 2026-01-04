<?php

namespace Domain\Order\Helpers\Payment;

use Domain\Order\Enums\OrderStatusEnum;
use Domain\Order\Helpers\OrderHelper;
use Domain\Order\Models\Order;
use Domain\Order\Models\Payment\OnlinePayment;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Infrastructure\Services\Acquiring\Enums\AcquiringTypeEnum;

class OnlinePaymentHelper
{
    public static function paymentByOnlineAvailable(string $deliveryDate): bool
    {
        $now = Carbon::now();
        $date = Carbon::createFromFormat('Y-m-d', $deliveryDate);

        return $date->diffInDays($now) < 10;
    }

    public static function getCurrentAcquiringType(): string
    {
        $acquiringTypeConfig = Arr::get(config('api.acquiring'), 'type');

        return match ($acquiringTypeConfig) {
            'sberbank'  => AcquiringTypeEnum::sberbank()->value,
            'yookassa'  => AcquiringTypeEnum::yookassa()->value,
            default     => AcquiringTypeEnum::yookassa()->value,
        };
    }

    public static function completePayment(OnlinePayment $payment): OnlinePayment
    {
        $order = $payment->orders()
            ->whereWaitingPayment()
            ->first();

        if ($order) {
            $orderTotal = OrderHelper::getTotal($order, true, true);

            /** @var Order $order */
            if ($orderTotal <= self::getPayedAmount($order)) {
                $status = $order->status === OrderStatusEnum::surcharge()->value
                    ? OrderStatusEnum::collected()->value
                    : OrderStatusEnum::payed()->value;

                $order->update([
                    'status'        => $status,
                    'need_exchange' => true
                ]);
            }
        }

        return $payment;
    }

    public static function getHeldAmount(Order $order): float
    {
        $payments = $order->getHeldOnlinePayments();

        $amount = $payments->count() > 0
            ? $payments->sum(fn(OnlinePayment $item) => $item->amount)
            : 0;

        return round($amount, 2);

    }

    public static function getPayedAmount(Order $order): float
    {
        $payments = $order->getPayedOnlinePayments();

        $amount = $payments->count() > 0
            ? $payments->sum(fn(OnlinePayment $item) => $item->amount)
            : 0;

        return round($amount, 2);
    }

    public static function getDepositedAmount(Order $order): float
    {
        $payments = $order->getDepositedOnlinePayments();

        $amount = $payments->count() > 0
            ? $payments->sum(fn(OnlinePayment $item) => $item->amount)
            : 0;

        return round($amount, 2);
    }

    public static function getNeededPaymentAmount(Order $order): float
    {
        $result = 0;

        $amountOnHold = self::getPayedAmount($order);
        $amountAfterCollecting = OrderHelper::getTotal($order, true, true);

        Log::channel('payment')->info(
            sprintf(
                '%s. Заказ - [%s] amountOnHold [%s]. amountAfterCollecting - %s',
                'Расчёт суммы холдирование оплаты по заказу',
                $order->id,
                $amountOnHold,
                $amountAfterCollecting
            )
        );

        if ($amountAfterCollecting > $amountOnHold) {
            $result = $amountAfterCollecting - $amountOnHold;
        }

        return round($result, 2);
    }

    public static function getNeededDepositAmount(Order $order): float
    {
        $result = 0;

        $amountDeposited = self::getDepositedAmount($order);
        $amountAfterCollecting = OrderHelper::getTotal($order, true, true, true);

        Log::channel('payment')->info(
            sprintf(
                '%s. Заказ - [%s] amountOnHold [%s]. amountAfterCollecting - %s',
                'Расчёт суммы холдирование оплаты по заказу',
                $order->id,
                $amountDeposited,
                $amountAfterCollecting
            )
        );

        if ($amountAfterCollecting > $amountDeposited) {
            $result = $amountAfterCollecting - $amountDeposited;
        }

        return round($result, 2);
    }

    public static function getPaymentExpiresAt(): string
    {
        $acquiringTtl = config('api.acquiring_ttl');

        return Carbon::now()->addSeconds($acquiringTtl);
    }
}

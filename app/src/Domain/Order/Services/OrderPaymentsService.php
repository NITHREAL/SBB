<?php

namespace Domain\Order\Services;

use Domain\Order\Helpers\OrderHelper;
use Domain\Order\Helpers\Payment\OnlinePaymentHelper;
use Domain\Order\Models\Order;

class OrderPaymentsService
{
    public static function checkPayments(Order $order): array
    {
        foreach ($order->payments as $payment) {
            $paymentStatusResponse = $payment->getOrderStatus();

            if ($paymentStatusResponse?->payed()) {
                OnlinePaymentHelper::completePayment($payment);
            }
        }

        return [
            'total' => OrderHelper::getTotal($order, true, true),
            'payed' => OnlinePaymentHelper::getPayedAmount($order),
        ];
    }
}

<?php

namespace App\Orchid\Screens\Shop\Order\Payment;

use App\Orchid\Layouts\Shop\Order\Payment\PaymentLogListLayout;
use Domain\Order\Models\Order;
use Domain\Order\Models\Payment\OnlinePayment;
use Orchid\Screen\Screen;

class PaymentLogListScreen extends Screen
{
    public $name = 'Логи платежа №';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Order $order, OnlinePayment $payment): array
    {
       $payment->load('logs');

       $this->name .= ' ' . $payment->id;

        return [
            'order' => $order,
            'logs' => $payment->logs
        ];
    }

    /**
     * Views.
     *
     * @return string[]
     */
    public function layout(): array
    {
        return [
            PaymentLogListLayout::class
        ];
    }
}

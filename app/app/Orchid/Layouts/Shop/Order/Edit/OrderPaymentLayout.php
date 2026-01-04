<?php

namespace App\Orchid\Layouts\Shop\Order\Edit;

use App\Orchid\Helpers\TD\DateTime;
use App\Orchid\Helpers\TD\ID;
use Domain\Order\Enums\Payment\PaymentStatusEnum;
use Domain\Order\Models\Payment\OnlinePayment;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class OrderPaymentLayout extends Table
{
    protected $target = 'order.payments';

    protected function columns(): array
    {
        $order = $this->query['order'];

        return [
            ID::make(),
            TD::make('Логи')->render(function (OnlinePayment $payment) use ($order) {
                return Link::make('Показать лог')
                    ->route('platform.orders.payment', [
                        'order' => $order->id,
                        'payment' => $payment->id
                    ]);
            }),
            TD::make('sber_order_id', __('admin.payment.sber_order_id')),
            TD::make('status', __('admin.payment.status'))
                ->render(function (OnlinePayment $payment) {
                    $enum = PaymentStatusEnum::toArray();

                    return ($enum[$payment->status] ?? '') . " ({$payment->status})";
                }),
            TD::make('amount', __('admin.payment.amount')),

            DateTime::createdAt(),
        ];
    }
}


<?php

namespace App\Orchid\Layouts\Shop\Order;

use App\Orchid\Core\Actions;
use App\Orchid\Helpers\TD\DateTime;
use App\Orchid\Helpers\TD\ID;
use Domain\Order\Enums\Delivery\DeliveryTypeEnum;
use Domain\Order\Enums\OrderSourceEnum;
use Domain\Order\Enums\OrderStatusEnum;
use Domain\Order\Enums\Payment\PaymentTypeEnum;
use Domain\Order\Models\Order;
use Domain\Order\Models\Payment\PaymentType;
use Domain\UtmLabel\Enums\UtmLabelEnum;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class OrderListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'orders';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            ID::make()
                ->sort(),
            TD::make('user.full_name', __('admin.user.full_name'))
                ->sort()
                ->render(fn (Order $order) => $order->contacts?->name ?? $order->user->full_name ?? 'Anonymous')
                ->width(130),
            TD::make('total_price', __('admin.order.total'))
                ->sort(),
            TD::make('status', __('admin.order.status'))
                ->sort()
                ->enum(OrderStatusEnum::class),
            TD::make('delivery_type', __('admin.order.delivery_type'))
                ->sort()
                ->render(function (Order $order) {
                    $deliveryType = $order->delivery_type;

                    $returnLabel = DeliveryTypeEnum::$deliveryType()?->label;

                    if ($order->store?->is_dark_store && !$order->completed) {
                        $returnLabel .= '<br/><div style="color:red;">Требуется выбор магазина.</div>';
                    }

                    return $returnLabel;
                }),
            TD::make('delivery_sub_type', __('admin.order.delivery_sub_type'))
                ->sort()
                ->render(function (Order $order) {
                    return $order->preparedDeliverySubType;
                }),
            TD::make('payment_type', __('admin.order.payment_type'))
                ->sort()
                ->render(function (Order $order) {
                    return PaymentTypeEnum::from($order->payment_type)->label;
                }),

            TD::make('request_from', __('admin.utm.utm_source'))
                ->sort()
                ->render(function (Order $order) {
                    $orderSources = OrderSourceEnum::toArray();
                    return  $orderSources[$order->request_from] ?? 'Неизвестный источник';
                }),

            TD::make('contacts.phone', __('admin.order.contacts.phone'))
                ->sort(),

            TD::make('receive_date', __('admin.order.receive_date_get'))
                ->sort()
                ->render(function ($model) {
                    return \Carbon\Carbon::parse($model->receive_date)->format('d-m-Y');
                }),

            DateTime::createdAt()
                ->sort(),

            TD::make()->actions([
                new Actions\Edit('platform.orders.edit')
            ])
        ];
    }
}

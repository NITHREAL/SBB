<?php

namespace App\Orchid\Layouts\Shop\Order\Edit;

use Domain\Order\Enums\Delivery\DeliveryServiceEnum;
use Domain\Order\Enums\Delivery\DeliveryTypeEnum;
use Domain\Order\Models\Order;
use Domain\Order\Services\Delivery\DateServices\ReceiveInterval;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class OrderDeliveryLayout extends Rows
{
    protected $title = 'Доставка';

    protected function fields(): array
    {
        /** @var Order $order */
        $order = $this->query['order'];
        $completed = $order->isCompleted;

        $from = null;
        $to = null;

        if ($order->receive_interval && ReceiveInterval::validate($order->receive_interval)) {
            [$from, $to] = explode('_', $order->receive_interval);
        }
        $from = (int) $from;
        $to = (int) $to;

        $interval = [];
        for ($i = 0; $i<=24; $i++) {
            $interval[$i] =  sprintf("%'.02d:00", $i);
        }

        return [
            Select::make('order.delivery_type')
                ->options(DeliveryTypeEnum::toArray())
                ->title(__('admin.order.delivery_type'))
                ->required()
                ->disabled(),

            DateTimer::make('order.receive_date')
                ->title(__('admin.order.receive_date_get'))
                ->format('d-m-Y')
                ->disabled(),

            Group::make([
                Select::make('order.receive_interval.from')
                    ->title(__('admin.order.receive_interval') . ', От')
                    ->placeholder('От')
                    ->options($interval)
                    ->value($from)
                    ->disabled(),

                Select::make('order.receive_interval.to')
                    ->title('До')
                    ->placeholder('До')
                    ->options($interval)
                    ->value($to)
                    ->disabled(),
            ]),

            Label::make('order.store.city.title')
                ->title(__('admin.store.city'))
                ->canSee($order->exists),

            Input::make('order.contacts.address')
                ->title(__('admin.order.contacts.address'))
                ->disabled(),

            Group::make([
                Input::make('order.contacts.apartment')
                    ->title(__('admin.order.contacts.apartment'))
                    ->type('number')
                    ->min(1)
                    ->disabled(),

                Input::make('order.contacts.floor')
                    ->title(__('admin.order.contacts.floor'))
                    ->type('number')
                    ->min(1)
                    ->disabled(),

                Input::make('order.contacts.entrance')
                    ->title(__('admin.order.contacts.entrance'))
                    ->type('number')
                    ->min(1)
                    ->disabled(),
                Input::make('order.contacts.intercom')
                    ->title(__('admin.order.contacts.intercom'))
                    ->disabled(),
            ]),
        ];
    }
}

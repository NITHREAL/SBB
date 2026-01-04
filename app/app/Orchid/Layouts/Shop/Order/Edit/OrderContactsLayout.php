<?php

namespace App\Orchid\Layouts\Shop\Order\Edit;

use Domain\Order\Models\Order;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class OrderContactsLayout extends Rows
{
    protected $title = 'Контакты';

    protected function fields(): array
    {
        /** @var Order $order */
        $order = $this->query['order'];
        $completed = $order->isCompleted;

        return [
            Input::make('order.contacts.name')
                ->title(__('admin.order.contacts.name'))
                ->readonly(),

            Input::make('order.contacts.phone')
                ->title(__('admin.order.contacts.phone'))
                ->mask('+7(999)999-99-99')
                ->type('tel')
                ->readonly(),

            Input::make('order.contacts.email')
                ->title(__('admin.order.contacts.email'))
                ->type('email')
                ->readonly(),
        ];
    }
}

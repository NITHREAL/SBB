<?php

namespace App\Orchid\Layouts\References\PaymentType;

use Domain\Order\Enums\Delivery\DeliveryTypeEnum;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class PaymentTypeRowLayout extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title;

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): array
    {
        return [
            CheckBox::make('payment.active')
                ->title(__('admin.active'))
                ->sendTrueOrFalse()
                ->horizontal(),

            Input::make('payment.title')
                ->title(__('admin.title'))
                ->horizontal(),

            Input::make('payment.code')
                ->title(__('admin.payment_type.code'))
                ->readonly()
                ->horizontal(),

            Input::make('payment.sort')
                ->title(__('admin.sort'))
                ->type('number')
                ->min(0)
                ->horizontal(),

            Select::make('payment.delivery_type')
                ->title(__('admin.payment_type.delivery_types'))
                ->horizontal()
                ->multiple()
                ->options(DeliveryTypeEnum::toArray())
        ];
    }
}

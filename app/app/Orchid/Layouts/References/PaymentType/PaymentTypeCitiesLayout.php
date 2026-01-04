<?php

namespace App\Orchid\Layouts\References\PaymentType;

use App\Orchid\Fields\Matrix;
use Domain\City\Models\City;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class PaymentTypeCitiesLayout extends Rows
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
        $payment = $this->query['payment'];

        $fields = [
            CheckBox::make('payment.for_all_cities')
                ->sendTrueOrFalse()
                ->title(__('admin.payment_type.for_all_cities'))
                ->horizontal()];

        if (!$payment->for_all_cities) {
            $fields[] = Matrix::make('payment.cities')
                ->columns([
                    __('admin.cities') => 'id',
                ])
                ->fields([
                    'id' => Select::make()
                        ->fromModel(City::class, 'title', 'id')
                        ->required(),
                ]);
        }

        return $fields;
    }
}

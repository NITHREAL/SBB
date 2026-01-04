<?php

namespace App\Orchid\Layouts\FarmerCounter;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class FarmerCounterEditLayout extends Rows
{
    /**
     * Views.
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('farmer_counter.title')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('admin.farmer_counter.title')),

            Input::make('farmer_counter.value')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('admin.farmer_counter.value')),
        ];
    }
}

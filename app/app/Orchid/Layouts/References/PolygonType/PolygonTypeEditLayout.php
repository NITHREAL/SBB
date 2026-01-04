<?php

namespace App\Orchid\Layouts\References\PolygonType;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class PolygonTypeEditLayout extends Rows
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
            Input::make('polygon_type.type')
                ->title(__('admin.polygon_type.type'))
                ->readonly()
                ->horizontal(),

            Input::make('polygon_type.delivery_type')
                ->title(__('admin.polygon_type.delivery_type'))
                ->readonly()
                ->horizontal(),

            Input::make('polygon_type.title')
                ->title(__('admin.title'))
                ->horizontal(),

            Input::make('polygon_type.description')
                ->title(__('admin.polygon_type.description'))
                ->horizontal(),

            Input::make('polygon_type.tooltip')
                ->title(__('admin.polygon_type.tooltip'))
                ->horizontal(),
        ];
    }
}

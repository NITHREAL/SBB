<?php

namespace App\Orchid\Layouts\References\PolygonType;

use App\Orchid\Core\Actions;
use App\Orchid\Helpers\TD\ID;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class PolygonTypeListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'polygon_types';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            ID::make()->sort(),
            TD::make('type', __('admin.polygon_type.type'))->sort(),
            TD::make('delivery_type', __('admin.polygon_type.delivery_type'))->sort(),
            TD::make('title', __('admin.title'))->sort(),
            TD::make('description', __('admin.polygon_type.description'))->defaultHidden()->sort(),
            TD::make('tooltip', __('admin.polygon_type.tooltip'))->defaultHidden()->sort(),
            TD::make()->actions([
                new Actions\Edit('platform.polygon_types.edit'),
            ])
        ];
    }
}

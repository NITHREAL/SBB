<?php

namespace App\Orchid\Layouts\Shop\ProductFilter;

use App\Orchid\Core\Actions;
use App\Orchid\Helpers\TD\Active;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ProductFilterListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'filters';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            Active::make(),

            TD::make('title', __('admin.title'))
                ->sort(),

            TD::make('sort', __('admin.sort'))
                ->width(120)
                ->sort()
                ->alignCenter(),

            TD::make()->actions([
                new Actions\Activate(),
                new Actions\Edit('platform.product-filter.edit'),
            ])
        ];
    }
}

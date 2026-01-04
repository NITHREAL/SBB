<?php

namespace App\Orchid\Layouts\FarmerCounter;

use App\Orchid\Core\Actions;
use App\Orchid\Helpers\TD\ID;
use App\Orchid\Helpers\TD\Sort;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class FarmerCounterListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'farmer_counter';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            ID::make(),
            TD::make('title', __('admin.farmer_counter.title')),
            TD::make('value', __('admin.farmer_counter.value')),
            Sort::make(),
            TD::make()->actions([
                new Actions\Edit('platform.farmer-counter.edit')
            ]),
        ];
    }
}

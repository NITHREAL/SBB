<?php

namespace App\Orchid\Layouts\Shop\Farmer;

use App\Orchid\Core\Actions;
use App\Orchid\Helpers\TD\Active;
use App\Orchid\Helpers\TD\DateTime;
use App\Orchid\Helpers\TD\ID;
use App\Orchid\Helpers\TD\Sort;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class FarmerListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'farmers';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            ID::make(),
            Active::make(),

            TD::make('name', __('admin.farmer.name'))
                ->sort(),

            Sort::make(),
            DateTime::createdAt(),

            TD::make()->actions([
                new Actions\Activate(),
                new Actions\Show('platform.farmers.show')
            ])
        ];
    }
}

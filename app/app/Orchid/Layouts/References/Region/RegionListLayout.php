<?php

namespace App\Orchid\Layouts\References\Region;

use App\Orchid\Core\Actions;
use App\Orchid\Helpers\TD\DateTime;
use App\Orchid\Helpers\TD\ID;
use App\Orchid\Helpers\TD\Sort;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class RegionListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'regions';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            ID::make()
                ->sort(),
            TD::make('title', __('admin.title'))
                ->sort(),
            Sort::make()
                ->sort(),
            TD::make()->actions([
                new Actions\Edit('platform.regions.edit'),
            ])
        ];
    }
}

<?php

namespace App\Orchid\Layouts\Shop\Group;

use App\Orchid\Actions\EnableMobile;
use App\Orchid\Actions\EnableSite;
use App\Orchid\Core\Actions;
use App\Orchid\Helpers\TD\Active;
use App\Orchid\Helpers\TD\ID;
use App\Orchid\Helpers\TD\Preview;
use App\Orchid\Helpers\TD\Sort;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class GroupListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'groups';

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
            Active::make()
                ->sort(),
            TD::make('title', __('admin.title'))
                ->sort(),
            TD::make('products_count', __('admin.group.product.count'))
                ->sort()
                ->width(100)
                ->alignCenter(),
            Sort::make()
                ->sort(),
            TD::make()->actions([
                new Actions\Activate(),
                new EnableMobile(),
                new Actions\Edit('platform.groups.edit'),
            ])
        ];
    }
}

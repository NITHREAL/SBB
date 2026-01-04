<?php

namespace App\Orchid\Layouts\Shop\Category;

use App\Orchid\Core\Actions;
use App\Orchid\Helpers\TD\Active;
use App\Orchid\Helpers\TD\ID;
use App\Orchid\Helpers\TD\Preview;
use App\Orchid\Helpers\TD\Sort;
use Domain\Product\Models\Category;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CategoryListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'categories';

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
                ->sort()
                ->render(function (Category $category) {
                    return Link::make($category->title)
                        ->route('platform.categories.show', $category);
                }),

            Sort::make()
                ->sort(),

            TD::make()->actions([
                new Actions\Activate(),
                new Actions\Edit('platform.categories.show')
            ])
        ];
    }
}

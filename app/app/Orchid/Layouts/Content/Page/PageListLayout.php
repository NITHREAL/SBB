<?php

namespace App\Orchid\Layouts\Content\Page;

use App\Orchid\Core\Actions;
use App\Orchid\Helpers\TD\ID;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class PageListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'pages';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            ID::make(),
            TD::make('title', __('admin.title'))
                ->sort()
                ->filter(TD::FILTER_TEXT),
            TD::make('slug', __('admin.page.slug'))
                ->sort()
                ->filter(TD::FILTER_TEXT),
            TD::make()->actions([
                new Actions\Edit("platform.pages.edit"),
            ])
        ];
    }
}

<?php

namespace App\Orchid\Layouts\Shop\Tag;

use App\Orchid\Core\Actions;
use App\Orchid\Helpers\TD\Active;
use App\Orchid\Helpers\TD\ID;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class TagListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    protected $target = 'tags';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            ID::make()->sort(),
            Active::make()->sort(),
            TD::make('text', __('admin.tag.text'))->sort(),
            TD::make()->actions([
                new Actions\Edit('platform.tags.edit')
            ])
        ];
    }
}

<?php

namespace App\Orchid\Layouts\Faq\FaqCategory;

use App\Orchid\Helpers\TD\Active;
use App\Orchid\Helpers\TD\ID;
use App\Orchid\Helpers\TD\Sort;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use App\Orchid\Core\Actions;

class FaqCategoryListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'faqCategories';

    protected function columns(): array
    {
        return [
            ID::make()->sort(),
            Active::make()->sort(),
            TD::make('title', __('admin.title'))->sort(),
            Sort::make()->sort(),
            TD::make()->actions([
                new Actions\Activate(),
                new Actions\Edit('platform.faq-categories.edit'),
            ])
        ];
    }
}

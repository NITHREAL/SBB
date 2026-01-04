<?php

namespace App\Orchid\Layouts\Shop\Category;

use App\Orchid\Filters\ActiveFilter;
use App\Orchid\Filters\IdFilter;
use App\Orchid\Filters\SortFilter;
use App\Orchid\Filters\TitleFilter;
use Orchid\Screen\Layouts\Selection;

class CategoryFiltersLayout extends Selection
{
    /**
     * @return array
     */
    public function filters(): array
    {
        return [
            IdFilter::class,
            ActiveFilter::class,
            TitleFilter::class
        ];
    }
}

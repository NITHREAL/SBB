<?php

namespace App\Orchid\Layouts\Content;

use App\Orchid\Filters\ActiveFilter;
use App\Orchid\Filters\CreatedAtFilter;
use App\Orchid\Filters\IdFilter;
use App\Orchid\Filters\SortFilter;
use App\Orchid\Filters\TitleFilter;
use App\Orchid\Filters\User\UserTypeFilter;
use Orchid\Screen\Layouts\Selection;

class SlidesFiltersLayout extends Selection
{
    /**
     * @return array
     */
    public function filters(): array
    {
        return [
            IdFilter::class,
            ActiveFilter::class,
            TitleFilter::class,
            UserTypeFilter::class,
            SortFilter::class,
            CreatedAtFilter::class
        ];
    }
}

<?php

namespace App\Orchid\Layouts\References\Store;

use App\Orchid\Filters\ActiveFilter;
use App\Orchid\Filters\City\CityFilter;
use App\Orchid\Filters\IdFilter;
use App\Orchid\Filters\SortFilter;
use App\Orchid\Filters\TitleFilter;
use Orchid\Screen\Layouts\Selection;

class StoreFilterLayout extends Selection
{
    /**
     * @inheritDoc
     */
    public function filters(): array
    {
        return [
            IdFilter::class,
            ActiveFilter::class,
            CityFilter::class,
            TitleFilter::class,
            SortFilter::class
        ];
    }
}

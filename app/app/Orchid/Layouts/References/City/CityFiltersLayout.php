<?php

namespace App\Orchid\Layouts\References\City;

use App\Orchid\Filters\IdFilter;
use App\Orchid\Filters\Region\RegionFilter;
use App\Orchid\Filters\SortFilter;
use App\Orchid\Filters\TitleFilter;
use Orchid\Screen\Layouts\Selection;

class CityFiltersLayout extends Selection
{
    /**
     * @return array
     */
    public function filters(): array
    {
        return [
            IdFilter::class,
            TitleFilter::class,
            RegionFilter::class,
            SortFilter::class
        ];
    }
}

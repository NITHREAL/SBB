<?php

namespace App\Orchid\Layouts\Shop\Farmer;

use App\Orchid\Filters\ActiveFilter;
use App\Orchid\Filters\CreatedAtFilter;
use App\Orchid\Filters\Farmer\FarmerNameFilter;
use App\Orchid\Filters\IdFilter;
use App\Orchid\Filters\SortFilter;
use Orchid\Screen\Layouts\Selection;

class FarmerFiltersLayout extends Selection
{
    /**
     * @return array
     */
    public function filters(): array
    {
        return [
            IdFilter::class,
            ActiveFilter::class,
            FarmerNameFilter::class,
            CreatedAtFilter::class,
            SortFilter::class
        ];
    }
}

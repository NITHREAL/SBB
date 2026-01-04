<?php

namespace App\Orchid\Layouts\References\Region;

use App\Orchid\Filters\IdFilter;
use App\Orchid\Filters\SortFilter;
use App\Orchid\Filters\TitleFilter;
use Orchid\Screen\Layouts\Selection;

class RegionFilterLayout extends Selection
{
    /**
     * @inheritDoc
     */
    public function filters(): array
    {
        return [
            IdFilter::class,
            TitleFilter::class,
            SortFilter::class
        ];
    }
}

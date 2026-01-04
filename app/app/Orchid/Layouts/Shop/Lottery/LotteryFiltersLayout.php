<?php

namespace App\Orchid\Layouts\Shop\Lottery;

use App\Orchid\Filters\ActiveFilter;
use App\Orchid\Filters\IdFilter;
use App\Orchid\Filters\SortFilter;
use App\Orchid\Filters\TitleFilter;
use Orchid\Screen\Layouts\Selection;

class LotteryFiltersLayout extends Selection
{
    public function filters(): array
    {
        return [
            IdFilter::class,
            ActiveFilter::class,
            TitleFilter::class,
            SortFilter::class
        ];
    }
}

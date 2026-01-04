<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Reports\Analytics;

use App\Orchid\Filters\Analytics\Journal\BaseDateRangeFilter;
use App\Orchid\Filters\Analytics\Journal\StoreTitleFilter;
use Orchid\Screen\Layouts\Selection;

class ActivityFilterLayout extends Selection
{

    public function filters(): array
    {
        return [
            StoreTitleFilter::class,
            (new BaseDateRangeFilter())

                ->field('created_at'),
        ];
    }
}

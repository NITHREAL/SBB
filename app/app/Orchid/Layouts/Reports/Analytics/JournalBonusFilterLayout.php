<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Reports\Analytics;

use App\Orchid\Filters\Analytics\Journal\DateFilter;
use Orchid\Screen\Layouts\Selection;

class JournalBonusFilterLayout extends Selection
{

    public function filters(): array
    {
        return [
            DateFilter::class,
//            SumPurchaseFilter::class,
//            AmountBonusFilter::class,
//            UserPhone::class,
//            UserFilter::class,
//            StoreTitleFilter::class,
//            OperationFilter::class,
        ];
    }
}

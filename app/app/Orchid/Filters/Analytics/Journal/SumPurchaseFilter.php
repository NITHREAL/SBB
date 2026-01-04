<?php

declare(strict_types=1);

namespace App\Orchid\Filters\Analytics\Journal;

use App\Orchid\Filters\Basic\RangeFilter;

class SumPurchaseFilter extends RangeFilter
{
    public $parameters = [
        'sum_purchase'
    ];

    protected ?string $dbColumn = 'sum_purchase';

    public function name(): string
    {
        return __('admin.journal.sum_purchase');
    }
}

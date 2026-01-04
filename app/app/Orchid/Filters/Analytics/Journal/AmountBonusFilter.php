<?php

declare(strict_types=1);

namespace App\Orchid\Filters\Analytics\Journal;

use App\Orchid\Filters\Basic\RangeFilter;

class AmountBonusFilter extends RangeFilter
{
    public $parameters = [
        'amount_bonus'
    ];

    protected ?string $dbColumn = 'amount_bonus';

    public function name(): string
    {
        return __('admin.journal.amount_bonus');
    }
}

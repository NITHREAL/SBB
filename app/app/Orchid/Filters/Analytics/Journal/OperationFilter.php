<?php

declare(strict_types=1);

namespace App\Orchid\Filters\Analytics\Journal;

use App\Orchid\Filters\Basic\TextFilter;

class OperationFilter extends TextFilter
{
    public $parameters = [
        'name_operation'
    ];

    public function name(): string
    {
        return __('admin.journal.name_operation');
    }
}

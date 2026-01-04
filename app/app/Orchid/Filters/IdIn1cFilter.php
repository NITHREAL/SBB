<?php

namespace App\Orchid\Filters;

use App\Orchid\Filters\Basic\TextFilter;

class IdIn1cFilter extends TextFilter
{
    public $parameters = [
        'system_id'
    ];

    public function name(): string
    {
        return __('admin.system_id');
    }
}

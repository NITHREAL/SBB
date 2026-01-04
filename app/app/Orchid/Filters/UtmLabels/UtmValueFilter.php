<?php

namespace App\Orchid\Filters\UtmLabels;

use App\Orchid\Filters\Basic\TextFilter;

class UtmValueFilter extends TextFilter
{
    public $parameters = [
        'value'
    ];

    public function name(): string
    {
        return __('admin.utm.value');
    }
}

<?php

namespace App\Orchid\Filters;

use App\Orchid\Filters\Basic\NumberFilter;

class IdFilter extends NumberFilter
{
    /**
     * @var array
     */
    public $parameters = [
        'id'
    ];

    /**
     * @return string
     */
    public function name(): string
    {
        return __('admin.id');
    }
}

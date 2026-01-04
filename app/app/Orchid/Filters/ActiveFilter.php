<?php

namespace App\Orchid\Filters;

use App\Orchid\Filters\Basic\BooleanFilter;

class ActiveFilter extends BooleanFilter
{
    public $parameters = [
        'active'
    ];

    protected $options = [
        0  => 'Неактивный',
        1  => 'Активный',
    ];

    public function name(): string
    {
        return __('admin.active');
    }
}

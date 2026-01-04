<?php

namespace App\Orchid\Filters;

use App\Orchid\Filters\Basic\TextFilter;

class TitleFilter extends TextFilter
{
    public $parameters = [
        'title'
    ];

    public function name(): string
    {
        return __('admin.title');
    }
}

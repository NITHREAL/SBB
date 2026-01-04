<?php

namespace App\Orchid\Filters\Farmer;

use App\Orchid\Filters\Basic\TextFilter;

class FarmerNameFilter extends TextFilter
{
    public $parameters = [
        'name'
    ];

    public function name(): string
    {
        return __('admin.farmer.name');
    }
}

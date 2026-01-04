<?php

namespace App\Orchid\Filters\AbandonedBaskets;

use App\Orchid\Filters\Basic\NumberFilter;

class ProductIdFilter extends NumberFilter
{
    /**
     * @var array
     */
    public $parameters = [
        'products.id'
    ];

    /**
     * @return string
     */
    public function name(): string
    {
        return __('admin.products.id');
    }
}

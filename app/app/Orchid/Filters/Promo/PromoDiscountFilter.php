<?php

namespace App\Orchid\Filters\Promo;

use App\Orchid\Filters\Basic\RangeFilter;

class PromoDiscountFilter extends RangeFilter
{
    public $parameters = [
        'discount'
    ];

    public function name(): string
    {
        return __('admin.promo.discount');
    }
}

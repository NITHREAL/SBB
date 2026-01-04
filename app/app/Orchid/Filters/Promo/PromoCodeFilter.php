<?php

namespace App\Orchid\Filters\Promo;

use App\Orchid\Filters\Basic\TextFilter;

class PromoCodeFilter extends TextFilter
{
    public $parameters = [
        'code'
    ];

    public function name(): string
    {
        return __('admin.promo.code');
    }
}

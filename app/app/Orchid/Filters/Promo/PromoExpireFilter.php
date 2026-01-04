<?php

namespace App\Orchid\Filters\Promo;

use App\Orchid\Filters\Basic\DateTimeRangeFilter;

class PromoExpireFilter extends DateTimeRangeFilter
{
    public $parameters = [
        'expires_in'
    ];

    public function name(): string
    {
        return __('admin.promo.expires_in');
    }
}

<?php

namespace App\Orchid\Filters\Promo;

use App\Orchid\Filters\Basic\BooleanFilter;

class PromoFreeDeliveryFilter extends BooleanFilter
{
    public $parameters = [
        'free_delivery'
    ];

    protected $options = [
        0  => 'Нет',
        1  => 'Да',
    ];

    public function name(): string
    {
        return __('admin.promo.free_delivery');
    }
}

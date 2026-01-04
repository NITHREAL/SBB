<?php

namespace App\Orchid\Filters\Order;

use App\Orchid\Filters\Basic\SelectFilter;
use Domain\Order\Enums\OrderSourceEnum;

class OrderRequestFromFilter extends SelectFilter
{
    public $parameters = [
        'request_from'
    ];

    public function __construct()
    {
        parent::__construct();

        $this->options = [
            OrderSourceEnum::site()->value          => 'Из Сайта',
            OrderSourceEnum::mobile()->value        => 'Из МП',
        ];
    }

    public function name(): string
    {
        return __('admin.order.request_from');
    }
}

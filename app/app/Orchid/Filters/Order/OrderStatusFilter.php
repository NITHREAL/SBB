<?php

namespace App\Orchid\Filters\Order;

use App\Orchid\Filters\Basic\SelectFilter;
use Domain\Order\Enums\OrderStatusEnum;

class OrderStatusFilter extends SelectFilter
{
    public $parameters = [
        'status'
    ];

    public function __construct()
    {
        parent::__construct();

        $this->options = OrderStatusEnum::toArray();
    }

    public function name(): string
    {
        return __('admin.order.status');
    }
}

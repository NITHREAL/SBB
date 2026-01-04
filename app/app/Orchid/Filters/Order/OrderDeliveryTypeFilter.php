<?php

namespace App\Orchid\Filters\Order;

use App\Orchid\Filters\Basic\SelectFilter;
use Domain\Order\Enums\Delivery\DeliveryTypeEnum;

class OrderDeliveryTypeFilter extends SelectFilter
{
    public $parameters = [
        'delivery_type'
    ];

    public function __construct()
    {
        parent::__construct();

        $this->options = DeliveryTypeEnum::toArray();
    }

    public function name(): string
    {
        return __('admin.order.delivery_type');
    }
}

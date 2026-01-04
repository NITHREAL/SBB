<?php

namespace App\Orchid\Layouts\Shop\Order;

use App\Orchid\Filters\CreatedAtFilter;
use App\Orchid\Filters\IdFilter;
use App\Orchid\Filters\Order\OrderCityFilter;
use App\Orchid\Filters\Order\OrderDeliveryTypeFilter;
use App\Orchid\Filters\Order\OrderPaymentTypeFilter;
use App\Orchid\Filters\Order\OrderRequestFromFilter;
use App\Orchid\Filters\Order\OrderStatusFilter;
use App\Orchid\Filters\Order\UtmTypeFilter;
use App\Orchid\Filters\Order\UtmValueFilter;
use App\Orchid\Filters\User\UserFilter;
use App\Orchid\Filters\UtmLabels\UtmSourceFilter;
use Orchid\Screen\Layouts\Selection;

class OrderFilterLayout extends Selection
{
    public function filters(): array
    {
        return [
            IdFilter::class,
            UserFilter::class,
            OrderCityFilter::class,
            OrderStatusFilter::class,
            OrderDeliveryTypeFilter::class,
            OrderPaymentTypeFilter::class,
            OrderRequestFromFilter::class,
            CreatedAtFilter::class,
        ];
    }
}

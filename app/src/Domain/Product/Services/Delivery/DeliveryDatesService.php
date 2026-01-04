<?php

namespace Domain\Product\Services\Delivery;

use Domain\Product\Models\ProductDeliveryDate;
use Illuminate\Support\Collection;

class DeliveryDatesService
{
    public function getNearestDeliveryDatesByCollection(array $productIds): Collection
    {
        return ProductDeliveryDate::query()
            ->whereIn('product_id', $productIds)
            ->get();
    }
}

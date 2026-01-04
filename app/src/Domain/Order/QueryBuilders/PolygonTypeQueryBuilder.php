<?php

namespace Domain\Order\QueryBuilders;

use Domain\Order\Enums\Delivery\PolygonTypeEnum;
use Illuminate\Database\Eloquent\Builder;
use Infrastructure\Eloquent\Builder\BaseQueryBuilder;

class PolygonTypeQueryBuilder extends BaseQueryBuilder
{
    public function whereDelivery(): Builder
    {
        return $this->where('polygon_types.delivery_type', PolygonTypeEnum::delivery()->value);
    }

    public function wherePickup(): Builder
    {
        return $this->where('polygon_types.delivery_type', PolygonTypeEnum::pickup()->value);
    }
}

<?php

namespace Domain\User\Resources\Order;

use Illuminate\Http\Resources\Json\JsonResource;
use Infrastructure\Http\Resources\PaginationResource;

class UserOrdersResource extends JsonResource
{
    public function toArray($request): array
    {
        $orders = $this->resource;

        return [
            'orders'        => UserOrderResource::collection($orders),
            'pagination'    => PaginationResource::make($orders),
        ];
    }
}

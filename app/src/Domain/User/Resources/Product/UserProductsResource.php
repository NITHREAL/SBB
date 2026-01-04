<?php

namespace Domain\User\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;
use Infrastructure\Http\Resources\PaginationResource;

class UserProductsResource extends JsonResource
{
    public function toArray($request): array
    {
        $products = $this->resource;

        return [
            'orders'        => UserProductResource::collection($products),
            'pagination'    => PaginationResource::make($products),
        ];
    }
}

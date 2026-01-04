<?php

namespace Domain\ProductGroup\Resources\ProductGroupPage;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Infrastructure\Http\Resources\PaginationResource;

class ProductGroupPageResource extends JsonResource
{
    public function toArray($request): array
    {
        $products = Arr::get($this->resource, 'products');

        return [
            'productGroup'          => ProductGroupPageGroupResource::make(
                Arr::get($this->resource, 'productGroup')
            ),
            'products'              => ProductGroupPageProductResource::collection($products),
            'filters'               => Arr::get($this->resource, 'filters'),
            'pagination'            => PaginationResource::make($products),
        ];
    }
}

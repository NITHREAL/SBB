<?php

namespace Domain\Product\Resources\Catalog;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Infrastructure\Http\Resources\PaginationResource;

class ForgottenProductResource extends JsonResource
{
    public function toArray($request): array
    {
        $products = Arr::get($this->resource, 'products');

        return [
            'products'              => ForgottenProductCatalogResource::collection($products),
            'filters'               => Arr::get($this->resource, 'filters'),
            'pagination'            => PaginationResource::make($products),
        ];
    }
}

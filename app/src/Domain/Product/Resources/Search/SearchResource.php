<?php

namespace Domain\Product\Resources\Search;

use Domain\Product\Resources\Catalog\CatalogProductResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Infrastructure\Http\Resources\PaginationResource;

class SearchResource extends JsonResource
{
    public function toArray($request): array
    {
        $products = Arr::get($this->resource, 'products');

        return [
            'products'      => CatalogProductResource::collection($products),
            'search'        => Arr::get($this->resource, 'search'),
            'filters'       => Arr::get($this->resource, 'filters'),
            'pagination'    => PaginationResource::make($products),
        ];
    }
}

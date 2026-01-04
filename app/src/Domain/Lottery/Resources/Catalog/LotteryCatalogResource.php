<?php

namespace Domain\Lottery\Resources\Catalog;

use Domain\Product\Resources\Catalog\CatalogProductResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Infrastructure\Http\Resources\PaginationResource;

class LotteryCatalogResource extends JsonResource
{
    public function toArray($request): array
    {
        $lottery = Arr::get($this->resource, 'lottery');
        $products = Arr::get($this->resource, 'products');

        return [
            'lottery'       => LotterySimpleResource::make($lottery),
            'products'      => CatalogProductResource::collection($products),
            'filters'       => Arr::get($this->resource, 'filters'),
            'pagination'    => PaginationResource::make($products),
        ];
    }
}

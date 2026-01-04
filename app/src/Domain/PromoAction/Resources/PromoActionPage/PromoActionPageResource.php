<?php

namespace Domain\PromoAction\Resources\PromoActionPage;

use Domain\Product\Resources\Catalog\CatalogProductResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Infrastructure\Http\Resources\PaginationResource;

class PromoActionPageResource extends JsonResource
{
    public function toArray($request): array
    {
        $promoAction = Arr::get($this->resource, 'promoAction');
        $products = Arr::get($this->resource, 'products');

        return [
            'promoAction'   => PromoActionPagePromoActionResource::make($promoAction),
            'products'      => CatalogProductResource::collection($products),
            'filters'       => Arr::get($this->resource, 'filters'),
            'pagination'    => PaginationResource::make($products),
        ];
    }
}

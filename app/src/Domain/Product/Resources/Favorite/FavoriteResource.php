<?php

namespace Domain\Product\Resources\Favorite;

use Domain\Product\Resources\Catalog\CatalogProductResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Infrastructure\Http\Resources\PaginationResource;

class FavoriteResource extends JsonResource
{
    public function toArray($request): array
    {
        $products = $this->resource;

        return [
            'products'      => CatalogProductResource::collection($products),
            'pagination'    => PaginationResource::make($products),
        ];
    }
}

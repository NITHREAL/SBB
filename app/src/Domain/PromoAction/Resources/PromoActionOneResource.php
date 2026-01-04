<?php

namespace Domain\PromoAction\Resources;

use Domain\Product\Resources\Catalog\CatalogProductResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class PromoActionOneResource extends JsonResource
{
    public function toArray($request): array
    {
        $products = Arr::get($this->resource, 'productsData', collect());

        return [
            'id'            => $this->resource->id,
            'title'         => $this->resource->title,
            'description'   => $this->resource->description,
            'slug'          => $this->resource->slug,
            'dateFrom'      => $this->resource->formattedActiveFrom,
            'dateTo'        => $this->resource->formattedActiveTo,
            'image'         => $this->resource->imageUrl,
            'products'      => CatalogProductResource::collection($products),
        ];
    }
}

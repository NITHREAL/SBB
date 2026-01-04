<?php

namespace Domain\Product\Resources\Catalog\CatalogPreview;

use Domain\Product\Resources\Catalog\CatalogProductResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class CatalogPreviewSubcategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'        => Arr::get($this->resource, 'id'),
            'title'     => Arr::get($this->resource, 'title'),
            'slug'      => Arr::get($this->resource, 'slug'),
            'isHasChilds' => Arr::get($this->resource, 'is_has_childs'),
            'products'  => CatalogProductResource::collection(
                Arr::get($this->resource, 'products'),
            ),
        ];
    }
}

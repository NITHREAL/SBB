<?php

namespace Domain\Product\Resources\Catalog\CatalogPreview;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class CatalogPreviewResource extends JsonResource
{
    public function toArray($request): array
    {
        $category = Arr::get($this->resource, 'category', []);

        return [
            'id'        => Arr::get($category, 'id'),
            'title'     => Arr::get($category, 'title'),
            'slug'      => Arr::get($category, 'slug'),
            'childs'    => CatalogPreviewSubcategoryResource::collection(
                Arr::get($this->resource, 'childs', []),
            ),
        ];
    }
}

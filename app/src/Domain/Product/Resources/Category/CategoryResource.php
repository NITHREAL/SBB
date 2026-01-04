<?php

namespace Domain\Product\Resources\Category;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        $category = $this->resource;

        return [
            'id'                => (int) $category->id,
            'slug'              => $category->slug,
            'image'             => $category->image_original,
            'imageBlurHash'     => $category->image_blur_hash,
            'title'             => (string) $category->title,
            'isHasChilds'       => (bool) $category->is_has_childs,
        ];
    }
}

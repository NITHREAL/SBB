<?php

namespace Domain\Product\Resources\Category;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryPageResource extends JsonResource
{
    public function toArray($request): array
    {
        $category = $this->resource;

        $childs = $category->childs;

        return [
            'id'                => (int) $category->id,
            'slug'              => $category->slug,
            'title'             => (string) $category->title,
            'childs'            => $childs ? CategoryResource::collection($childs) : [],
        ];
    }
}

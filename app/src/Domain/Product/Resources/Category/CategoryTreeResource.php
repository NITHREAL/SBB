<?php

namespace Domain\Product\Resources\Category;

use Domain\Product\Models\Category;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryTreeResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Category $category */
        $category = $this->resource;

        return [
            'id'                => (int)$category->id,
            'parentId'          => $category->parentId,
            'slug'              => $category->slug,
            'image'             => $category->image_original,
            'imageBlurHash'     => $category->image_blur_hash,
            'title'             => (string)$category->title,
            'marginLeft'        => (int)$category->margin_left,
            'marginRight'       => (int)$category->margin_right,
            'level'             => (int)$category->level,
            'childs'            => $category->childs ? SubCategoryResource::collection($category->childs) : [],
        ];
    }

    public function withResponse($request, $response): void
    {
        $response->addMeta([
            'includes' => [
                'childs' => SubCategoryResource::class,
            ],
        ]);
    }
}

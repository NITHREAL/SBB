<?php

namespace Domain\User\Resources\Category;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                => $this->resource->id,
            'title'             => $this->resource->title,
            'image'             => $this->resource->image,
        ];
    }
}

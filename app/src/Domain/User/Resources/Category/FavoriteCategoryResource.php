<?php

namespace Domain\User\Resources\Category;

use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteCategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'title'     => $this->resource->title,
            'image'     => $this->resource->image,
            'period'    => $this->resource->period,
        ];
    }
}

<?php

namespace Domain\Lottery\Resources\Catalog;

use Illuminate\Http\Resources\Json\JsonResource;

class LotterySimpleResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            => $this->resource->id,
            'title'         => $this->resource->title,
            'slug'          => $this->resource->slug,
            'image'         => $this->resource->imageUrl,
        ];
    }
}

<?php

namespace Domain\PromoAction\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PromoActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            => $this->resource->id,
            'title'         => $this->resource->title,
            'description'   => $this->resource->short_description,
            'slug'          => $this->resource->slug,
            'image'         => $this->resource->imageUrl,
        ];
    }
}

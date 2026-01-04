<?php

namespace Domain\PromoAction\Resources\PromoActionPage;

use Illuminate\Http\Resources\Json\JsonResource;

class PromoActionPagePromoActionResource extends JsonResource
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

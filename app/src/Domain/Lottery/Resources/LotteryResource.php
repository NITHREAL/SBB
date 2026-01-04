<?php

namespace Domain\Lottery\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LotteryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            => $this->resource->id,
            'title'         => $this->resource->title,
            'slug'          => $this->resource->slug,
            'image'         => $this->resource->imageUrl,
            'dateFrom'      => $this->resource->formattedActiveFrom,
            'dateTo'        => $this->resource->formattedActiveTo,
        ];
    }
}

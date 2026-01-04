<?php

namespace Domain\City\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'        => $this->resource->id,
            'title'     => $this->resource->title,
            'fiasId'   => $this->resource->fias_id,
            'latitude'  => $this->resource->latitude,
            'longitude' => $this->resource->longitude,
            'timezone'  => $this->resource->timezone
        ];
    }
}

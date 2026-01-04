<?php

namespace Domain\Farmer\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Infrastructure\Http\Resources\PaginationResource;

class FarmerCollectionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'farmers' => FarmerResource::collection($this->resource),
            'pagination' => PaginationResource::make($this->resource),
        ];
    }
}

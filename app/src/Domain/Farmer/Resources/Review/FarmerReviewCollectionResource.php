<?php

namespace Domain\Farmer\Resources\Review;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Infrastructure\Http\Resources\PaginationResource;


class FarmerReviewCollectionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'reviews' => FarmerReviewResource::collection($this->resource),
            'pagination' => PaginationResource::make($this->resource),
        ];
    }
}

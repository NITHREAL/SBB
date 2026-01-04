<?php

namespace Domain\Farmer\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class FarmerCategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => Arr::get($this->resource, 'id'),
            'title' => Arr::get($this->resource, 'title'),
            'slug' => Arr::get($this->resource, 'slug'),
            'products' => FarmerProductResource::collection(
                Arr::get($this->resource, 'products'),
            ),
        ];
    }
}

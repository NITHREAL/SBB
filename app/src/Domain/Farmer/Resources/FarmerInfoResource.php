<?php

namespace Domain\Farmer\Resources;

use Domain\Farmer\Resources\Product\FarmerCategoryResource;
use Domain\Product\Helpers\RatingHelper;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class FarmerInfoResource extends JsonResource
{
    public function toArray($request): array
    {
        $farmer = Arr::get($this->resource, 'farmerInfo');
        return [
            'id' => $farmer->id,
            'name' => $farmer->name,
            'slug' => $farmer->slug,
            'supplyDescription' => $farmer->supply_description,
            'description' => $farmer->description,
            'image' => $farmer->image_original,
            'imageBlurHash' => $farmer->image_blur_hash,
            'rating' => RatingHelper::getRatingFormat($farmer->rating),
            'reviewCount' => $farmer->reviewCount,
            'address' => $farmer->address,
            'reviewInfo' => $farmer->review_info_format,
            'categories' => FarmerCategoryResource::collection(Arr::get($this->resource, 'categories')),
            'certificates' => $farmer->certificates,
        ];
    }
}

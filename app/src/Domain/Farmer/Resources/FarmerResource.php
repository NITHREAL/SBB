<?php

declare(strict_types=1);

namespace Domain\Farmer\Resources;

use Domain\Product\Helpers\RatingHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class FarmerResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                    => $this->id,
            'name'                  => $this->name,
            'slug'                  => $this->slug,
            'supplyDescription'    => $this->supply_description,
            'image'                 => $this->image_original,
            'imageBlurHash'       => $this->image_blur_hash,
            'rating'                => RatingHelper::getRatingFormat($this->rating),
            'reviewCount'           => $this->reviewCount,
        ];
    }
}

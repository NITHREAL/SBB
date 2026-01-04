<?php

namespace Domain\CouponCategory\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponCategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        $couponCategory = $this->resource;

        return [
            'id'        => $couponCategory->id,
            'title'     => $couponCategory->title,
            'image'     => $couponCategory->imageUrl,
            'price'     => $couponCategory->price,
        ];
    }
}

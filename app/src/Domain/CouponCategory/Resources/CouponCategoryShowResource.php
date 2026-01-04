<?php

namespace Domain\CouponCategory\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponCategoryShowResource extends JsonResource
{
    public function toArray($request): array
    {
        $couponCategory = $this->resource;

        return [
            'id'            => $couponCategory->id,
            'title'         => $couponCategory->title,
            'description'   => $couponCategory->description,
            'purchaseTerms' => $couponCategory->purchase_terms,
            'images'        => $couponCategory->imagesData,
            'price'         => $couponCategory->price,
        ];
    }
}

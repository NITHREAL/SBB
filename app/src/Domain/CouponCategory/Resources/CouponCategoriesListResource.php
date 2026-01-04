<?php

namespace Domain\CouponCategory\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Infrastructure\Http\Resources\PaginationResource;

class CouponCategoriesListResource extends JsonResource
{
    public function toArray($request): array
    {
        $couponCategories = $this->resource;

        return [
            'couponCategories'  => CouponCategoryResource::collection($couponCategories),
            'pagination'        => PaginationResource::make($couponCategories),
        ];
    }
}

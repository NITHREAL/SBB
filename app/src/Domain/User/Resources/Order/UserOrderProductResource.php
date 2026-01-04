<?php

namespace Domain\User\Resources\Order;

use Domain\Product\Helpers\ProductHelper;
use Domain\Product\Helpers\RatingHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class UserOrderProductResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                    => (int) $this->id,
            'image'                 => $this->image_original,
            'imageBlurHash'         => $this->image_blur_hash,
            'title'                 => (string) $this->title,
            'slug'                  => (string) $this->slug,
            'rating'                => RatingHelper::getRatingFormat($this->rating),
            'unit'                  => (string) $this->unitTitle,
            'weight'                => (float) $this->weight,
            'count'                 => (int) $this->count,
            'price'                 => ProductHelper::getPreparedProductPrice($this->price),
            'priceDiscount'         => ProductHelper::getPreparedProductPrice($this->priceDiscount),
            'total'                 => ProductHelper::getPreparedProductPrice($this->total),
            'favorited'             => (bool) $this->favorited,
            'sumUnit'               => $this->sum_unit,
        ];
    }
}

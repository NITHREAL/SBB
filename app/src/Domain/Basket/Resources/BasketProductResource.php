<?php

namespace Domain\Basket\Resources;

use Domain\Product\Helpers\RatingHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class BasketProductResource extends JsonResource
{
    public function toArray($request): array
    {
        $product = $this->resource;

        return [
            'id'                => $product->id,
            'id1c'              => $product->id1C,
            'title'             => $product->title,
            'slug'              => $product->slug,
            'byPreorder'        => $product->by_preorder,
            'favorited'         => $product->favorited,
            'rating'            => RatingHelper::getRatingFormat($this->rating),
            'isWeight'          => (bool) $product->is_weight,
            'unit'              => $product->unitTitle,
            'price'             => $product->price,
            'priceDiscount'     => $product->price_discount,
            'priceUnit'         => $product->price_unit,
            'count'             => $product->count,
            'availableCount'    => $product->available_count,
            'basketWeight'      => $product->is_weight
                ? (float) $product->basketWeight
                : null,
            'sum'               => $product->sum,
            'sumPrev'           => $product->sum_prev,
            'sumUnit'           => $product->sum_unit,
            'image'             => $product->image_original,
            'imageBlurHash'     => $product->image_blur_hash,
        ];
    }
}

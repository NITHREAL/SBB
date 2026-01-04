<?php

namespace Domain\Farmer\Resources\Product;

use Domain\Tag\Resources\TagResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class FarmerProductResource extends JsonResource
{
    public function toArray($request): array
    {
        $product = $this->resource;
        $price = Arr::get($product->prices, 'display.price');

        $tagsData = $this->tagsData
            ? TagResource::collection($this->tagsData)
            : [];

        return [
            'id'                    => (int) $this->id,
            'image'                 => $this->image_original,
            'imageBlurHash'         => $this->image_blur_hash,
            'title'                 => (string) $this->title,
            'slug'                  => (string) $this->slug,
            'rating'                => floor((float) $this->rating),
            'unit'                  => (string) $this->unitTitle,
            'weight'                => (float) $this->weight,
            'inStock'               => (bool) $this->in_stock,
            'countInBasket'         => (int) $this->count_in_basket,
            'price'                 => $price,
            'priceDiscount'         => Arr::get($product->prices, 'display.price_discount'),
            'priceUnit'             => $this->price_unit,
            'dateSupply'            => $this->date_supply,
            'deliveryInCountry'     => (bool) $this->delivery_in_country,
            'byPreorder'            => (bool) $product->by_preorder,
            'cooking'               => (bool) $this->cooking,
            'availableCount'        => (int) $product->available_count,
            'canBuy'                => (bool) $product->can_buy,
            'favorited'             => (bool) $product->favorited,
            'categoryId'            => $product->categoryId,
            'tags'                  => $tagsData,
        ];
    }
}

<?php

namespace Domain\Product\Resources\Catalog;

use Domain\Product\Helpers\RatingHelper;
use Domain\Tag\Resources\TagResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class BaseCatalogProductResource extends JsonResource
{
    public function toArray(Request $request)
    {
        $product = $this->resource;
        $price = Arr::get($product->prices, 'display.price');

        $farmerData = $this->farmerId
            ? collect([
                'id'    => $this->farmerId,
                'name'  => $this->farmerName,
                'slug'  => $this->farmerSlug,
            ])
            : null;

        $tagsData = $this->tagsData
            ? TagResource::collection($this->tagsData)
            : [];

        return [
            'id'                    => (int) $this->id,
            'image'                 => $this->image_original,
            'imageBlurHash'         => $this->image_blur_hash,
            'title'                 => (string) $this->title,
            'slug'                  => (string) $this->slug,
            'rating'                => RatingHelper::getRatingFormat($this->rating),
            'popular'               => (int) $this->popular,
            'unit'                  => (string) $this->unitTitle,
            'weight'                => (float) $this->weight,
            'isWeight'              => (bool) $this->is_weight,
            'inStock'               => (bool) $this->in_stock,
            'countInBasket'         => (int) $this->count_in_basket,
            'weightInBasket'        => $this->is_weight
                ? (float) $this->weight_in_basket
                : null,
            'price'                 => $price,
            'priceDiscount'         => Arr::get($product->prices, 'display.price_discount'),
            'priceUnit'             => $this->price_unit,
            'dateSupply'            => $this->date_supply,
            'deliveryInCountry'     => (bool) $this->delivery_in_country,
            'byPreorder'            => (bool) $product->by_preorder,
            'cooking'               => (bool) $this->cooking,
            'availableCount'        => (int) $product->available_count,
            'canBuy'                => (bool) $product->can_buy,
            'farmer'                => $farmerData,
            'favorited'             => (bool) $product->favorited,
            'categoryId'            => $product->categoryId,
            'tags'                  => $tagsData,
        ];
    }
}

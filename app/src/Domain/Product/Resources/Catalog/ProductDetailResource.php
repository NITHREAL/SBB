<?php

namespace Domain\Product\Resources\Catalog;

use Domain\Product\Helpers\RatingHelper;
use Domain\Product\Models\Product;
use Domain\Tag\Resources\TagResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

/**
 * @mixin Product
 */
class ProductDetailResource extends JsonResource
{
    public function toArray($request): array
    {
        $farmerData = $this->farmer_id
            ? collect([
                'id'    => $this->farmer_id,
                'name'  => $this->farmer_name,
                'slug'  => $this->farmer_slug,
            ])
            : null;

        $tagsData = $this->tagsData
            ? TagResource::collection($this->tagsData)
            : [];

        $relatedProductsData = $this->relatedProductsData
            ? CatalogProductResource::collection($this->relatedProductsData)
            : [];

        return [
            'id'                => $this->id,
            'title'             => $this->title,
            'description'       => $this->description,
            'composition'       => $this->composition,
            'slug'              => $this->slug,
            'rating'            => RatingHelper::getRatingFormat($this->rating),
            'unit'              => (string) $this->unitTitle,
            'available'         => $this->available,
            'byPreorder'        => $this->by_preorder,
            'cooking'           => $this->cooking,
            'countInBasket'     => (int) $this->count_in_basket,
            'weightInBasket'     => (float) $this->weight_in_basket,
            'inStock'           => (bool) $this->in_stock,
            'dateSupply'        => $this->date_supply,
            'deliveryInCountry' => (bool) $this->delivery_in_country,
            'availableCount'    => $this->available_count,
            'canBuy'            => (bool) $this->can_buy,
            'reviewed'          => (bool) $this->reviewed,
            'priceDiscount'     => Arr::get($this->prices, 'display.price_discount'),
            'priceUnit'         => $this->price_unit,
            'price'             => Arr::get($this->prices, 'display.price'),
            'sum'               => $this->sum,
            'sumUnit'           => $this->price_unit,
            'weight'            => $this->weight,
            'weightUnit'        => $this->weight_unit,
            'isWeight'          => (bool) $this->is_weight,
            'images'            => $this->preparedImages,
            'properties'        => [
                'proteins'          => $this->proteins,
                'fats'              => $this->fats,
                'carbohydrates'     => $this->carbohydrates,
                'nutritionKcal'     => $this->nutrition_kcal,
                'nutritionKj'       => $this->nutrition_kj,
                'storageConditions' => $this->storage_conditions,
                'shelfLife'         => $this->shelf_life,
                'vegan'             => $this->vegan,
            ],
            'favorited'         => $this->favorited,
            'categoryId'        => $this->category_id,
            'farmer'            => $farmerData,
            'tags'              => $tagsData,
            'relatedProducts'   => $relatedProductsData,
            'isReviewAvailability' => $this->is_review_availability,
            'reviewCount'       => $this->reviewCount,
        ];
    }
}

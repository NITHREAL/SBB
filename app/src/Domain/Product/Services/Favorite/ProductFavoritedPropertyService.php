<?php

namespace Domain\Product\Services\Favorite;

use Illuminate\Support\Collection;

class ProductFavoritedPropertyService
{
    public function __construct(
        private readonly FavoriteService $favoriteService,
    ) {
    }

    public function defineFavoritedPropertyOnObject(object $product): object
    {
        $favoriteProducts = $this->favoriteService->getFavorite()->products;

        return $this->defineFavoritedProperty($product, $favoriteProducts);
    }

    public function defineFavoritedPropertyOnCollection(Collection $products): Collection
    {
        $favoriteProducts = $this->favoriteService->getFavorite()->products;

        return $products->map(function (object $product) use ($favoriteProducts) {
            return $this->defineFavoritedProperty($product, $favoriteProducts);
        });
    }

    private function defineFavoritedProperty(object $product, Collection $favoritedProducts): object
    {
        $product->favorited = $favoritedProducts->where('id', $product->id)->isNotEmpty();

        return $product;
    }
}

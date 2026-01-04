<?php

namespace Domain\FavoriteCategory\Services;

use Domain\FavoriteCategory\Models\FavoriteCategory;
use Illuminate\Support\Arr;

readonly class FavoriteCategoryChangeService
{
    public function updateFavoriteCategories(array $favoriteCategoriesData): void
    {
        foreach ($favoriteCategoriesData as $favoriteCategoryData) {
            $this->fillFavoriteCategory($favoriteCategoryData);
        }
    }

    private function fillFavoriteCategory(array $favoriteCategoryData): void
    {
        $loyaltyId = Arr::get($favoriteCategoryData, 'id');

        $favoriteCategory = FavoriteCategory::query()->whereLoyaltyId($loyaltyId)->first();

        if (empty($favoriteCategory)) {
            $favoriteCategory = new FavoriteCategory();
            $favoriteCategory->loyalty_id = $loyaltyId;
        }

        $favoriteCategory->fill([
            'title' => Arr::get($favoriteCategoryData, 'title'),
            'image' => Arr::get($favoriteCategoryData, 'image'),
            'period' => Arr::get($favoriteCategoryData, 'period'),
        ]);

        $favoriteCategory->save();
    }
}

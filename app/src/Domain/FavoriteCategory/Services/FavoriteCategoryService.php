<?php

namespace Domain\FavoriteCategory\Services;

use Domain\FavoriteCategory\Services\Loyalty\FavoriteCategoryLoyaltyService;
use Infrastructure\Services\Loyalty\Exceptions\LoyaltyException;

readonly class FavoriteCategoryService
{
    public function __construct(
        private FavoriteCategoryLoyaltyService  $favoriteCategoryLoyaltyService,
        private FavoriteCategoryChangeService $favoriteCategoryChangeService,
    ) {
    }

    /**
     * @throws LoyaltyException
     */
    public function updateFavoriteCategories(): void
    {
        $favoriteCategories = $this->favoriteCategoryLoyaltyService->getLoyaltyFavoriteCategories();

        $this->favoriteCategoryChangeService->updateFavoriteCategories($favoriteCategories);
    }
}

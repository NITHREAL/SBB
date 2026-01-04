<?php

namespace Domain\FavoriteCategory\Services\Loyalty;

use Domain\User\Models\User;
use Infrastructure\Services\Loyalty\Exceptions\LoyaltyException;
use Infrastructure\Services\Loyalty\Facades\Loyalty;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\FavoriteCategories\GetFavoriteCategoriesDTO;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\FavoriteCategories\GetFavoriteCategoryDetailDTO;
use Infrastructure\Services\Loyalty\Responses\Manzana\FavoriteCategories\GetAll\FavoriteCategory;
use Infrastructure\Services\Loyalty\Responses\Manzana\FavoriteCategories\GetAll\GetFavoriteCategoriesResponse;
use Infrastructure\Services\Loyalty\Responses\Manzana\FavoriteCategories\GetDetail\GetFavoriteCategoryDetailResponse;

readonly class FavoriteCategoryLoyaltyService
{
    /**
     * @throws LoyaltyException
     */
    public function getLoyaltyFavoriteCategories(): array
    {
        $sessionId = $this->getSessionId();

        if (empty($sessionId)) {
            throw new LoyaltyException('Отсутствует sessionId');
        }

        return $this->getCategoriesFromLoyalty($sessionId);
    }

    private function getCategoriesFromLoyalty(string $sessionId): array
    {
        $categories = $this->getFavoriteCategoriesFromLoyalty($sessionId);

        $favoriteCategories = [];

        foreach ($categories as $category) {
            /** @var FavoriteCategory $category */

            $productListId = $category->getProductListGroupId();

            $categoryDetail = $this->getCategoryDetailFromLoyalty($sessionId, $productListId);

            $favoriteCategories[] = [
                'id'        => $category->getId(),
                'title'     => $category->getName(),
                'image'     => $categoryDetail->getImage(),
                'period'    => $category->getPeriod(),
            ];
        }

        return $favoriteCategories;
    }

    private function getCategoryDetailFromLoyalty(
        string $sessionId,
        string $categoryProductListId,
    ): GetFavoriteCategoryDetailResponse {
        $getCategoryDetailDTO = GetFavoriteCategoryDetailDTO::make([
            'sessionId'             => $sessionId,
            'productListGroupId'    => $categoryProductListId,
        ]);

        /** @var GetFavoriteCategoryDetailResponse $response */
        $response = Loyalty::getFavoriteCategoryDetail($getCategoryDetailDTO);

        return $response;
    }

    private function getFavoriteCategoriesFromLoyalty(string $sessionId): array
    {
        $getAllCategoriesDTO = GetFavoriteCategoriesDTO::make([
            'sessionId' => $sessionId,
        ]);

        /** @var GetFavoriteCategoriesResponse $response */
        $response = Loyalty::getAllFavoriteCategories($getAllCategoriesDTO);

        return $response->getCategories();
    }

    private function getSessionId(): ?string
    {
        return User::query()
            ->whereNotNull('loyalty_session_id')
            ->orderByDesc('loyalty_session_id')
            ->value('loyalty_session_id');
    }
}

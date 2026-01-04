<?php

namespace Domain\User\Services\Loyalty\FavoriteCategories;

use Domain\User\Models\User;
use Infrastructure\Services\Loyalty\Facades\Loyalty;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\FavoriteCategories\SetUserFavoriteCategoriesDTO;
use Infrastructure\Services\Loyalty\Responses\Manzana\FavoriteCategories\SetUserFavoriteCategoryResponse;

readonly class LoyaltyUserFavoriteCategoryService
{
    public function setFavoriteCategoryToUser(
        User $user,
        string $favoriteCategoryLoyaltyId,
    ): SetUserFavoriteCategoryResponse {
        $dto = SetUserFavoriteCategoriesDTO::make([
            'sessionId'                     => $user->loyalty_session_id,
            'contactId'                     => $user->loyalty_id,
            'personalCampaignSettingsId'    => $favoriteCategoryLoyaltyId,
        ]);

        /** @var SetUserFavoriteCategoryResponse $response */
        $response = Loyalty::setUserFavoriteCategory($dto);

        return $response;
    }
}

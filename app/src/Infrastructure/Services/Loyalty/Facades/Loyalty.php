<?php

namespace Infrastructure\Services\Loyalty\Facades;

use Illuminate\Support\Facades\Facade;
use Infrastructure\Services\Loyalty\Gateways\Manzana\Manzana;
use Infrastructure\Services\Loyalty\RequestDTO\LoyaltyDTOInterface;
use Infrastructure\Services\Loyalty\Responses\ResponseInterface;

/**
 * @mixin Manzana
 *
 * @method ResponseInterface requestSmsAuth(LoyaltyDTOInterface $loyaltyDTO)
 * @method ResponseInterface confirmSmsAuth(LoyaltyDTOInterface $loyaltyDTO)
 * @method ResponseInterface getContact(LoyaltyDTOInterface $loyaltyDTO)
 * @method ResponseInterface updateContact(LoyaltyDTOInterface $loyaltyDTO)
 * @method ResponseInterface getContactBonusesHistory(LoyaltyDTOInterface $loyaltyDTO)
 * @method ResponseInterface getContactLevelsInfo(LoyaltyDTOInterface $loyaltyDTO)
 * @method ResponseInterface getContactLevels(LoyaltyDTOInterface $loyaltyDTO)
 * @method ResponseInterface getLevels(LoyaltyDTOInterface $loyaltyDTO)
 * @method ResponseInterface getContactCards(LoyaltyDTOInterface $loyaltyDTO)
 * @method ResponseInterface addCardToContact(LoyaltyDTOInterface $loyaltyDTO)
 * @method ResponseInterface addVirtualCardToContact(LoyaltyDTOInterface $loyaltyDTO)
 * @method ResponseInterface getAllFavoriteCategories(LoyaltyDTOInterface $loyaltyDTO)
 * @method ResponseInterface getFavoriteCategoryDetail(LoyaltyDTOInterface $loyaltyDTO)
 * @method ResponseInterface setUserFavoriteCategory(LoyaltyDTOInterface $loyaltyDTO)
 * @method ResponseInterface getUserFavoriteCategories(LoyaltyDTOInterface $loyaltyDTO)
 *
 */
class Loyalty extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'Loyalty';
    }
}

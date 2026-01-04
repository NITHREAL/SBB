<?php

namespace Infrastructure\Services\Loyalty\Gateways\Manzana;

use Infrastructure\Services\Loyalty\Gateways\GatewayInterface;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\Auth\ConfirmSMSAuthDTO;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\Auth\RequestSMSAuthDTO;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\Bonuses\GetContactBonusesHistoryDTO;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\Cards\AddCardToContactDTO;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\Cards\GetContactCardsDTO;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\Contact\GetContactDTO;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\Contact\UpdateContactDTO;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\FavoriteCategories\GetFavoriteCategoriesDTO;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\FavoriteCategories\GetFavoriteCategoryDetailDTO;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\FavoriteCategories\GetUserFavoriteCategoriesDTO;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\FavoriteCategories\SetUserFavoriteCategoriesDTO;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\Levels\GetContactLevelInfoDTO;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\Levels\GetContactLevelsDTO;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\Levels\GetLevelsDTO;
use Infrastructure\Services\Loyalty\Responses\Manzana\Auth\ConfirmSmsAuthResponse;
use Infrastructure\Services\Loyalty\Responses\Manzana\Auth\RequestSmsAuthResponse;
use Infrastructure\Services\Loyalty\Responses\Manzana\Bonuses\GetContactBonusesHistoryResponse;
use Infrastructure\Services\Loyalty\Responses\Manzana\Cards\AddCardToContactResponse;
use Infrastructure\Services\Loyalty\Responses\Manzana\Cards\GetContactCardsResponse;
use Infrastructure\Services\Loyalty\Responses\Manzana\Contact\GetContactResponse;
use Infrastructure\Services\Loyalty\Responses\Manzana\Contact\UpdateContactResponse;
use Infrastructure\Services\Loyalty\Responses\Manzana\FavoriteCategories\GetAll\GetFavoriteCategoriesResponse;
use Infrastructure\Services\Loyalty\Responses\Manzana\FavoriteCategories\GetDetail\GetFavoriteCategoryDetailResponse;
use Infrastructure\Services\Loyalty\Responses\Manzana\FavoriteCategories\GetForUser\GetUserFavoriteCategoriesResponse;
use Infrastructure\Services\Loyalty\Responses\Manzana\FavoriteCategories\SetUserFavoriteCategoryResponse;
use Infrastructure\Services\Loyalty\Responses\Manzana\Levels\ContactLevelInfo\GetContactLevelsInfoResponse;
use Infrastructure\Services\Loyalty\Responses\Manzana\Levels\ContactLevels\GetContactLevelsResponse;
use Infrastructure\Services\Loyalty\Responses\Manzana\Levels\Levels\GetLevelsResponse;

interface ManzanaGatewayInterface extends GatewayInterface
{
    public function requestSmsAuth(RequestSMSAuthDTO $registerSmsDTO): RequestSmsAuthResponse;

    public function confirmSmsAuth(ConfirmSMSAuthDTO $confirmSmsDTO): ConfirmSmsAuthResponse;

    public function getContact(GetContactDTO $getContactDTO): GetContactResponse;

    public function updateContact(UpdateContactDTO $updateContactDTO): UpdateContactResponse;

    public function getContactBonusesHistory(GetContactBonusesHistoryDTO $contactBonusesHistoryDTO): GetContactBonusesHistoryResponse;

    public function getContactLevelsInfo(GetContactLevelInfoDTO $getContactLevelInfoDTO): GetContactLevelsInfoResponse;

    public function getContactLevels(GetContactLevelsDTO $getLevelsDTO): GetContactLevelsResponse;

    public function getLevels(GetLevelsDTO $getLevelsDTO): GetLevelsResponse;

    public function getContactCards(GetContactCardsDTO $getContactCardsDTO): GetContactCardsResponse;

    public function addCardToContact(AddCardToContactDTO $addCardToContactDTO): AddCardToContactResponse;

    public function getAllFavoriteCategories(GetFavoriteCategoriesDTO $getFavoriteCategoriesDTO): GetFavoriteCategoriesResponse;

    public function getFavoriteCategoryDetail(GetFavoriteCategoryDetailDTO $getFavoriteCategoryDetailDTO): GetFavoriteCategoryDetailResponse;

    public function setUserFavoriteCategory(SetUserFavoriteCategoriesDTO $setUserFavoriteCategoriesDTO): SetUserFavoriteCategoryResponse;

    public function getUserFavoriteCategories(GetUserFavoriteCategoriesDTO $getUserFavoriteCategoriesDTO): GetUserFavoriteCategoriesResponse;
}

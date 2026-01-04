<?php

namespace Infrastructure\Services\Loyalty;

use Infrastructure\Services\Acquiring\Gateways\Exceptions\GatewayException;
use Infrastructure\Services\Loyalty\Gateways\GatewayInterface;
use Infrastructure\Services\Loyalty\RequestDTO\LoyaltyDTOInterface;
use Infrastructure\Services\Loyalty\Responses\ResponseInterface;

readonly class LoyaltyService
{
    public function __construct(
        private GatewayInterface $gateway,
    ) {
    }

    /**
     * @throws GatewayException
     */
    public function requestSmsAuth(LoyaltyDTOInterface $requestSmsLogin): ResponseInterface
    {
        return $this->gateway->requestSmsAuth($requestSmsLogin);
    }

    /**
     * @throws GatewayException
     */
    public function confirmSmsAuth(LoyaltyDTOInterface $confirmSmsLogin): ResponseInterface
    {
        return $this->gateway->confirmSmsAuth($confirmSmsLogin);
    }

    /**
     * @throws GatewayException
     */
    public function getContact(LoyaltyDTOInterface $getContactDTO): ResponseInterface
    {
        return $this->gateway->getContact($getContactDTO);
    }

    /**
     * @throws GatewayException
     */
    public function updateContact(LoyaltyDTOInterface $updateContactDTO): ResponseInterface
    {
        return $this->gateway->updateContact($updateContactDTO);
    }

    /**
     * @throws GatewayException
     */
    public function getContactBonusesHistory(LoyaltyDTOInterface $getContactBonusesHistory): ResponseInterface
    {
        return $this->gateway->getContactBonusesHistory($getContactBonusesHistory);
    }

    /**
     * @throws GatewayException
     */
    public function getContactLevelsInfo(LoyaltyDTOInterface $getContactLevelsInfoDTO): ResponseInterface
    {
        return $this->gateway->getContactLevelsInfo($getContactLevelsInfoDTO);
    }

    /**
     * @throws GatewayException
     */
    public function getContactLevels(LoyaltyDTOInterface $getLevelsDTO): ResponseInterface
    {
        return $this->gateway->getContactLevels($getLevelsDTO);
    }

    public function getLevels(LoyaltyDTOInterface $getLevels): ResponseInterface
    {
        return $this->gateway->getLevels($getLevels);
    }

    /**
     * @throws GatewayException
     */
    public function getContactCards(LoyaltyDTOInterface $getContactCardsDTO): ResponseInterface
    {
        return $this->gateway->getContactCards($getContactCardsDTO);
    }

    /**
     * @throws GatewayException
     */
    public function addCardToContact(LoyaltyDTOInterface $addCardToContactDTO): ResponseInterface
    {
        return $this->gateway->addCardToContact($addCardToContactDTO);
    }

    /**
     * @throws GatewayException
     */
    public function addVirtualCardToContact(LoyaltyDTOInterface $addVirtualCardToContactDTO): ResponseInterface
    {
        return $this->gateway->addVirtualCardToContact($addVirtualCardToContactDTO);
    }

    /**
     * @throws GatewayException
     */
    public function getAllFavoriteCategories(LoyaltyDTOInterface $getFavoriteCategoriesDTO): ResponseInterface
    {
        return $this->gateway->getAllFavoriteCategories($getFavoriteCategoriesDTO);
    }

    /**
     * @throws GatewayException
     */
    public function getFavoriteCategoryDetail(LoyaltyDTOInterface $getFavoriteCategoryDetailDTO): ResponseInterface
    {
        return $this->gateway->getFavoriteCategoryDetail($getFavoriteCategoryDetailDTO);
    }

    /**
     * @throws GatewayException
     */
    public function setUserFavoriteCategory(LoyaltyDTOInterface $setUserFavoriteCategoriesDTO): ResponseInterface
    {
        return $this->gateway->setUserFavoriteCategory($setUserFavoriteCategoriesDTO);
    }

    public function getUserFavoriteCategories(LoyaltyDTOInterface $getUserFavoriteCategoriesDTO): ResponseInterface
    {
        return $this->gateway->getUserFavoriteCategories($getUserFavoriteCategoriesDTO);
    }
}

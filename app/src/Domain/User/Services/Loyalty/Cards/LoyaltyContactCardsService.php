<?php

namespace Domain\User\Services\Loyalty\Cards;

use Domain\User\DTO\LoyaltyCards\AddLoyaltyCardDTO;
use Domain\User\Models\User;
use Infrastructure\Services\Loyalty\Facades\Loyalty;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\Cards\AddCardToContactDTO;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\Cards\AddVirtualCardToContactDTO;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\Cards\GetContactCardsDTO;
use Infrastructure\Services\Loyalty\Responses\Manzana\Cards\AddCardToContactResponse;
use Infrastructure\Services\Loyalty\Responses\Manzana\Cards\AddVirtualCardToContactResponse;
use Infrastructure\Services\Loyalty\Responses\Manzana\Cards\GetContactCardsResponse;

readonly class LoyaltyContactCardsService
{
    public function getLoyaltyContactCards(User $user): GetContactCardsResponse
    {
        $getContactCardsDTO = GetContactCardsDTO::make([
            'sessionId' => $user->loyalty_session_id,
            'contactId' => $user->loyalty_id,
        ]);

        /** @var GetContactCardsResponse $response */
        $response = Loyalty::getContactCards($getContactCardsDTO);

        return $response;
    }

    public function addLoyaltyCardToContact(AddLoyaltyCardDTO $addLoyaltyCardDTO): AddCardToContactResponse
    {
        $user = $addLoyaltyCardDTO->getUser();

        $addLoyaltyCardDTO = AddCardToContactDTO::make([
            'sessionId'     => $user->loyalty_session_id,
            'contactId'     => $user->loyalty_id,
            'cardNumber'    => $addLoyaltyCardDTO->getCardNumber(),
        ]);

        /** @var AddCardToContactResponse $response */
        $response = Loyalty::addCardToContact($addLoyaltyCardDTO);

        return $response;
    }

    public function addVirtualLoyaltyCardToContact(User $user): AddVirtualCardToContactResponse
    {
        $addVirtualLoyaltyCardDTO = AddVirtualCardToContactDTO::make([
            'sessionId'     => $user->loyalty_session_id,
            'contactId'     => $user->loyalty_id,
        ]);

        /** @var AddVirtualCardToContactResponse $response */
        $response = Loyalty::addVirtualCardToContact($addVirtualLoyaltyCardDTO);

        return $response;
    }
}

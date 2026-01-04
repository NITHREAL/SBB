<?php

namespace Domain\User\Services\LoyaltyCards;

use Domain\User\DTO\LoyaltyCards\AddLoyaltyCardDTO;
use Domain\User\Services\Loyalty\Cards\LoyaltyContactCardsService;

readonly class LoyaltyCardService
{
    public function __construct(
        private LoyaltyContactCardsService $loyaltyContactCardsService,
        private LoyaltyCardSelectService $loyaltyCardSelectService,
    ) {
    }

    public function addCard(AddLoyaltyCardDTO $addLoyaltyCardDTO): array
    {
        $this->loyaltyContactCardsService->addLoyaltyCardToContact($addLoyaltyCardDTO);

        return $this->loyaltyCardSelectService->getUserLoyaltyCards($addLoyaltyCardDTO->getUser());
    }
}

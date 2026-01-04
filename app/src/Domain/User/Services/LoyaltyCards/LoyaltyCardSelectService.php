<?php

namespace Domain\User\Services\LoyaltyCards;

use Domain\User\Enums\LoyaltyCard\LoyaltyCardTypeEnum;
use Domain\User\Models\User;
use Domain\User\Services\Loyalty\Cards\LoyaltyContactCardsService;
use Illuminate\Support\Arr;
use Infrastructure\Services\Loyalty\Responses\Manzana\Cards\ContactCard;

readonly class LoyaltyCardSelectService
{
    public function __construct(
        private LoyaltyContactCardsService $loyaltyContactCardsService,
    ) {
    }

    public function getUserLoyaltyCards(User $user): array
    {
        $loyaltyCards = $this->loyaltyContactCardsService->getLoyaltyContactCards($user);

        return $this->getPreparedLoyaltyCards($loyaltyCards->getCards());
    }

    private function getPreparedLoyaltyCards(array $cards): array
    {
        return Arr::map($cards, fn(ContactCard $card) => $this->getPreparedCard($card));
    }

    private function getPreparedCard(ContactCard $card): array
    {
        return [
            'cardNumber'    => $card->getNumber(),
            'cardType'      => $card->isVirtual()
                ? LoyaltyCardTypeEnum::virtual()->value
                : LoyaltyCardTypeEnum::plastic()->value,
        ];
    }
}

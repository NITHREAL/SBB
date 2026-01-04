<?php

namespace Domain\User\Services\Loyalty\Bonuses;

use Domain\User\DTO\Bonuses\BonusesHistoryDTO;
use Infrastructure\Services\Loyalty\Facades\Loyalty;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\Bonuses\GetContactBonusesHistoryDTO;
use Infrastructure\Services\Loyalty\Responses\Manzana\Bonuses\GetContactBonusesHistoryResponse;

readonly class LoyaltyContactBonusesService
{
    public function getLoyaltyContactBonusesHistory(BonusesHistoryDTO $bonusesHistoryDTO): GetContactBonusesHistoryResponse
    {
        $user = $bonusesHistoryDTO->getUser();

        $getContactBonusesHistoryDTO = GetContactBonusesHistoryDTO::make([
            'sessionId' => $user->loyalty_session_id,
            'contactId' => $user->loyalty_id,
            'limit'     => $bonusesHistoryDTO->getLimit(),
            'page'      => $bonusesHistoryDTO->getPage(),
        ]);

        /** @var GetContactBonusesHistoryResponse $response */
        $response = Loyalty::getContactBonusesHistory($getContactBonusesHistoryDTO);

        return $response;
    }
}

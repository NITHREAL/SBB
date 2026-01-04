<?php

namespace Domain\BonusLevel\Services\Loyalty;

use Domain\User\Models\User;
use Infrastructure\Services\Loyalty\Facades\Loyalty;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\Levels\GetLevelsDTO;
use Infrastructure\Services\Loyalty\Responses\Manzana\Levels\Levels\GetLevelsResponse;

readonly class LoyaltyBonusLevelService
{
    public function getLoyaltyLevels(User $user): array
    {
        $getLevelsDTO = GetLevelsDTO::make([
            'sessionId' => $user->loyalty_session_id,
        ]);

        /** @var GetLevelsResponse $response */
        $response = Loyalty::getLevels($getLevelsDTO);

        return $response->getLevels();
    }
}

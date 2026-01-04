<?php

namespace Domain\User\Services\Loyalty\Levels;

use Domain\User\Models\User;
use Infrastructure\Services\Loyalty\Facades\Loyalty;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\Levels\GetContactLevelsDTO;
use Infrastructure\Services\Loyalty\Responses\Manzana\Levels\ContactLevels\GetContactLevelsResponse;
use Infrastructure\Services\Loyalty\Responses\Manzana\Levels\ContactLevels\LevelInfo;

readonly class LoyaltyContactLevelInfoService
{
    public function getContactLevelInfo(User $user): ?LevelInfo
    {
        $getLevelsDTO = GetContactLevelsDTO::make([
            'sessionId' => $user->loyalty_session_id,
            'contactId' => $user->loyalty_id,
        ]);

        /** @var GetContactLevelsResponse $response */
        $response = Loyalty::getContactLevels($getLevelsDTO);

        return $response->getLevelInfo();
    }
}

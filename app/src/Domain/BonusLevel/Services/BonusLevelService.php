<?php

namespace Domain\BonusLevel\Services;

use Domain\BonusLevel\Services\Loyalty\LoyaltyBonusLevelService;
use Domain\User\Models\User;

readonly class BonusLevelService
{
    public function __construct(
        private LoyaltyBonusLevelService $loyaltyBonusLevelService,
        private BonusLevelsChangeService $bonusLevelsChangeService,
    ) {
    }

    public function processBonusLevelsUpdate(): void
    {
        /** @var User $user */
        $user = User::query()->whereNotNull('loyalty_session_id')->orderByDesc('updated_at')->first();

        if ($user) {
            $loyaltyLevelsData = $this->loyaltyBonusLevelService->getLoyaltyLevels($user);

            $this->bonusLevelsChangeService->updateBonusLevels($loyaltyLevelsData);
        }
    }
}

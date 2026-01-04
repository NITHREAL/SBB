<?php

namespace Domain\BonusLevel\Services;

use Domain\BonusLevel\Models\BonusLevel;

readonly class BonusLevelSelectService
{
    public function getBonusLevelByLoyaltyId(string $levelLoyaltyId): ?object
    {
        return BonusLevel::query()->whereLoyaltyId($levelLoyaltyId)->first();
    }
}

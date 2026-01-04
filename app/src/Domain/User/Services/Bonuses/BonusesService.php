<?php

namespace Domain\User\Services\Bonuses;

use Domain\BonusLevel\Services\BonusLevelSelectService;
use Domain\Faq\Helpers\FaqCategoryHelper;
use Domain\Faq\Services\Faq\FaqSelectionService;
use Domain\User\Models\User;
use Domain\User\Services\Loyalty\LoyaltyUserDataService;

readonly class BonusesService
{
    public function __construct(
        private LoyaltyUserDataService $loyaltyUserDataService,
        private FaqSelectionService $faqSelectionService,
        private BonusLevelSelectService $bonusLevelSelectService,
    ) {
    }

    public function getBonusAccountBalance(User $user): array
    {
        $user = $this->loyaltyUserDataService->getUpdatedUserFromLoyalty($user);

        return $this->prepareBonusAccountBalancesData($user);
    }

    public function getBonusAccountBalanceWithFaq(User $user): array
    {
        $user = $this->loyaltyUserDataService->getUpdatedUserFromLoyalty($user);

        return [
            'bonusAccountId'        => $user->loyalty_id,
            'currentBonuses'        => $user->bonuses,
            'bonusLevel'            => $this->prepareBonusLevelData($user),
            'faq'                   => $this->faqSelectionService->getFaqBySlug(FaqCategoryHelper::BONUSES_CATEGORIES_FAQ_SLUG),
        ];
    }

    private function prepareBonusAccountBalancesData(User $user): array
    {
        $preparedData = [
            'bonusAccountId'        => $user->loyalty_id,
            'bonusAccountQrCode'    => $user->bonusAccountQrCode,
            'currentBonuses'        => $user->bonuses,
            'bonusLevel'            => $this->prepareBonusLevelData($user),
            'bonusInfo'             => [],
        ];

        return $preparedData;
    }

    private function prepareBonusLevelData(User $user): array
    {
        $bonusLevel = $this->bonusLevelSelectService->getBonusLevelByLoyaltyId($user->loyalty_level_id);

        $levelNumber = $bonusLevel?->number;

        $title = $levelNumber ? sprintf('Уровень %s', (int) $levelNumber) : null;

        return [
            'title'             => $title,
            'description'       => $bonusLevel?->description,
            'minBonusPoints'    => $bonusLevel?->min_bonus_points,
            'maxBonusPoints'    => $bonusLevel?->max_bonus_points,
            'levelBonusPoints'  => (int) $user->loyalty_level_progression,
        ];
    }
}

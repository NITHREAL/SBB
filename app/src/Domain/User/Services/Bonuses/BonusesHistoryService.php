<?php

namespace Domain\User\Services\Bonuses;

use Domain\User\DTO\Bonuses\BonusesHistoryDTO;
use Domain\User\Services\Loyalty\Bonuses\LoyaltyContactBonusesService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Infrastructure\Services\Loyalty\Responses\Manzana\Bonuses\ContactBonusesHistoryRecord;

readonly class BonusesHistoryService
{
    public function __construct(
        private LoyaltyContactBonusesService $loyaltyContactBonusesService,
    ) {
    }

    public function getBonusAccountHistory(BonusesHistoryDTO $bonusesHistoryDTO): LengthAwarePaginator
    {
        $bonusesHistory = $this->loyaltyContactBonusesService->getLoyaltyContactBonusesHistory($bonusesHistoryDTO);

        $collection = $this->getPreparedBonusesHistory($bonusesHistory->getBonusesHistory());
        $limit = $bonusesHistoryDTO->getLimit();
        $page = $bonusesHistoryDTO->getPage();

        $paginatedItems = $collection->forPage($page, $limit);

        return new LengthAwarePaginator(
            $paginatedItems, $collection->count(),
            $limit,
            $page,
            ['path' => url()->current()]
        );
    }

    private function getPreparedBonusesHistory(Collection $bonusesHistory): Collection
    {
        return $bonusesHistory
            ->map(function (ContactBonusesHistoryRecord $historyRecord) {
                return [
                    'title'         => $historyRecord->getRuleName(),
                    'bonusesCount'  => $historyRecord->getPreparedBonusesCount(),
                ];
            });
    }
}

<?php

namespace Domain\BonusLevel\Services;

use Domain\BonusLevel\Models\BonusLevel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Infrastructure\Services\Loyalty\Responses\Manzana\Levels\Levels\Level;

readonly class BonusLevelsChangeService
{
    public function updateBonusLevels(array $bonusLevelsData): void
    {
        try {
            DB::beginTransaction();

            BonusLevel::truncate();

            foreach ($bonusLevelsData as $bonusLevelDTO) {
                /** @var Level $bonusLevelDTO */

                $this->createBonusLevel($bonusLevelDTO);
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();

            $message = sprintf('Ошибка во время обновления бонусных уровней. Ошибка - [%s]', $exception->getMessage());

            Log::channel('loyalty')->error($message);
        }
    }

    private function createBonusLevel(Level $bonusLevelDTO): void
    {
        $bonusLevel = new BonusLevel();

        $bonusLevel->fill([
            'loyalty_id'        => $bonusLevelDTO->getId(),
            'number'            => $bonusLevelDTO->getExternalId(),
            'title'             => $bonusLevelDTO->getName(),
            'description'       => $bonusLevelDTO->getDescription(),
            'min_bonus_points'  => $bonusLevelDTO->getMinValue(),
            'max_bonus_points'  => $bonusLevelDTO->getMaxValue(),
        ]);

        $bonusLevel->save();
    }
}

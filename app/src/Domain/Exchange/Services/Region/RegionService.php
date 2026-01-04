<?php

declare(strict_types=1);

namespace Domain\Exchange\Services\Region;

use Domain\City\Models\Region;
use Domain\Exchange\DTO\RegionDTO;

/**
 * Сервис для обработки регионов при обмене данными из 1С.
 */
class RegionService
{
    /**
     * @param RegionDTO $regionDTO ДТО с данными региона.
     * @return Region Возвращаем модель Region.
     */
    public function exchange(RegionDTO $regionDTO): Region
    {
        $searchAttributes = $this->prepareSearchAttributes($regionDTO);

        $attributes = $this->prepareAttributes($regionDTO);

        return Region::updateOrCreate($searchAttributes, $attributes);
    }

    /**
     * @param RegionDTO $regionDTO ДТО с данными региона.
     * @return array Массив уникальных атрибутов.
     */
    protected function prepareSearchAttributes(RegionDTO $regionDTO): array
    {
        return [
            'system_id' => $regionDTO->getSystemId(),
        ];
    }

    /**
     * @param RegionDTO $regionDTO ДТО с данными региона.
     * @return array Массив атрибутов.
     */
    protected function prepareAttributes(RegionDTO $regionDTO): array
    {
        return $regionDTO->toArray();
    }
}

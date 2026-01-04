<?php

declare(strict_types=1);

namespace Domain\Exchange\Services\City;

use Domain\City\Models\City;
use Domain\City\Models\Region;
use Domain\Exchange\DTO\CityDTO;
use Domain\Exchange\Jobs\FetchCityCoordinates;
use InvalidArgumentException;

/**
 * Сервис для обработки городов при обмене данными из 1С.
 */
class CityService
{
    /**
     * @param CityDTO $cityDTO
     * @return City
     * @throws InvalidArgumentException
     */
    public function exchange(CityDTO $cityDTO): City
    {
        $region = $this->findRegionOrFail($cityDTO->getRegionSystemId());

        $city = City::firstOrNew([
            'system_id' => $cityDTO->getSystemId(),
        ]);

        $city->fill($cityDTO->toArray());

        $city->region()->associate($region);

        $city->save();

        FetchCityCoordinates::dispatch($city);

        return $city;
    }

    /**
     * @param string $regionSystemId
     * @return Region
     * @throws InvalidArgumentException
     */
    private function findRegionOrFail(string $regionSystemId): Region
    {
        $region = Region::where('system_id', $regionSystemId)->first();
        if (!$region) {
            throw new InvalidArgumentException("Регион с system_id '{$regionSystemId}' не найден.");
        }
        return $region;
    }
}

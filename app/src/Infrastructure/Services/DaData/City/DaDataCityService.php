<?php

namespace Infrastructure\Services\DaData\City;

use Domain\City\Models\City;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Infrastructure\Services\DaData\DaData;

class DaDataCityService
{
    private const AVAILABLE_CITY_PREFIXES = ['г', 'пгт', 'п', 'с', 'д'];

    private int $citiesSelectCount = 10;

    private static string $cityCacheKey = 'city_fias_id';
    private static int $cityCacheTtl = 86400;

    public function getCitiesData(string $query): array
    {
        $citiesData = DaData::getCitiesByQuery($query, $this->citiesSelectCount);

        return $this->getPreparedCitiesData($citiesData);
    }

    public function getCityFromCache(string $fiasId): ?City
    {
        return Cache::remember(
            sprintf('%s_%s', self::$cityCacheKey, $fiasId),
            self::$cityCacheTtl,
            fn() => City::getOneByFiasId($fiasId),
        );
    }

    private function getPreparedCitiesData(array $citiesData): array
    {
        $result = [];

        foreach ($citiesData as $item) {
            $item['data']['city_id'] = $this->getItemCityId($item);

            $cityName = $item['data']['city'] ?? $item['data']['settlement'];

            if ($this->isCityDataCorrect($item, $result)) {
                $result[$cityName] = $item;
            }
        }

        return array_values($result);
    }

    private function getItemCityId(array $item): ?int
    {
        $cities = City::all();

        $itemCityFiasId = $item['data']['city_fias_id'] ?? $item['data']['settlement_fias_id'];

        return $cities->where('fias_id', $itemCityFiasId)->first()?->id;
    }

    private function isCityDataCorrect(array $item, array $preparedCities): bool
    {
        $itemData = $item['data'];

        $type = $itemData['city_type'] ?? $itemData['settlement_type'];
        $cityId = $itemData['city_id'];
        $cityName = $itemData['city'] ?? $itemData['settlement'];

        return in_array($type, self::AVAILABLE_CITY_PREFIXES)
            && empty($cityId) === false
            && Arr::has($preparedCities, $cityName) === false;
    }
}

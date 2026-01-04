<?php

namespace Infrastructure\Services\DaData;

use Domain\City\Models\City;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Infrastructure\Services\DaData\DTO\DaDataPromptDTO;
use Infrastructure\Services\DaData\Exceptions\DaDataException;
use MoveMoveIo\DaData\Enums\Language;
use MoveMoveIo\DaData\Facades\DaDataAddress;
use RuntimeException;

class DaData
{
    private static string $prefix = 'dadata_';

    private static int $gelolocateRadius = 500; // в метрах

    public static function getCitiesByQuery(
        string $query,
        int $count = 10,
    ): array {
        $locations = [];
        $key = self::getAddressCacheKey($locations, $query, $count);

        $promptDTO = new DaDataPromptDTO(
            $query,
            $count,
            $locations,
            ['value' => 'city'],
            ['value' => 'settlement']
        );

        return Cache::remember(
            $key,
            config('dadata.ttl'),
            fn() => self::getAddressesFromDaData($promptDTO),
        );
    }

    public static function findManyByAddress(
        City $city,
        string $address,
        int $count = 10,
    ): ?array {
        $locations = self::prepareCityLocations($city);
        $key = self::getAddressCacheKey($locations, $address, $count);

        $promptDTO = new DaDataPromptDTO(
            $address,
            $count,
            $locations,
            ['value' => 'city'],
            ['value' => 'house']
        );

        return Cache::remember(
            $key,
            config('dadata.ttl'),
            fn() => self::getAddressesFromDaData($promptDTO),
        );
    }

    public static function findAddressesByRegions(
        string $address,
        array $regionFiasIds,
        int $count = 10,
    ): array {
        $locations = self::prepareRegionLocations($regionFiasIds);
        $cacheKey = self::$prefix . md5(json_encode($locations) . $address . $count);

        $promptDTO = new DaDataPromptDTO(
            $address,
            $count,
            $locations,
            ['value' => 'city'],
            ['value' => 'house']
        );

        return Cache::remember(
            $cacheKey,
            config('dadata.ttl'),
            fn() => self::getAddressesFromDaData($promptDTO),
        );
    }

    /**
     * @throws DaDataException
     */
    public static function geolocate(float $latitude, float $longitude, int $count = 10): array
    {
        $key = self::$prefix . md5($latitude . $longitude);
        $suggestions = Cache::get($key);

        if ($suggestions) {
            return json_decode($suggestions, true);
        }

        try {
            $suggestions = DaDataAddress::geolocate(
                $latitude,
                $longitude,
                $count,
                self::$gelolocateRadius,
                Language::RU
            );
        } catch (RuntimeException $e) {
            throw new DaDataException($e);
        }

        Cache::put($key, json_encode($suggestions['suggestions']), config('dadata.ttl'));

        return $suggestions['suggestions'];
    }

    private static function getAddressesFromDaData(DaDataPromptDTO $promptDTO): array
    {
        /** @var array $dadataResult */
        $dadataResult = DaDataAddress::prompt(
            $promptDTO->getAddress(),
            $promptDTO->getCount(),
            Language::RU,
            $promptDTO->getLocations(),
            $promptDTO->getLocationsGeo(),
            $promptDTO->getLocationsBoost(),
            $promptDTO->getFromBound(),
            $promptDTO->getToBound(),
        );

        return Arr::get($dadataResult, 'suggestions', []);
    }

    private static function prepareCityLocations(City $city): array
    {
        $locations = [];

        $locations[]['region_fias_id'] = $city->region_fias_id;
        $locations[]['city_fias_id'] = $city->fias_id;

        return $locations;
    }

    private static function prepareRegionLocations(array $regionFiasIds): array
    {
        $locations = [];

        foreach ($regionFiasIds as $regionFiasId) {
            $locations[]['region_fias_id'] = $regionFiasId;
        }

        return $locations;
    }

    private static function getAddressCacheKey(
        array $locations,
        string $address,
        int $count,
    ): string {
        return self::$prefix . md5(json_encode($locations) . $address . $count);
    }

    /**
     * Получает координаты (широту и долготу) по fias_id.
     *
     * @param string $fiasId
     * @return array|null Массив с ключами 'latitude' и 'longitude', или null, если данные не найдены.
     * @throws DaDataException
     */
    public static function getCoordinatesByFiasId(string $fiasId): ?array
    {
        $cacheKey = self::$prefix . 'coordinates_' . md5($fiasId);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $result = DaDataAddress::fias($fiasId);

            $coordinates = Arr::get($result, 'location.geo');

            if ($coordinates) {
                $data = [
                    'latitude' => Arr::get($coordinates, 'latitude'),
                    'longitude' => Arr::get($coordinates, 'longitude'),
                ];

                Cache::put($cacheKey, $data, config('dadata.ttl'));

                return $data;
            }
        } catch (RuntimeException $e) {
            throw new DaDataException($e->getMessage());
        }

        return null;
    }
}

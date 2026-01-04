<?php

namespace Infrastructure\Services\Buyer\Components\City\Helper;

use Domain\City\Models\City;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Infrastructure\Services\DaData\Address\DaDataAddressService;
use Infrastructure\Services\DaData\City\DaDataCityService;
use Infrastructure\Services\DaData\Exceptions\DaDataException;
use Stevebauman\Location\Facades\Location;

class CityEntityHelper
{
    private const DEFAULT_CITY_ID = 2; // Кемерово

    private int $defaultCityCacheTtl = 10800;

    private string $defaultCityKey = 'default_city';

    private int $fiasIdByIpCacheTtl = 259200;

    private string $fiasIdByIpCacheKeyPrefix = 'fiasId_by_ip';

    public function __construct(
        private readonly DaDataAddressService $dataAddressService,
        private readonly DaDataCityService $dataCityService,
    ) {
    }

    public function getDefaultCity(): ?City
    {
        return Cache::remember(
            $this->defaultCityKey,
            $this->defaultCityCacheTtl,
            function () {
                return City::query()
                    ->orderBy('sort')
                    ->whereId(self::DEFAULT_CITY_ID)
                    ->first();
            }
        );
    }

    private function getCityByIp(string $ip = null): ?City
    {
        $city = null;

        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            $ip = request()->getClientIp();
        }

        $fiasId = Cache::remember(
            $this->getFiasIdByIpCacheKey($ip),
            $this->fiasIdByIpCacheTtl,
            fn() => $this->getCityFiasIdByIp($ip),
        );

        if ($fiasId) {
            $city = $this->dataCityService->getCityFromCache($fiasId);
        }

        return $city;
    }

    /**
     * @throws DaDataException
     */
    private function getCityFiasIdByIp(string $ip): ?string
    {
        $fiasId = null;
        $position = Location::get($ip);

        if ($position) {
            $fiasId = $this->getCityFiasIdByCoordinates($position->latitude, $position->longitude);
        }

        return $fiasId;
    }

    /**
     * @throws DaDataException
     */
    private function getCityFiasIdByCoordinates(string $latitude, string $longitude): ?string
    {
        $locationData = $this->dataAddressService->getAddressByCoordinates(
            $latitude,
            $longitude,
        );

        return Arr::get($locationData, 'fias_id');
    }

    private function getFiasIdByIpCacheKey(string $ip): string
    {
        return sprintf('%s_%s', $this->fiasIdByIpCacheKeyPrefix, $ip);
    }
}

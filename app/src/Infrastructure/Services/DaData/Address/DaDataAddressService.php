<?php

namespace Infrastructure\Services\DaData\Address;

use Domain\City\Models\City;
use Domain\City\Services\CityService;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Infrastructure\Services\DaData\DaData;
use Infrastructure\Services\DaData\Exceptions\DaDataException;

class DaDataAddressService
{
    private Collection $cities;

    private int $requestedAddressesCount = 20;

    public function __construct(
        private readonly CityService $cityService,
    ) {
        $this->cities = $this->cityService->getActualCities();
    }

    public function getAddressesDataByQuery(
        string $query,
        bool $uniqueStreets,
    ): array {
        $regionFiasIds = $this->getAllRegionFiasIds();

        $suggestions = DaData::findAddressesByRegions($query, $regionFiasIds, $this->requestedAddressesCount);

        return $this->prepareAddressesData($suggestions, $uniqueStreets);
    }

    /**
     * @throws DaDataException
     */
    public function getAddressByCoordinates(string $latitude, string $longitude): array
    {
        try {
            $suggestion = DaData::geolocate($latitude, $longitude, 1);
        } catch (Exception $e) {
            throw new DaDataException($e, 'Не удалось определить адрес доставки', 400);
        }

        if (empty($suggestion)) {
            throw new DaDataException('Доставка на выбранный адрес не доступна', 400);
        }

        $data = $this->prepareDaDataValue(Arr::first($suggestion));

        $city = $this->getNearestCity($data);

        if (empty($city)) {
            throw new DaDataException('Доставка на выбранный адрес не доступна', 400);
        }

        $data['city_id'] = $city?->id;

        return $data;
    }

    public function getOneAddressDataByQuery(City $city, string $query): ?array
    {
        $suggestion = DaData::findManyByAddress($city, $query, 1);

        return Arr::first(
            $this->prepareAddressesData($suggestion, true, $city),
        );
    }

    private function prepareAddressesData(
        array $suggestions,
        bool $uniqueStreets,
        ?City $city = null,
    ): array {
        $result = [];

        $existedStreets = [];

        foreach ($suggestions as $item) {
            $lat = $item['data']['geo_lat'];
            $lon = $item['data']['geo_lon'];

            $itemData = $this->prepareDaDataValue($item);

            if (Arr::where($result, fn ($res) => $res['value'] === $itemData['value'])) {
                continue;
            }

            $city = $city ?? $this->getNearestCity($itemData);

            $street = Arr::get($itemData, 'value');

            if ($uniqueStreets && Arr::has($existedStreets, $street)) {
                continue;
            } else {
                $existedStreets[$street] = $street;
            }

            $result[] = array_merge(
                $itemData,
                [
                    'city_id'   => $city?->id,
                    'latitude'  => $lat,
                    'longitude' => $lon,
                ],
            );
        }

        return $result;
    }

    private function prepareDaDataValue(array $suggestion): array
    {
        $data = Arr::get($suggestion, 'data');

        $location = Arr::get($data, 'settlement_with_type') ?? Arr::get($data, 'city_with_type');
        $street = Arr::get($data, 'street_with_type');
        $house = sprintf(
            '%s %s %s %s',
            Arr::get($data, 'house_type'),
            Arr::get($data, 'house'),
            Arr::get($data, 'block_type'),
            Arr::get($data, 'block'),
        );

        return array_map(
            fn ($item) => trim($item),
            [
                'value'             => implode(', ', array_filter([$location, $street, $house])),
                'location'          => $location,
                'street'            => $street,
                'house'             => $house,
                'latitude'          => Arr::get($data, 'geo_lat'),
                'longitude'         => Arr::get($data, 'geo_lon'),
                'fias_id'           => Arr::get($data, 'city_fias_id') ?? Arr::get($data, 'settlement_fias_id'),
                'region_fias_id'    => Arr::get($data, 'region_fias_id'),
            ]
        );
    }

    private function getNearestCity(array $data): ?object
    {
        $city = $this->cities->where('fias_id', Arr::get($data, 'fias_id'))->first();

        if (empty($city)) {
            $city = $this->cities->where('region_fias_id', Arr::get($data, 'region_fias_id'))->first();
        }

        return $city;
    }

    private function getAllRegionFiasIds(): array
    {
        return $this->cities->unique('region_fias_id')->pluck('region_fias_id')->toArray();
    }
}

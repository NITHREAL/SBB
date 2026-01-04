<?php

namespace Domain\City\Services;

use Domain\City\Models\City;
use Domain\Store\Models\Store;
use Illuminate\Support\Collection;

class CityService
{
    public function getAllCities(): Collection
    {
        return City::query()
            ->whereHas('stores')
            ->orderBy('sort')
            ->get();
    }

    public function getActualCities(): Collection
    {
        $cities = City::all();

        if ($cities->isNotEmpty()) {
            $stores = Store::query()
                ->whereActive()
                ->whereCities($cities->pluck('id')->toArray())
                ->get();

            $cities = $cities
                ->map(function ($city) use ($stores) {
                    $cityStoresCount = $stores->where('city_id', $city->id)->count();

                    $city->storesCount = $cityStoresCount;

                    return $city;
                })
                ->filter(fn($city) => $city->storesCount > 0)
                ->sortByDesc('storesCount');
        }

        return $cities;
    }
}

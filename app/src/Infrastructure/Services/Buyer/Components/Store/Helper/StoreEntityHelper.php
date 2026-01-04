<?php

namespace Infrastructure\Services\Buyer\Components\Store\Helper;

use Domain\City\Models\City;
use Domain\Store\Models\Store;
use Domain\User\Services\Store\UserStoreService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class StoreEntityHelper
{
    private int $defaultStoreCacheTtl = 259200;

    private string $defaultStoreCacheKey = 'default_store';

    public function __construct(
        private readonly UserStoreService $userStoreService,
    ) {
    }

    public function getDefaultUserStore(int $cityId, ?int $userId): ?Store
    {
        if ($userId) {
            $userStores = $this->userStoreService->getUserStoresByCity($userId, $cityId);

            $store = $userStores->where('chosen', true)->first();

            if (empty($store)) {
                $store = $this->getCityStores($cityId)->first();
            }
        }

        if (empty($store)) {
            $store = $this->getDefaultStoreByCity($cityId);
        }

        return $store;
    }

    public function getDefaultStoreByCity(int $cityId): ?Store
    {
        return Cache::remember(
            $this->defaultStoreCacheKey,
            $this->defaultStoreCacheTtl,
            fn() => $this->getDefaultStore($cityId),
        );
    }

    public function getClosestStoreByCoords(
        City $city,
        string $latitude,
        string $longitude,
    ): ?Store {
        $stores = Store::query()
            ->byCityCollection($city->id)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        foreach ($stores as $store) {
            $x = $latitude - $store->latitude;
            $y = $longitude - $store->longitude;

            $distances[$store->id] = sqrt(($x**2) + ($y**2));
        }

        asort($distances);

        /** @var Store|null $closest */
        $closest = $stores->find(key($distances));

        return $closest;
    }

    private function getDefaultStore(int $cityId): ?object
    {
        $store = Store::query()
            ->whereAvailableProductsExists()
            ->whereCity($cityId)
            ->where('stores.active', true)
            ->orderBy('sort')
            ->first();

        if (empty($store)) {
            $store = Store::query()
                ->whereCity($cityId)
                ->where('stores.active', true)
                ->orderBy('sort')
                ->first();
        }

        return $store;
    }

    private function getCityStores(int $cityId): Collection
    {
        return Cache::remember(
            $this->defaultStoreCacheTtl,
            sprintf('city_%s_stores', $cityId),
            fn() => Store::query()->byCityCollection($cityId)->whereActive()->get(),
        );
    }
}

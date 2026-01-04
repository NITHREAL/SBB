<?php

namespace Domain\Store\Services;

use Domain\Store\Models\Store;
use Illuminate\Support\Collection;

readonly class StoreService
{
    public function getStores(): Collection
    {
        return Store::query()
            ->baseQuery()
            ->whereActive()
            ->whereAvailableProductsExists()
            ->whereNotNull('stores.city_id')
            ->get();
    }

    public function geStoresByCity(int $cityId): Collection
    {
        return Store::query()
            ->baseQuery()
            ->byCityCollection($cityId)
            ->get();
    }

    public function getStoreBySlug(string $slug): object
    {
        return Store::query()
            ->baseQuery()
            ->whereActive()
            ->whereSlug($slug)
            ->firstOrFail();
    }
}

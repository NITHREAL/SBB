<?php

namespace Domain\Store\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;
use Infrastructure\Eloquent\Builder\BaseQueryBuilder;

/**
 * @method static self baseQuery()
 * @method static self byCityCollection(int $cityId)
 * @method static self byUserCollection(int $userId)
 * @method static self whereCity(int $cityId)
 * @method static self whereCities(array $cityIds)
 * @method static self whereStore1cId(string $store1cId)
 * @method static self whereActive()
 * @method static self whereSlug(string $slug)
 * @method static self whereAvailableProductsExists()
 */
class StoreQueryBuilder extends BaseQueryBuilder
{
    public function baseQuery(): self
    {
        return $this
            ->select([
                'stores.*',
                'cities.title as cityTitle',
            ])
            ->leftJoin('cities', 'stores.city_id', '=', 'cities.id')
            ->orderBy('stores.sort', 'ASC');
    }

    public function byCityCollection(int $cityId): Builder
    {
        return $this
            ->whereActive()
            ->whereCity($cityId);
    }

    public function byUserCollection(int $userId): self
    {
        return $this
            ->addSelect(['favorite_stores.chosen'])
            ->leftJoin('favorite_stores', 'favorite_stores.store_id', '=', 'stores.id')
            ->where('favorite_stores.user_id', $userId)
            ->whereActive()
            ->orderBy('favorite_stores.created_at');
    }

    public function whereCity(int $cityId): Builder
    {
        return $this->where('stores.city_id', $cityId);
    }

    public function whereCities(array $cityIds): self
    {
        return $this->whereIn('stores.city_id', $cityIds);
    }

    public function whereStore1cId(string $store1cId): self
    {
        return $this->where('stores.system_id', $store1cId);
    }

    public function whereActive(): self
    {
        return $this->where('stores.active', true);
    }

    public function whereSlug(string $slug): self
    {
        return $this->where('stores.slug', $slug);
    }

    public function whereAvailableProductsExists(): self
    {
        return $this
            ->whereHas('leftovers', function ($query) {
                return $query
                    ->where('product_store.count', '>', 0)
                    ->where('product_store.price', '>', 0);
            });
    }
}

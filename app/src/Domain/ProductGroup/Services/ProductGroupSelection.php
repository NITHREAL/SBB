<?php

namespace Domain\ProductGroup\Services;

use Domain\ProductGroup\Models\ProductGroup;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ProductGroupSelection
{
    private const PRODUCT_GROUP_CACHE_KEY_PREFIX = 'category';

    private const PRODUCT_GROUP_CACHE_TTL = 10800;

    public static function getProductGroupBySlug(string $slug): ProductGroup
    {
        return Cache::remember(
            self::getProductGroupCacheKey($slug),
            self::PRODUCT_GROUP_CACHE_TTL,
            fn() => ProductGroup::query()->whereSlug($slug)->firstOrFail(),
        );
    }

    public static function getProductGroupsByProductIds(array $productIds): Collection
    {
        return ProductGroup::query()
            ->select([
                'groups.id as groupId',
                'groups.active as active',
                'groups.slug as slug',
                'gp.product_id as productId',
                'gp.sort as sort',
            ])
            ->leftJoin('group_products as gp', 'gp.group_id', '=', 'groups.id')
            ->whereIn('gp.product_id', $productIds)
            ->orderBy('groups.sort')
            ->get();
    }

    private static function getProductGroupCacheKey(string $slug): string
    {
        return sprintf('%s_%s', self::PRODUCT_GROUP_CACHE_KEY_PREFIX, $slug);
    }
}

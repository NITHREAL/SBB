<?php

namespace Domain\Product\Services\Category;

use Domain\Product\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CategorySelection
{
    private const CATEGORY_CACHE_KEY_PREFIX = 'category';

    private const CATEGORY_CACHE_TTL = 10800;

    public static function getCategoryBySlug(string $slug): Category
    {
        return Cache::remember(
            self::getCategoryCacheKey($slug),
            self::CATEGORY_CACHE_TTL,
            fn() => Category::query()->whereSlug($slug)->firstOrFail(),
        );
    }

    public static function getSpecialCategoriesByProducts(array $product1cIds): Collection
    {
        return Category::query()
            ->specialCategoriesByProducts($product1cIds)
            ->get();
    }

    private static function getCategoryCacheKey(string $slug): string
    {
        return sprintf('%s_%s', self::CATEGORY_CACHE_KEY_PREFIX, $slug);
    }
}

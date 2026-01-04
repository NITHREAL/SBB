<?php

namespace Domain\Farmer\Services;

use Domain\Farmer\Models\Farmer;
use Domain\Product\Models\Product;
use Domain\Product\Services\ProductCollectionService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class FarmerProductsService
{
    private const DEFAULT_FARMER_PRODUCTS_LIMIT = 4;

    private const FARMER_PRODUCT_CACHE_KEY_PREFIX = 'farmer_products';

    private const FARMER_PRODUCT_CACHE_TTL = 10800;

    public function __construct(
        private readonly ProductCollectionService $productCollectionService,
    ) {
    }

    public function getFarmerProducts(
        Farmer $farmer,
        ?int $limit,
        ?string $store1cId,
    ): object {
        $limit = $limit ?? self::DEFAULT_FARMER_PRODUCTS_LIMIT;

        return Cache::remember(
            $this->getFarmerProductsCacheKey($farmer->slug, $store1cId),
            self::FARMER_PRODUCT_CACHE_TTL,
            fn() => $this->getFarmerProductsData($farmer, $limit, $store1cId),
        );
    }

    private function getFarmerProductsData(
        Farmer $farmer,
        int $limit,
        string $store1cId,
    ): object {
        $products = $this->getProducts(
            $farmer,
            $store1cId,
        );

        $categories = collect();

        $products
             ->groupBy('categoryId')
             ->map(function ($categoryProducts) use ($limit, $categories) {
                $categories->push([
                    'id' => $categoryProducts[0]->categoryId,
                    'slug' => $categoryProducts[0]->categorySlug,
                    'title' => $categoryProducts[0]->categoryTitle,
                    'products' => $categoryProducts
                        ->sortBy('sort')
                        ->take($limit),
                ]);
            });

        return $categories;
    }

    private function getProducts(
        Farmer $farmer,
        string $store1cId,
    ): Collection {
        $products = Product::query()
            ->farmerCategoriesQuery($store1cId)
            ->where('farmers.id', $farmer->id)
            ->get();

        return $this->productCollectionService->getPreparedProductsCollection($products, false);
    }

    private function getFarmerProductsCacheKey(string $farmerSlug, string $store1cId): string
    {
        return sprintf(
            '%s_%s_%s',
            self::FARMER_PRODUCT_CACHE_KEY_PREFIX,
            $farmerSlug,
            $store1cId
        );
    }
}

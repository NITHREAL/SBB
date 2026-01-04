<?php

namespace Domain\Product\Services\Catalog;

use Domain\Product\Models\Category;
use Domain\Product\Models\Product;
use Domain\Product\Services\Category\CategorySelection;
use Domain\Product\Services\ProductCollectionService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Infrastructure\Services\Buyer\Facades\BuyerStore;

class CatalogPreviewService
{
    private const CHILD_CATEGORIES_CACHE_KEY_PREFIX = 'categories_childs';

    private const CHILD_CATEGORIES_CACHE_TTL = 10800;

    private const DEFAULT_SUBCATEGORY_PRODUCTS_LIMIT = 4;

    public function __construct(
        private readonly ProductCollectionService $productCollectionService,
    ) {
    }

    public function getCatalogPreview(
        string  $slug,
        ?int    $limit,
    ): array {
        $limit = $limit ?? self::DEFAULT_SUBCATEGORY_PRODUCTS_LIMIT;

        $category = CategorySelection::getCategoryBySlug($slug);
        $childs = $this->getCategoryChildsData($category, $limit);

        return compact('category', 'childs');
    }

    private function getCategoryChildsData(
        Category $category,
        int      $limit,
    ): Collection {
        $store1cId = BuyerStore::getOneCId();

        return Cache::remember(
            $this->getChildCategoriesCacheKey($category->slug, $store1cId),
            self::CHILD_CATEGORIES_CACHE_TTL,
            fn() => $this->getCategoryChilds($category->getAttribute('system_id'), $limit, $store1cId),
        );
    }

    private function getCategoryChilds(
        string $category1cId,
        int $limit,
        string $store1cId,
    ): Collection {
        $categories = Category::query()
            ->baseQuery()
            ->whereParent($category1cId)
            ->active()
            ->with(['children' => fn($q) => $q->active()])
            ->get();

        $products = $this->getCategoryProducts(
            $categories->pluck('id')->toArray(),
            $store1cId,
        );

        return $categories
            ->map(function ($item) use ($limit, $products) {
                $item->setAttribute('is_has_childs', $item->children->isNotEmpty());
                $item->setAttribute(
                    'products',
                    $products
                        ->where('categoryId', $item->id)
                        ->sortBy('sort')
                        ->take($limit)
                );

                return $item;
            })
            ->filter(fn($item) => $item->products->isNotEmpty());
    }

    private function getCategoryProducts(
        array $categoryIds,
        string $store1cId,
    ): Collection {
        $products = Product::query()
            ->categoryQuery($store1cId)
            ->whereCategories($categoryIds)
            ->get();

        return $this->productCollectionService->getPreparedProductsCollection($products);
    }

    private function getChildCategoriesCacheKey(string $slug, string $store1cId): string
    {
        return sprintf(
            '%s_%s_store_1c_id_%s',
            self::CHILD_CATEGORIES_CACHE_KEY_PREFIX,
            $slug,
            $store1cId,
        );
    }
}

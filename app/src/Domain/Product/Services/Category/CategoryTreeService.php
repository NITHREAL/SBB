<?php

namespace Domain\Product\Services\Category;

use Domain\Image\Helpers\ImagePropertiesHelper;
use Domain\Image\Models\Attachment;
use Domain\Image\Services\ImageSelection;
use Domain\Product\Models\Category;
use Domain\Product\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Infrastructure\Services\Buyer\Facades\BuyerStore;

class CategoryTreeService
{
    private string $categoryTreeCacheKeyPrefix = 'categories_store';

    private int $categoryTreeCacheTtl = 18000;

    private int $categoryTreeLevel = 4;

    public function getCategoryTree(string $store1cId = null): Collection
    {
        $store1cId = $store1cId ?? BuyerStore::getSelectedStore()->getAttribute('system_id');

        return Cache::remember(
            $this->getCategoryTreeCacheKey($store1cId),
            $this->categoryTreeCacheTtl,
            fn() => $this->getCategoryTreeData(),
        );
    }

    private function getCategoryTreeData(): Collection
    {
        $categories = Category::query()
            ->categoryTreeQuery($this->categoryTreeLevel)
//            ->whereNotNull('categories.parent_system_id')
            ->active()
            ->get();

        $categoriesIds = $categories->pluck('id')->toArray();

        $images = ImageSelection::getCategoriesImages(
            $categoriesIds,
        );

        $products = $this->getCategoriesProducts($categoriesIds);

        return $categories
            ->whereNull('parent_system_id')
            ->map(function ($item) use ($products, $categories, $images) {
                return $this->getPreparedCategoriesItemData($item, $categories, $images, $products);
            })
            ->filter(fn($item) => count($item->childs) > 0);
    }

    private function getPreparedCategoriesItemData(
        Category $item,
        Collection $childCategories,
        Collection $images,
        Collection $products,
    ): object {
        $image = $this->getCategoryImage($images, $item);

        if ($image) {
            $item = ImagePropertiesHelper::setImageProperties($item, $image);
        }

        $childs = $childCategories
            ->where('parent_system_id', $item->getAttribute('system_id'))
            ->filter(fn($child) => $products->where('categoryId', '=', $child->id)->isNotEmpty())
            ->map(function ($child) use ($item, $images) {
                $child->parent_id = $item->id;

                $image = $this->getCategoryImage($images, $item);

                if ($image) {
                    $child = ImagePropertiesHelper::setImageProperties($child, $image);
                }

                return $child;
            });

        $item->setAttribute('childs', $childs);

        return $item;
    }

    private function getCategoriesProducts(array $categoriesIds): Collection
    {
        return Product::query()
            ->select([
                'products.id',
                'categories.id as categoryId',
            ])
            ->whereCategories($categoriesIds)
            ->where('products.active', true)
            ->get();
    }

    private function getCategoryImage(Collection $images, Category $category): ?Attachment
    {
        return $images->where('owner_id', $category->id)->sortByDesc('main')->first();
    }

    private function getCategoryTreeCacheKey(string $store1cId): string
    {
        return sprintf('%s_%s', $this->categoryTreeCacheKeyPrefix, $store1cId);
    }
}

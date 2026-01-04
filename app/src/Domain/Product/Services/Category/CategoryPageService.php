<?php

namespace Domain\Product\Services\Category;

use Domain\Product\Models\Category;
use Illuminate\Support\Facades\Cache;

class CategoryPageService
{
    private string $categoryCacheKeyPrefix = 'category_page';

    private int $categoryCacheTtl = 18000;

    public function getCategory(string $slug): object
    {
        return Cache::remember(
            $this->getCategoryCacheKey($slug),
            $this->categoryCacheTtl,
            fn() => $this->getCategoryData($slug),
        );
    }

    public function getCategoryData(string $slug): object
    {
        $category = Category::query()
            ->whereSlug($slug)
            ->whereActive()
            ->whereHaveActiveChildren()
            ->with([
                'children' => function ($query) {
                    return $query
                        ->where('active', true)
                        ->with(['children' => fn($query) => $query->where('active', true)]);
                },
            ])
            ->firstOrFail();

        return $this->getPreparedCategory($category);
    }

    private function getPreparedCategory(object $category): object
    {
        $children = $category
            ->children
            ->map(function ($child) {
                $child->setAttribute('is_has_childs', $child->children->isNotEmpty());

                return $child;
            });

        $category->setAttribute('childs', $children);

        return $category;
    }

    private function getCategoryCacheKey(string $slug): string
    {
        return sprintf('%s_%s', $this->categoryCacheKeyPrefix, $slug);
    }
}

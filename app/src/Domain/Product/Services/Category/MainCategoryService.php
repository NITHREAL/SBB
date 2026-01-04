<?php

namespace Domain\Product\Services\Category;

use Domain\Image\Helpers\ImagePropertiesHelper;
use Domain\Image\Services\ImageSelection;
use Domain\Product\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class MainCategoryService
{
    private string $mainCategoriesCacheKey = 'main_categories';

    private int $mainCategoriesCacheTtl = 18000;

    public function getMainCategories(): Collection
    {
        return Cache::remember(
            $this->mainCategoriesCacheKey,
            $this->mainCategoriesCacheTtl,
            fn() => $this->getMainCategoriesData(),
        );
    }

    private function getMainCategoriesData(): Collection
    {
        $categories = Category::query()
            ->mainCategoriesQuery()
            ->with(['children' => fn($query) => $query->where('active', true)])
            ->get();

        return $this->getPreparedCategories($categories);
    }

    private function getPreparedCategories(Collection $categories): Collection
    {
        $categoriesIds = $categories->pluck('id')->toArray();

        $images = ImageSelection::getCategoriesImages(
            $categoriesIds,
        );

        return $categories->map(fn(object $category) => $this->getPreparedCategory($category, $images));
    }

    private function getPreparedCategory(object $category, Collection $images): object
    {
        if ($image = $images->where('owner_id', $category->id)->sortByDesc('main')->first()) {
            $category = ImagePropertiesHelper::setImageProperties($category, $image);
        }

        $category->setAttribute('is_has_childs', $category->children->isNotEmpty());

        return $category;
    }
}

<?php

namespace Domain\Product\Services\Category;

use Domain\Image\Helpers\ImagePropertiesHelper;
use Domain\Image\Services\ImageSelection;
use Domain\Product\Models\Category;
use Illuminate\Support\Collection;

readonly class CategorySelectService
{
    public function getSubcategories(): Collection
    {
        $categories = Category::query()
            ->whereActive()
            ->whereIsSubcategory()
            ->orderBy('sort')
            ->get();

        return $this->prepareCategoriesCollection($categories);
    }

    private function prepareCategoriesCollection(Collection $categories): Collection
    {
        $images = ImageSelection::getCategoriesImages(
            $categories->pluck('id')->toArray(),
        );

        return $categories->map(fn(object $category) => $this->prepareCategory($category, $images));
    }

    private function prepareCategory(object $category, Collection $images): object
    {
        $image = $images->where('owner_id', $category->id)->sortByDesc('main')->first();

        if ($image) {
            $category = ImagePropertiesHelper::setImageProperties($category, $image);
        }

        return $category;
    }
}

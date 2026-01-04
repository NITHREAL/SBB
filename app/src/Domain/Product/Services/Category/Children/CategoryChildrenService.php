<?php

namespace Domain\Product\Services\Category\Children;

use Domain\Product\Models\Category;
use Illuminate\Support\Collection;

readonly class CategoryChildrenService
{
    public function updateCategoriesChildrenSystemId(): void
    {
        $categories = Category::all();

        $result = [];

        foreach ($categories as $category) {
            $children = $this->getCategoryChildren($category, $categories, collect());

            $childrenSystemIds = $children->pluck('system_id')->toArray();

            $result[$category->system_id] = $childrenSystemIds;

            $category->children_system_ids = $childrenSystemIds;

            $category->save();
        }
    }

    private function getCategoryChildren(object $category, Collection $categories, Collection $children): Collection
    {
        $childs = $categories->where('parent_system_id', $category->system_id);

        if ($childs->isNotEmpty()) {
            foreach ($childs as $child) {
                $children = $this->getCategoryChildren($child, $categories, $children);
            }
        } else {
            $children = $children->push($category);
        }

        return $children;
    }
}

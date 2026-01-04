<?php

namespace Domain\Product\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;

/**
 * @method static self baseQuery()
 * @method static self categoryTreeQuery(int $categoryTreeLevel)
 * @method static self mainCategoriesQuery()
 * @method static self specialCategoriesByProducts(array $product1cIds)
 * @method static self whereProductsInStore(string $store1cId)
 * @method static self whereDoesntHaveParents()
 * @method static self whereHaveActiveChildren()
 * @method static self whereSlug(string $slug)
 * @method static self whereParent(string $parent1cId)
 * @method static self whereActive()
 * @method static self whereIsSubcategory()
 */
class CategoryQueryBuilder extends Builder
{
    public function baseQuery(): Builder
    {
        return $this
            ->select([
                'categories.id',
                'categories.system_id',
                'categories.slug',
                'categories.title',
                'categories.margin_left',
                'categories.margin_right',
                'categories.level',
                'categories.parent_system_id',
            ])
            ->orderBy('categories.sort');
    }

    public function categoryTreeQuery(int $categoryTreeLevel): Builder
    {
        return $this
            ->baseQuery()
            ->where(function ($query) use ($categoryTreeLevel) {
                return $query
                    ->where('categories.level', '<', $categoryTreeLevel)
                    ->orWhereNull('categories.level')
                    ->orWhere('categories.special_type', true);
            });
    }

    public function mainCategoriesQuery(): Builder
    {
        return $this
            ->baseQuery()
            ->whereDoesntHaveParents()
            ->whereActive();
    }

    public function specialCategoriesByProducts(array $product1cIds): Builder
    {
        return $this
            ->select([
                'categories.id as categoryId',
                'category_product.product_system_id as product1CId'
            ])
            ->leftJoin(
                'category_product',
                'category_product.category_system_id',
                '=',
                'categories.system_id',
            )
            ->whereIn('category_product.product_system_id', $product1cIds)
            ->where('categories.special_type', true);
    }

    public function whereProductsInStore(string $store1cId): Builder
    {
        // TODO доработать после появления логики для работы с датами
        return $this
            ->leftJoin('category_product as cp', 'cp.category_system_id', '=', 'categories.system_id')
            ->leftJoin('products', 'products.system_id', '=', 'cp.product_system_id')
            ->leftJoin('product_store as leftovers', 'leftovers.product_system_id', '=', 'products.system_id')
            ->where('leftovers.store_system_id', $store1cId)
            ->where('leftovers.active', true)
            ->where('leftovers.price', '>', 0)
            ->where(function (Builder $query) {
                return $query
                    ->where('cooking', true)
                    ->orWhereRaw('
                        (products.weight IS NULL or products.weight = 0 AND leftovers.count >= 1) OR
                        (products.weight > 0 and leftovers.count >= products.weight)
                    ');
            });
    }

    public function whereSlug(string $slug): Builder
    {
        return $this->where('categories.slug', $slug);
    }

    public function whereParent(string $parent1cId): Builder
    {
        return $this->where('categories.parent_system_id', $parent1cId);
    }

    public function whereActive(): self
    {
        return $this->where('categories.active', true);
    }

    public function whereIsSubcategory(): self
    {
        return $this->whereNotNull('categories.parent_system_id');
    }

    public function whereDoesntHaveParents(): self
    {
        return $this->whereNull('categories.parent_system_id');
    }

    public function whereHaveActiveChildren(): self
    {
        return $this->whereHas('children', fn($query) => $query->where('active', true));
    }
}

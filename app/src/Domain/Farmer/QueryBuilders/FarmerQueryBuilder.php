<?php

namespace Domain\Farmer\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;
use Infrastructure\Eloquent\Builder\BaseQueryBuilder;

/**
 * @method static whereSlug(string $slug)
 */
class FarmerQueryBuilder extends BaseQueryBuilder
{
    public function baseQuery(): self
    {
        return $this
            ->select([
                'farmers.id',
                'farmers.description',
                'farmers.supply_description',
                'farmers.name',
                'farmers.slug',
                'farmers.sort',
                'farmers.review_info',
                'farmers.address',
                'farmers.active',
                'farmers.rating',
                'farmers.system_id',
            ]);
    }
    public function whereSlug(string $slug): self
    {
        return $this->where('slug', $slug);
    }

    public function withProductsInStoreQuery(string $store1cId): self
    {
        return $this
            ->baseQuery()
            ->leftJoin('products', 'products.farmer_system_id', '=', 'farmers.system_id')
            ->leftJoin('product_store as leftovers', 'leftovers.product_system_id', '=', 'products.system_id')
            ->leftJoin('category_product as cp', 'cp.product_system_id', '=', 'products.system_id')
            ->leftJoin('categories', 'cp.category_system_id', '=', 'categories.system_id')
            ->whereNotNull('categories.parent_system_id')
            ->where('categories.active', true)
            ->where('farmers.active', true)
            ->where('leftovers.store_system_id', $store1cId)
            ->where('products.active', true)
            ->where('leftovers.active', true)
            ->where('leftovers.price', '>', 0)
            ->groupBy('farmers.id');
    }
}

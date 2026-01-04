<?php

namespace Domain\Product\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Infrastructure\Eloquent\Builder\BaseQueryBuilder;

/**
 * @method static baseQuery()
 * @method static catalogQuery(string $store1cId)
 */
class ForgottenProductQueryBuilder extends BaseQueryBuilder
{

    protected function selectProductFields(): self
    {
        return $this->select(
            [
                'products.id',
                'products.system_id as id1C',
                'products.title',
                'products.slug',
                'products.weight',
                'products.by_preorder',
                'products.cooking',
                'products.rating',
                'products.delivery_in_country',
                'products.vegan',
                'leftovers.delivery_schedule',
                'leftovers.price',
                'leftovers.price_discount',
                'leftovers.discount_expires_in',
                'leftovers.active as leftoversActive',
                'products.active',
                'leftovers.count as availableCount',
            ]
        );
    }

    protected function joinProductTables(): Builder
    {
        return $this->join('products', 'forgotten_products.product_id', '=', 'products.id')->leftJoin('product_store as leftovers', 'leftovers.product_system_id', '=', 'products.system_id')
            ->where('leftovers.active', true)
            ->where('products.active', true)
            ->where('leftovers.price', '>', 0);
    }

    public function baseQuery(): Builder
    {
        return $this
            ->selectProductFields()
            ->joinProductTables();
    }

    public function catalogQuery(string $store1cId): self
    {
        return $this
            ->baseQuery()
            ->addSelect([
                'units.title as unitTitle',
                'categories.id as categoryId',
                ])
            ->leftJoin('units', 'units.system_id', '=', 'products.unit_system_id')
            ->leftJoin('category_product as cp', 'cp.product_system_id', '=', 'products.system_id')
            ->leftJoin('categories', 'cp.category_system_id', '=', 'categories.system_id')
            ->whereHas('product.categories.parent', fn(Builder $q) => $q->where('active', true))
            ->whereNotNull('categories.parent_system_id')
            ->where('categories.active', true)
            ->when($store1cId, function (Builder $q) use ($store1cId) {
                $q->leftJoin('product_store as leftovers', 'leftovers.product_system_id', '=', 'products.system_id')
                    ->where('leftovers.store_system_id', $store1cId
            );
        });
    }

    public function addSorting(array $sortParams): Builder
    {
        return $this->orderBy(
            Arr::get($sortParams, 'column'),
            Arr::get($sortParams, 'direction')
        );
    }
}

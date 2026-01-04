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
class WeekProductQueryBuilder extends BaseQueryBuilder
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
        return $this->join('products', 'week_products.product_id', '=', 'products.id')->leftJoin('product_store as leftovers', 'leftovers.product_system_id', '=', 'products.system_id')
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

    public function catalogQueryForNonUser(): Builder
    {
        $subquery = DB::table('product_store as ps1')
            ->select('ps1.product_system_id', 'ps1.hash')
            ->where('ps1.active', true)
            ->where('ps1.price', '>', 0)
            ->where('ps1.count', '>', 0)
            ->leftJoin('products', 'ps1.product_system_id', '=', 'products.system_id')
            ->whereRaw('ps1.hash = (SELECT MIN(ps2.hash) FROM product_store as ps2 WHERE ps2.product_system_id = ps1.product_system_id AND ps2.active = true AND ps2.price > 0 AND ((products.weight > 0 AND ps2.count > products.weight) OR (products.weight = 0 AND ps2.count > 1)))');

        return $this->selectProductFields()
            ->join('products', 'week_products.product_id', '=', 'products.id')
            ->where('products.active', true)
            ->leftJoinSub($subquery, 'unique_leftovers', function ($join) {
                $join->on('products.system_id', '=', 'unique_leftovers.product_system_id');
            })
            ->leftJoin('product_store as leftovers', function ($join) {
                $join->on('leftovers.product_system_id', '=', 'unique_leftovers.product_system_id')
                    ->on('leftovers.hash', '=', 'unique_leftovers.hash'
                );
            })
            ->where('leftovers.active', true)
            ->where('leftovers.price', '>', 0);
    }

    public function addSorting(array $sortParams): Builder
    {
        return $this->orderBy(
            Arr::get($sortParams, 'column'),
            Arr::get($sortParams, 'direction')
        );
    }
}

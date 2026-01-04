<?php

namespace Domain\Product\QueryBuilders;

use Carbon\Carbon;
use Domain\Order\Helpers\OrderStatusHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Infrastructure\Eloquent\Builder\BaseQueryBuilder;
use Illuminate\Support\Facades\DB;

/**
 * @method static baseQuery()
 * @method static categoryQuery(string $store1cId)
 * @method static smallProductCardsQuery(string $store1cId)
 * @method static groupsQuery(array $groupIds, string $store1cId)
 * @method static basketQuery(string $store1cId, int $basketId)
 * @method static detailQuery(string $slug)
 * @method static searchByBarcodeQuery(string $barcode)
 * @method static ordersQuery(array $orderIds)
 * @method static userProductsQuery(int $userId)
 * @method static promoActionsQuery(array $promoActionIds, string $storeSystemId)
 * @method static lotteriesQuery(array $lotteryIds, string $storeSystemId)
 * @method static productsSelectionQuery(string $store1cId, int $limit, string $table)
 * @method static farmerCategoriesQuery(string $store1cId)
 * @method static whereCategory(int $categoryId)
 * @method static wherePromoAction(int $promoActionId)
 * @method static whereLottery(int $lotteryId)
 * @method static whereSearch(string $search)
 * @method static whereStoreOneCId(string $store1cId)
 * @method static whereStoreSlug(string $storeSlug)
 * @method static catalogQueryByStoreSlug(string $storeSlug)
 * @method static withProductGroup()
 * @method static whereForVegan()
 * @method static whereFarmers(array $farmerIds)
 * @method static whereProductSlug(string $productSlug)
 * @method static whereProductGroup(array $groupIds)
 * @method static whereCategoriesParentAndChildren(int $categoryId, array $childrenSystemIds)
 * @method static whereActive()
 * @method static addSorting(array $sortParams)
 */
class ProductQueryBuilder extends BaseQueryBuilder
{
    public function baseQuery(): self
    {
        return $this
            ->select([
                'products.id',
                'products.system_id as id1C',
                'products.title',
                'products.slug',
                'products.is_weight',
                'products.weight',
                'products.by_preorder',
                'products.cooking',
                'products.rating',
                'products.popular',
                'products.delivery_in_country',
                'products.vegan',
                'leftovers.price',
                'leftovers.price_discount',
                'leftovers.discount_expires_in',
                'leftovers.active as leftoversActive',
                'products.active',
                'leftovers.delivery_schedule',
                'leftovers.count as availableCount',
            ])
            ->leftJoin('product_store as leftovers', 'leftovers.product_system_id', '=', 'products.system_id')
            ->where('leftovers.active', true)
            ->where('products.active', true)
            ->where('leftovers.price', '>', 0);
    }

    public function categoryQuery(string $store1cId): self
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
            ->whereHas('categories.parent', fn(Builder $q) => $q->where('active', true))
            ->whereNotNull('categories.parent_system_id')
            ->where('categories.active', true)
            ->whereStoreOneCId($store1cId);
    }

    public function smallProductCardsQuery(string $store1cId): self
    {
        return $this->baseQuery()
            ->addSelect([
                'units.title as unitTitle',
            ])
            ->leftJoin('units', 'units.system_id', '=', 'products.unit_system_id')
            ->whereHas('categories', function ($query) {
                return $query
                    ->whereHas('parent', fn(Builder $q) => $q->where('active', true))
                    ->whereNotNull('categories.parent_system_id')
                    ->where('active', true);
            })
            ->whereStoreOneCId($store1cId);
    }

    public function basketQuery(string $store1cId, int $basketId): self
    {
        return $this
            ->baseQuery()
            ->addSelect([
                'products.unit_system_id as unit1cId',
                'bp.count as count',
                'bp.weight as basketWeight',
                'leftovers.count as availableCount',
                'bp.from_order as fromOrder',
            ])
            ->leftJoin('basket_product as bp', 'bp.product_id', '=', 'products.id')
            ->whereStoreOneCId($store1cId)
            ->where('bp.basket_id', $basketId);
    }

    public function groupsQuery(array $groupIds, string $store1cId): self
    {
        return $this
            ->baseQuery()
            ->addSelect([
                'units.title as unitTitle',
            ])
            ->leftJoin('units', 'units.system_id', '=', 'products.unit_system_id')
            ->leftJoin('category_product as cp', 'cp.product_system_id', '=', 'products.system_id')
            ->leftJoin('categories', 'cp.category_system_id', '=', 'categories.system_id')
            ->whereNotNull('categories.parent_system_id')
            ->where('categories.active', true)
            ->whereProductGroup($groupIds)
            ->whereStoreOneCId($store1cId);
    }

    public function detailQuery(string $slug): Builder
    {
        return $this
            ->baseQuery()
            ->addSelect([
                'products.description',
                'products.composition',
                'products.proteins',
                'products.fats',
                'products.carbohydrates',
                'products.nutrition_kcal',
                'products.nutrition_kj',
                'products.storage_conditions',
                'products.shelf_life',
                'categories.id as category_id',
            ])
            ->leftJoin('category_product as cp', 'cp.product_system_id', '=', 'products.system_id')
            ->leftJoin('categories', 'cp.category_system_id', '=', 'categories.system_id')
            ->whereProductSlug($slug);
    }

    public function ordersQuery(array $orderIds): self
    {
        return $this
            ->select([
                'products.id',
                'products.system_id as id1C',
                'products.title',
                'products.slug',
                'products.rating',
                'products.is_weight',
                'op.order_id as orderId',
                'op.count',
                'op.price',
                'op.price_discount as priceDiscount',
                'op.price_promo as pricePromo',
                'op.total',
                'op.total_without_discount',
                'op.weight',
                'units.title as unitTitle',
            ])
            ->leftJoin('order_product as op', 'op.product_system_id', '=', 'products.system_id')
            ->leftJoin('units', 'units.system_id', '=', 'op.unit_system_id')
            ->whereIn('op.order_id', $orderIds);
    }

    public function userProductsQuery(int $userId, string $storeOneCId): self
    {
        return $this
            ->select([
                'products.id',
                'products.system_id as id1C',
                'products.title',
                'products.slug',
                'products.is_weight',
                'products.weight',
                'products.by_preorder',
                'products.cooking',
                'products.rating',
                'products.popular',
                'products.delivery_in_country',
                'products.vegan',
                'products.active',
                DB::raw('MAX(units.title) as unitTitle'),
                DB::raw('MAX(leftovers.price) as price'),
                DB::raw('MAX(leftovers.price_discount) as price_discount'),
                DB::raw('MAX(leftovers.discount_expires_in) as discount_expires_in'),
                DB::raw('MAX(leftovers.delivery_schedule) as delivery_schedule'),
                DB::raw('MAX(leftovers.count) as availableCount'),
                DB::raw('MAX(ord.created_at) as purchasedAt'), // Агрегируем по последнему заказу
                DB::raw('MAX(leftovers.active) as leftoversActive'),
            ])
            ->leftJoin('product_store as leftovers', 'leftovers.product_system_id', '=', 'products.system_id')
            ->leftJoin('order_product as op', 'op.product_system_id', '=', 'products.system_id')
            ->leftJoin('orders as ord', 'ord.id', '=', 'op.order_id')
            ->leftJoin('units', 'units.system_id', '=', 'op.unit_system_id')
            ->where('leftovers.store_system_id', $storeOneCId)
            ->where('leftovers.active', true)
            ->where('products.active', true)
            ->where('leftovers.price', '>', 0)
            ->where('ord.user_id', $userId)
            ->whereNotIn('ord.status', OrderStatusHelper::getCanceledStatuses())
            ->groupBy('products.id')
            ->orderByDesc('purchasedAt');
    }

    public function searchByBarcodeQuery(string $barcode): self
    {
        return $this
            ->baseQuery()
            ->addSelect([
                'products.description',
                'products.composition',
                'products.proteins',
                'products.fats',
                'products.carbohydrates',
                'products.nutrition_kcal',
                'products.nutrition_kj',
                'products.storage_conditions',
                'products.shelf_life',
                'farmers.id as farmer_id',
                'farmers.name as farmer_name',
                'farmers.slug as farmer_slug',
                'categories.id as category_id',
            ])
            ->leftJoin('farmers', 'farmers.system_id', '=', 'products.farmer_system_id')
            ->leftJoin('category_product as cp', 'cp.product_system_id', '=', 'products.system_id')
            ->leftJoin('categories', 'cp.category_system_id', '=', 'categories.system_id')
            ->whereProductBarcode($barcode);
    }

    public function promoActionsQuery(array $promoActionIds, string $storeSystemId): self
    {
        return $this
            ->baseQuery()
            ->whereActiveSubcategory()
            ->wherePromoActions($promoActionIds)
            ->whereStoreOneCId($storeSystemId);
    }

    public function lotteriesQuery(array $lotteryIds, string $storeSystemId): self
    {
        return $this
            ->baseQuery()
            ->whereActiveSubcategory()
            ->whereLotteries($lotteryIds)
            ->whereStoreOneCId($storeSystemId);
    }

    public function productsSelectionQuery(
        string $store1cId,
        int $limit,
        string $tableName,
    ): self {
        $selectionTable = sprintf('%s as selection', $tableName);

        return $this
            ->smallProductCardsQuery($store1cId)
            ->join($selectionTable, 'selection.product_id', '=', 'products.id')
            ->orderBy('selection.sort')
            ->limit($limit);
    }

    public function farmerCategoriesQuery(string $store1cId): self
    {
        return $this
            ->baseQuery()
            ->addSelect([
                'units.title as unitTitle',
                'categories.id as categoryId',
                'farmers.id as farmerId',
                'categories.slug as categorySlug',
                'categories.title as categoryTitle',
                'categories.active as categoryActive',
                'categories.parent_system_id as categoryParent',
                'categoryParents.system_id as parent1cId'
            ])
            ->leftJoin('farmers', 'farmers.system_id', '=', 'products.farmer_system_id')
            ->leftJoin('units', 'units.system_id', '=', 'products.unit_system_id')
            ->leftJoin('category_product as cp', 'cp.product_system_id', '=', 'products.system_id')
            ->leftJoin('categories', 'cp.category_system_id', '=', 'categories.system_id')
            ->leftJoin('categories as categoryParents', 'categories.parent_system_id', '=', 'categoryParents.system_id')
            ->whereNotNull('categories.parent_system_id')
            ->where('categories.active', true)
            ->where('categoryParents.active', true)
            ->whereStoreOneCId($store1cId)
            ;
    }

    public function whereStoreOneCId(string $store1cId): self
    {
        return $this
            ->leftJoin('product_store as leftovers', 'leftovers.product_system_id', '=', 'products.system_id')
            ->where('leftovers.store_system_id', $store1cId)
            ;
    }

    public function whereCategory(int $categoryId): Builder
    {
        return $this
            ->leftJoin(
                'category_product as cp',
                'cp.product_system_id',
                '=',
                'products.system_id',
            )
            ->leftJoin('categories', 'categories.system_id', '=', 'cp.category_system_id')
            ->where('categories.id', $categoryId);
    }

    public function whereCategoriesParentAndChildren(int $categoryId, array $categorySystemIds): self
    {
        return $this
            ->leftJoin(
                'category_product as cp',
                'cp.product_system_id',
                '=',
                'products.system_id',
            )
            ->leftJoin('categories', 'categories.system_id', '=', 'cp.category_system_id')
            ->where(function ($query) use ($categoryId, $categorySystemIds) {
                return $query
                    ->where('categories.id', $categoryId)
                    ->orWhere(function ($query) use ($categorySystemIds) {
                        foreach ($categorySystemIds as $categorySystemId) {
                            $query->orWhere('categories.system_id', $categorySystemId);
                        }

                        return $query;
                    });
            });
    }

    public function wherePromoAction(int $promoActionId): self
    {
        return $this
            ->addSelect(['promo_action_products.promo_action_id as promo_action_id'])
            ->leftJoin('promo_action_products', 'promo_action_products.product_id', '=', 'products.id')
            ->where('promo_action_products.promo_action_id', $promoActionId)
            ->orderBy('promo_action_products.sort');
    }

    public function whereLottery(int $lotteryId): self
    {
        return $this
            ->addSelect(['lottery_products.lottery_id as lottery_id'])
            ->leftJoin('lottery_products', 'lottery_products.product_id', '=', 'products.id')
            ->where('lottery_products.lottery_id', $lotteryId)
            ->orderBy('lottery_products.sort');
    }

    public function whereFarmers(array $farmerIds): Builder
    {
        return $this
            ->leftJoin('farmers', 'farmers.system_id', '=', 'products.farmer_system_id')
            ->whereIn('farmers.id', $farmerIds);
    }

    public function whereSearch(string $title): self
    {
        return $this->where('products.title', 'like', '%' . $title . '%');
//        $keywords = explode(' ', $title);
//
//        return $this->where(function ($query) use ($keywords) {
//            foreach ($keywords as $word) {
//                $query->orWhere('products.title', 'LIKE', '%' . $word . '%');
//            }
//        });
    }

    public function whereForVegan(): self
    {
        return $this->where('products.vegan', true);
    }

    public function whereProductSlug(string $productSlug): self
    {
        return $this->where('products.slug', $productSlug);
    }

    public function whereProductBarcode(string $barcode): self
    {
        return $this->whereJsonContains('products.barcodes', $barcode);
    }

    public function whereProductGroup(array $groupIds): self
    {
        return $this
            ->addSelect(['group_products.group_id as groupId'])
            ->leftJoin('group_products', 'group_products.product_id', '=', 'products.id')
            ->whereIn('group_products.group_id', $groupIds)
            ->orderBy('group_products.sort');
    }

    public function wherePromoActions(array $promoActionIds): self
    {
        return $this
            ->addSelect(['promo_action_products.promo_action_id as promo_action_id'])
            ->leftJoin('promo_action_products', 'promo_action_products.product_id', '=', 'products.id')
            ->whereIn('promo_action_products.promo_action_id', $promoActionIds)
            ->orderBy('promo_action_products.sort');
    }

    public function whereLotteries(array $lotteryIds): self
    {
        return $this
            ->addSelect(['lottery_products.lottery_id as lottery_id'])
            ->leftJoin('lottery_products', 'lottery_products.product_id', '=', 'products.id')
            ->whereIn('lottery_products.lottery_id', $lotteryIds)
            ->orderBy('lottery_products.sort');
    }

    public function whereActiveSubcategory(): self
    {
        return $this
            ->leftJoin('category_product as cp', 'cp.product_system_id', '=', 'products.system_id')
            ->leftJoin('categories', 'cp.category_system_id', '=', 'categories.system_id')
            ->whereNotNull('categories.parent_system_id')
            ->where('categories.active', true);
    }

    public function whereCategories(array $categoryIds): Builder
    {
        return $this
            ->leftJoin(
                'category_product as cp',
                'cp.product_system_id',
                '=',
                'products.system_id',
            )
            ->leftJoin('categories', 'categories.system_id', '=', 'cp.category_system_id')
            ->whereIn('categories.id', $categoryIds);
    }

    public function whereAvailableToday(): self
    {
        return $this
            ->where('leftovers.count', '>', 0)
            ->where(function (ProductQueryBuilder $query) {
                return $query
                    ->where(function (ProductQueryBuilder $query) {
                        return $query
                            ->whereNotNull('products.weight')
                            ->whereRaw('leftovers.count >= products.weight');
                    })
                    ->orWhere(function (ProductQueryBuilder $query) {
                        return $query->whereNull('products.weight');
                    });
            });
    }

    public function whereOrderedInTime(Carbon $from, Carbon $to): self
    {
        return $this
            ->select([
                'products.id',
                'products.system_id',
                'products.slug',
                'products.sku',
                'products.farmer_system_id',
                'products.unit_system_id',
                'products.title',
                'op.count',
                'op.product_system_id',
                DB::raw('SUM(op.count) as total_count'),
            ])
            ->join('order_product as op', 'op.product_system_id', '=', 'products.system_id')
            ->join('orders as ord', 'ord.id', '=', 'op.order_id')
            ->whereBetween('ord.created_at', [ $from, $to])
            ->groupBy([
                'products.id',
                'products.system_id',
                'op.product_system_id',
                'op.count'
            ]);
    }

    public function whereActive(): self
    {
        return $this->where('products.active', true);
    }

    public function addSorting(array $sortParams): Builder
    {
        return $this->orderBy(
            Arr::get($sortParams, 'column'),
            Arr::get($sortParams, 'direction'),
        );
    }
}

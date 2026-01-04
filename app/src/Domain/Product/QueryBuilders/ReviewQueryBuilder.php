<?php

namespace Domain\Product\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;
use Infrastructure\Eloquent\Builder\BaseQueryBuilder;

/**
 * @method static catalogQuery()
 * @method static whereCategory(int $categoryId)
 * @method static whereSearch(string $search)
 * @method static whereForVegan()
 * @method static whereFarmers(array $farmerIds)
 * @method static addSorting(array $sortParams)
 */
class ReviewQueryBuilder extends BaseQueryBuilder
{
    public function indexQuery(string $productSlug): Builder
    {
        return $this
            ->select([
                'reviews.id',
                'reviews.rating',
                'reviews.text',
                'reviews.created_at',
                'users.first_name',
                'users.last_name',
                'users.id as user_id',
            ])
            ->leftJoin('products', 'products.id', '=', 'reviews.product_id')
            ->leftJoin('users', 'users.id', '=', 'reviews.user_id')
            ->where('products.slug', $productSlug)
            ->where('reviews.active', true);
    }

    public function farmersQuery(array $farmerIds): Builder
    {
        return $this
            ->select([
                'reviews.*',
                'farmers.id as farmerId',
                'products.title as productTitle'
            ])
            ->leftJoin('products', 'products.id', '=', 'reviews.product_id')
            ->leftJoin('farmers', 'products.farmer_system_id', '=', 'farmers.system_id')
            ->whereIn('farmers.id', $farmerIds)
            ->where('products.active', true)
            ->where('reviews.active', true);
    }

    public function whereProductId(int $productId): self
    {
        return $this->where('reviews.product_id', $productId);
    }

    public function whereActive(): self
    {
        return $this->where('reviews.active', true);
    }

    public function whereUser(int $userId): self
    {
        return $this->where('reviews.user_id', $userId);
    }
}

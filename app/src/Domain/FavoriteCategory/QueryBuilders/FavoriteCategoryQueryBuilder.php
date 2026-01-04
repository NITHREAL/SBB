<?php

namespace Domain\FavoriteCategory\QueryBuilders;

use Infrastructure\Eloquent\Builder\BaseQueryBuilder;

/**
 * @method static self wherePeriod(string $period)
 * @method static self whereLoyaltyId(string $loyaltyId)
 */
class FavoriteCategoryQueryBuilder extends BaseQueryBuilder
{
    public function wherePeriod(string $period): self
    {
        return $this->where('favorite_categories.period', $period);
    }

    public function whereLoyaltyId(string $loyaltyId): self
    {
        return $this->where('favorite_categories.loyalty_id', $loyaltyId);
    }
}

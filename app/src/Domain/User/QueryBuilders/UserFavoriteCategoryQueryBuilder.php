<?php

namespace Domain\User\QueryBuilders;

use Infrastructure\Eloquent\Builder\BaseQueryBuilder;

/**
 * @method static self baseQuery(int $userId)
 * @method static self whereUser(int $userId)
 * @method static self wherePeriods(array $periods)
 * @method static self wherePeriod(string $period)
 */
class UserFavoriteCategoryQueryBuilder extends BaseQueryBuilder
{
    public function baseQuery(int $userId): self
    {
        return $this
            ->select([
                'user_favorite_categories.period',
                'categories.title',
                'categories.image',
            ])
            ->leftJoin('favorite_categories as categories', 'categories.id', '=', 'user_favorite_categories.category_id')
            ->whereUser($userId);
    }

    public function whereUser(int $userId): self
    {
        return $this->where('user_favorite_categories.user_id', $userId);
    }

    public function wherePeriods(array $periods): self
    {
        return $this->whereIn('user_favorite_categories.period', $periods);
    }

    public function wherePeriod(string $period): self
    {
        return $this->where('user_favorite_categories.period', $period);
    }
}

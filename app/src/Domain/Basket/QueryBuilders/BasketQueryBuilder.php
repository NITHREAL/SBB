<?php

namespace Domain\Basket\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;
use Infrastructure\Eloquent\Builder\BaseQueryBuilder;

/**
 * @method static whereToken(string $token)
 * @method static whereUser(int $userId)
 */
class BasketQueryBuilder extends BaseQueryBuilder
{
    public function whereToken(string $token): Builder
    {
        return $this->where('baskets.token', $token);
    }

    public function whereUser(int $userId): Builder
    {
        return $this->where('user_id', $userId);
    }
}

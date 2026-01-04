<?php

namespace Domain\User\QueryBuilders;

use Infrastructure\Eloquent\Builder\BaseQueryBuilder;

/**
 * @method static self whereUser(int $userId)
 * @method static self whereStore(int $storeId)
 * @method static self whereChosen()
 */
class UserStoreQueryBuilder extends BaseQueryBuilder
{
    public function whereUser(int $userId): self
    {
        return $this->where('favorite_stores.user_id', $userId);
    }

    public function whereStore(int $storeId): self
    {
        return $this->where('favorite_stores.store_id', $storeId);
    }

    public function whereChosen(): self
    {
        return $this->where('favorite_stores.chosen', true);
    }
}

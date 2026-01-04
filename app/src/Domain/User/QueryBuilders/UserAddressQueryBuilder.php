<?php

namespace Domain\User\QueryBuilders;

use Infrastructure\Eloquent\Builder\BaseQueryBuilder;

/**
 * @method static whereUser(int $userId)
 * @method static whereAddress(string $address)
 * @method static whereCity(int $cityId)
 * @method static whereChosen()
 */
class UserAddressQueryBuilder extends BaseQueryBuilder
{
    public function whereUser(int $userId): self
    {
        return $this->where('user_id', $userId);
    }

    public function whereAddress(string $address): self
    {
        return $this->where('address', $address);
    }

    public function whereCity(int $cityId): self
    {
        return $this->where('user_addresses.city_id', $cityId);
    }

    public function whereChosen(): self
    {
        return $this->where('user_addresses.chosen', true);
    }
}

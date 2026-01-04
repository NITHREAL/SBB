<?php

namespace Domain\BonusLevel\QueryBuilders;

use Infrastructure\Eloquent\Builder\BaseQueryBuilder;

/**
 * @method static self whereLoyaltyId(string $loyaltyId)
 */
class BonusLevelQueryBuilder extends BaseQueryBuilder
{
    public function whereLoyaltyId(string $loyaltyId): self
    {
        return $this->where('bonus_levels.loyalty_id', $loyaltyId);
    }
}

<?php

namespace Domain\CouponCategory\QueryBuilders;

use Infrastructure\Eloquent\Builder\BaseQueryBuilder;

/**
 * @method static self baseQuery()
 * @method static self whereGuid(string $guid)
 * @method static self whereActive()
 */
class CouponCategoryQueryBuilder extends BaseQueryBuilder
{
    public function baseQuery(): self
    {
        return $this
            ->select(['coupon_categories.*'])
            ->orderBy('sort');
    }

    public function whereGuid(string $guid): self
    {
        return $this->where('coupon_categories.guid', $guid);
    }

    public function whereActive(): self
    {
        return $this->where('coupon_categories.active', true);
    }
}

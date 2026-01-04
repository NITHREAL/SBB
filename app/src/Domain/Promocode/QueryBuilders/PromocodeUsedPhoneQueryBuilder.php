<?php

namespace Domain\Promocode\QueryBuilders;

use Infrastructure\Eloquent\Builder\BaseQueryBuilder;

/**
 * @method static wherePromocode(int $promocodeId)
 * @method static wherePhone(string $phone)
 */
class PromocodeUsedPhoneQueryBuilder extends BaseQueryBuilder
{
    public function wherePromocode(int $promocodeId): self
    {
        return $this->where('promo_used_phones.promo_id', $promocodeId);
    }

    public function wherePhone(string $phone): self
    {
        return $this->where('promo_used_phones.phone', $phone);
    }
}

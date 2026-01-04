<?php

namespace Domain\PromoAction\QueryBuilders;

use Infrastructure\Eloquent\Builder\BaseQueryBuilder;

/**
 * @method static self baseQuery()
 * @method static self whereSlug(string $slug)
 * @method static self whereActive()
 */
class PromoActionQueryBuilder extends BaseQueryBuilder
{
    public function baseQuery(): self
    {
        return $this
            ->select(['promo_actions.*'])
            ->orderBy('sort');
    }

    public function whereSlug(string $slug): self
    {
        return $this->where('promo_actions.slug', $slug);
    }

    public function whereActive(): self
    {
        return $this
            ->where('promo_actions.active', true)
            ->where(function (self $query) {
                return $query
                    ->where(function (self $query) {
                        return $query
                            ->where('promo_actions.active_from', '<=', now())
                            ->orWhereNull('promo_actions.active_from');
                    })
                    ->where(function (self $query) {
                        return $query
                            ->where('promo_actions.active_to', '>', now())
                            ->orWhereNull('promo_actions.active_to');
                    });
            });
    }
}

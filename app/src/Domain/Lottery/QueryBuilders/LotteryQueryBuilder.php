<?php

namespace Domain\Lottery\QueryBuilders;

use Infrastructure\Eloquent\Builder\BaseQueryBuilder;

/**
 * @method static self baseQuery()
 * @method static self whereSlug(string $slug)
 * @method static self whereActive()
 */
class LotteryQueryBuilder extends BaseQueryBuilder
{
    public function baseQuery(): self
    {
        return $this
            ->select(['lotteries.*'])
            ->orderBy('lotteries.sort');
    }

    public function whereSlug(string $slug): self
    {
        return $this->where('lotteries.slug', $slug);
    }

    public function whereActive(): self
    {
        return $this
            ->where('lotteries.active', true)
            ->where(function (self $query) {
                return $query
                    ->where(function (self $query) {
                        return $query
                            ->where('lotteries.active_from', '<=', now())
                            ->orWhereNull('lotteries.active_from');
                    })
                    ->where(function (self $query) {
                        return $query
                            ->where('lotteries.active_to', '>', now())
                            ->orWhereNull('lotteries.active_to');
                    });
            });
    }
}

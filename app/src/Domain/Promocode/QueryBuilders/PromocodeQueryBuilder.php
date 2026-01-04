<?php

namespace Domain\Promocode\QueryBuilders;

use Illuminate\Support\Carbon;
use Infrastructure\Eloquent\Builder\BaseQueryBuilder;

/**
 *  @method static whereActual()
 *  @method static whereCode(string $code)
 *  @method static whereMobile()
 *  @method static whereOrderType(string $orderType)
 *  @method static whereAudienceUser(int $userId)
 */
class PromocodeQueryBuilder extends BaseQueryBuilder
{
    public function whereActual(): self
    {
        return $this
            ->where(function ($query) {
                return $query
                    ->whereNull('promos.expires_in')
                    ->orWhere('promos.expires_in', '>', Carbon::now());
            });
    }

    public function whereCode(string $code): self
    {
        return $this->where('promos.code', $code);
    }

    public function whereMobile(): self
    {
        return $this->where('promos.mobile', true);
    }

    public function whereActive(): self
    {
        return $this->where('promos.active', true);
    }

    public function whereOrderType(string $orderType): self
    {
        return $this->where('promos.order_type', $orderType);
    }

    public function whereAudienceUser(int $userId): self
    {
        return $this
            ->leftJoin('audiences', 'audiences.id', '=', 'promos.show_audience_id')
            ->leftJoin(
                'audience_list as audienceUsers',
                'audienceUsers.audience_id',
                '=',
                'audiences.id'
            )
            ->where(function ($query) use ($userId) {
                return $query
                    ->where('audienceUsers.user_id', $userId)
                    ->orWhereNull('promos.show_audience_id');
            })
            ->groupBy(['promos.id', 'audienceUsers.user_id']);
    }
}

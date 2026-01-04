<?php

declare(strict_types=1);

namespace Domain\ProductGroup\QueryBuilders;

use Infrastructure\Eloquent\Builder\BaseQueryBuilder;

/**
 * @method static baseQuery()
 * @method static wherePlatform(bool $isMobile)
 * @method static user(?int $userId)
 * @method static whereActive()
 * @method static whereSlug(string $slug)
 */
class ProductGroupQueryBuilder extends BaseQueryBuilder
{
    public function baseQuery(): self
    {
        return $this
            ->select([
                'groups.id',
                'groups.title',
                'groups.slug',
                'groups.sort',
            ])
        ;
    }

    public function wherePlatform(): self
    {
        return $this->where('groups.site', true);
    }

    public function user(?int $userId): self
    {
        $query = $this->whereNull('groups.audience_id');

        if ($userId) {
            return $query
                ->addSelect(['audiences.title as audienceTitle'])
                ->leftJoin(
                    'audiences',
                    'audiences.id',
                    '=',
                    'groups.audience_id',
                )
                ->leftJoin(
                    'audience_list as audienceUsers',
                    'audienceUsers.audience_id',
                    '=',
                    'audiences.id'
                )
                ->orWhere('audienceUsers.user_id', $userId)
                ->groupBy(['groups.id', 'audienceUsers.user_id']);
        }

        return $query;
    }

    public function whereActive(): self
    {
        return $this->where('groups.active', true);
    }

    public function whereSlug(string $slug): self
    {
        return $this->where('groups.slug', $slug);
    }
}

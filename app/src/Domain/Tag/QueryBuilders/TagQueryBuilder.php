<?php

namespace Domain\Tag\QueryBuilders;

use Infrastructure\Eloquent\Builder\BaseQueryBuilder;

/**
 * @method static byGroupsCollection(array $groupIds)
 * @method static whereActive()
 */
class TagQueryBuilder extends BaseQueryBuilder
{
    public function byGroupsCollection(array $groupIds): self
    {
        return $this
            ->select(['tags.*', 'taggables.taggable_id as groupId'])
            ->leftJoin('taggables', 'taggables.tag_id', '=', 'tags.id')
            ->where('taggables.taggable_type', 'group')
            ->whereIn('taggables.taggable_id', $groupIds);
    }

    public function whereActive(): self
    {
        return $this->where('active', true);
    }
}

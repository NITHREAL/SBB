<?php

namespace Domain\Tag\Service;

use Domain\Tag\Models\Tag;
use Illuminate\Support\Collection;

class TagSelectionService
{
    public static function getTagByGroups(array $groupIds): Collection
    {
        return Tag::query()
            ->byGroupsCollection($groupIds)
            ->whereActive()
            ->get();
    }
}

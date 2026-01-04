<?php

declare(strict_types=1);

namespace Domain\Story\QueryBuilders;

use Infrastructure\Eloquent\Builder\BaseQueryBuilder;

/**
 * @method static user(?int $userId, int $storyId = null)
 * @method static active()
 * @method static whereAvailableInGroups()
 */
class StoryQueryBuilder extends BaseQueryBuilder
{
    public function baseQuery(): self
    {
        return $this
            ->select(['stories.*'])
            ->orderByDesc('stories.id');
    }

    public function active(): self
    {
        return $this->where('stories.active', true);
    }

    public function user(?int $userId, int $storyId = null): self
    {
        $query = $this->whereNull('stories.audience_id');

        if ($userId) {
            $query
                ->leftJoin(
                    'audiences',
                    'audiences.id',
                    '=',
                    'stories.audience_id',
                )
                ->leftJoin(
                    'audience_list as audienceUsers',
                    'audienceUsers.audience_id',
                    '=',
                    'audiences.id'
                )
                ->orWhere(function($query) use($userId, $storyId) {
                    $query->where('audienceUsers.user_id', $userId)
                        ->where('stories.active',true)
                        ->when($storyId, function ($query, string $storyId) {
                            $query->where('stories.id', $storyId);
                        });
                })
                ->groupBy(['stories.id', 'audienceUsers.user_id']);
        }

        return $query;
    }

    public function whereAvailableInGroups(): self
    {
        return $this->where('stories.available_in_groups', true);
    }
}

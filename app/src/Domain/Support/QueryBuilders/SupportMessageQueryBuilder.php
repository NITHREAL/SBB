<?php

namespace Domain\Support\QueryBuilders;

use Infrastructure\Eloquent\Builder\BaseQueryBuilder;

/**
 * @method static self whereUser(int $userId)
 * @method static self whereOnlyForStuff(bool $isOnlyForStuff)
 * @method static self whereUnread()
 * @method static self whereAuthor(string $author)
 */
class SupportMessageQueryBuilder extends BaseQueryBuilder
{
    public function whereUser(int $userId): self
    {
        return $this->where('support_messages.user_id', $userId);
    }

    public function whereOnlyForStuff(bool $isOnlyForStuff): self
    {
        return $this->where('support_messages.stuff_only', $isOnlyForStuff);
    }

    public function whereUnread(): self
    {
        return $this->where('support_messages.viewed', false);
    }

    public function whereAuthor(string $author): self
    {
        return $this->where('support_messages.author', $author);
    }
}

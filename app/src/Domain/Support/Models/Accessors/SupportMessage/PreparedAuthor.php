<?php

namespace Domain\Support\Models\Accessors\SupportMessage;

use Domain\Support\Enums\SupportMessageAuthorEnum;
use Domain\Support\Models\SupportMessage;
use Illuminate\Support\Arr;

final class PreparedAuthor
{
    public function __construct(
        private readonly SupportMessage $supportMessage,
    ) {
    }

    public function __invoke(): string
    {
        $author = $this->supportMessage->author;

        return Arr::get(SupportMessageAuthorEnum::toArray(), $author, $author);
    }
}

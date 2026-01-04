<?php

namespace Domain\User\DTO\Bonuses;

use Domain\User\Models\User;
use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class BonusesHistoryDTO extends BaseDTO
{
    private const DEFAULT_LIMIT = 15;

    private const DEFAULT_PAGE = 1;

    public function __construct(
        private readonly int $limit,
        private readonly int $page,
        private readonly User $user,
    ) {
    }

    public static function make(array $data, User $user): self
    {
        return new self(
            Arr::get($data, 'limit') ?? self::DEFAULT_LIMIT,
            Arr::get($data, 'page') ?? self::DEFAULT_PAGE,
            $user,
        );
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}

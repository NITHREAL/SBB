<?php

namespace Domain\User\DTO\Order;

use Domain\User\Models\User;
use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class UserOrdersHistoryDTO extends BaseDTO
{
    private const DEFAULT_LIMIT = 10;

    public function __construct(
        private ?int $limit,
        private User $user,
    ) {
    }

    public static function make(array $data, User $user): self
    {
        return new self(
            Arr::get($data, 'limit', self::DEFAULT_LIMIT),
            $user,
        );
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}

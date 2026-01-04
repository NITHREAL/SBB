<?php

namespace Domain\User\DTO\Product;

use Domain\User\Models\User;
use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class PurchasedProductDTO extends BaseDTO
{
    private const DEFAULT_LIMIT = 4;

    public function __construct(
        private readonly int  $limit,
        private readonly User $user,
    ) {
    }

    public static function make(array $data, User $user): self
    {
        return new static(
            Arr::get($data, 'limit', self::DEFAULT_LIMIT),
            $user,
        );
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }
}

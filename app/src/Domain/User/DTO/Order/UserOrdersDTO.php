<?php

namespace Domain\User\DTO\Order;

use Domain\User\Models\User;
use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class UserOrdersDTO extends BaseDTO
{
    private int $defaultLimit = 10;

    public function __construct(
        private ?string $type,
        private ?string $state,
        private ?string $deliveryType,
        private ?int $limit,
        private User $user,
    ) {
    }

    public static function make(array $data, User $user): self
    {
        return new self(
            Arr::get($data, 'requestFrom'),
            Arr::get($data, 'state'),
            Arr::get($data, 'deliveryType'),
            Arr::get($data, 'limit'),
            $user,
        );
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getLimit(): int
    {
        return $this->limit ?? $this->defaultLimit;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function getDeliveryType(): ?string
    {
        return $this->deliveryType;
    }
}

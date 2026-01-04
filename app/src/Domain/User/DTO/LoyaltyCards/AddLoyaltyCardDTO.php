<?php

namespace Domain\User\DTO\LoyaltyCards;

use Domain\User\Models\User;
use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class AddLoyaltyCardDTO extends BaseDTO
{
    public function __construct(
        private readonly string $cardNumber,
        private readonly User $user,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'cardNumber'),
            Arr::get($data, 'user'),
        );
    }

    public function getCardNumber(): string
    {
        return $this->cardNumber;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}

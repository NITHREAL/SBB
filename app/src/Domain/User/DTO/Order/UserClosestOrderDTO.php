<?php

namespace Domain\User\DTO\Order;

use Domain\User\Models\User;
use Infrastructure\DTO\BaseDTO;

class UserClosestOrderDTO extends BaseDTO
{
    public function __construct(
        private readonly User $user,
    ) {
    }

    public static function make(User $user): self
    {
        return new self(
            $user
        );
    }

    public function getUser(): User
    {
        return $this->user;
    }
}

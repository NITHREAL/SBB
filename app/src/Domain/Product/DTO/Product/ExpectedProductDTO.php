<?php

namespace Domain\Product\DTO\Product;

use Domain\User\Models\User;
use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class ExpectedProductDTO extends BaseDTO
{

    public function __construct(
        private readonly User $user,
        private readonly int $product_id,
    ) {
    }

    public static function make(int $id, User $user): self
    {
        return new self(
            $user,
            $id,
        );
    }

    public function getProductId(): string
    {
        return $this->product_id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

}

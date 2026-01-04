<?php

namespace Domain\Promocode\DTO;

use Domain\User\Models\User;
use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class GetPromocodesDTO extends BaseDTO
{
    public function __construct(
        private readonly bool $isMobile,
        private readonly User $user,
    ) {
    }

    public static function make(array $data, User $user): self
    {
        return new self(
            Arr::get($data, 'mobile', false),
            $user
        );
    }

    public function isMobile(): bool
    {
        return $this->isMobile;
    }

    public function getUserId(): int
    {
        return $this->user->id;
    }
}

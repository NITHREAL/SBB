<?php

namespace Domain\User\DTO\Payment;

use Domain\User\Models\User;
use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class UserPaymentMethodDTO extends BaseDTO
{
    public function __construct(
        private readonly string $paymentType,
        private readonly ?int $bindingId,
        private readonly User $user,
    ) {
    }

    public static function make(array $data, User $user): self
    {
        return new self(
            Arr::get($data, 'paymentType'),
            Arr::get($data, 'bindingId'),
            $user,
        );
    }

    public function getPaymentType(): string
    {
        return $this->paymentType;
    }

    public function getBindingId(): ?int
    {
        return $this->bindingId;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}

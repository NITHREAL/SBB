<?php

namespace Domain\MobileToken\DTO;

use Domain\User\Models\User;
use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class MobileAppTokenDTO extends BaseDTO
{
    public function __construct(
        private readonly string $token,
        private readonly ?string $device,
        private readonly ?string $service,
        private readonly User $user,
    ) {
    }

    public static function make(array $data, User $user): self
    {
        return new self(
            Arr::get($data, 'token'),
            Arr::get($data, 'device'),
            Arr::get($data, 'service'),
            $user,
        );
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getDevice(): ?string
    {
        return $this->device;
    }

    public function getService(): ?string
    {
        return $this->service;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}

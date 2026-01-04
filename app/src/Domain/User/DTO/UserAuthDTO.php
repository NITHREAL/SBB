<?php

namespace Domain\User\DTO;

use Illuminate\Support\Arr;

class UserAuthDTO
{
    public function __construct(
        public string $phone,
        public string $code,
        public bool $newsSubscription,
        public ?string $signature,
    ) {}

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'phone'),
            Arr::get($data, 'code'),
            Arr::get($data, 'newsSubscription', true),
            Arr::get($data, 'signature'),
        );
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getNewsSubscription(): bool
    {
        return $this->newsSubscription;
    }

    public function getSignature(): ?string
    {
        return $this->signature;
    }
}

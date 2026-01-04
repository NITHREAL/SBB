<?php

declare(strict_types=1);

namespace Domain\Store\DTO\Exchange;

use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class StoreExchangeDTO extends BaseDTO
{
    public function __construct(
        protected readonly string $city_system_id,
        protected readonly string $system_id,
        protected readonly ?string $legal_entity_system_id,
        protected readonly bool $active,
        protected readonly string $title,
        protected readonly ?string $address
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'city_system_id'),
            Arr::get($data, 'system_id'),
            Arr::get($data, 'legal_entity_system_id'),
            Arr::get($data, 'active'),
            Arr::get($data, 'title'),
            Arr::get($data, 'address')
        );
    }

    public function getCitySystemId(): string
    {
        return $this->city_system_id;
    }

    public function getSystemId(): string
    {
        return $this->system_id;
    }

    public function getLegalEntitySystemId(): ?string
    {
        return $this->legal_entity_system_id;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }
}

<?php

declare(strict_types=1);

namespace Domain\Store\DTO\Exchange;

use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class StoreLeftoverExchangeDTO extends BaseDTO
{
    public function __construct(
        protected readonly string $system_id,
        protected readonly ?bool $active,
        protected readonly ?float $price,
        protected readonly ?float $price_discount,
        protected readonly ?string $discount_expires_in,
        protected readonly ?int $count,
        protected readonly ?array $delivery_schedule
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'system_id'),
            Arr::get($data, 'active'),
            Arr::get($data, 'price'),
            Arr::get($data, 'price_discount'),
            Arr::get($data, 'discount_expires_in'),
            Arr::get($data, 'count'),
            Arr::get($data, 'delivery_schedule', [])
        );
    }

    public function getSystemId(): string
    {
        return $this->system_id;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function getPriceDiscount(): ?float
    {
        return $this->price_discount;
    }

    public function getDiscountExpiresIn(): ?string
    {
        return $this->discount_expires_in;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function getDeliverySchedule(): ?array
    {
        return $this->delivery_schedule;
    }
}

<?php

namespace Domain\Order\DTO;

use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class OrderDeliveryDTO extends BaseDTO
{
    public function __construct(
        private string $deliveryType,
        private string $deliverySubType,
        private string $deliveryDate,
        private string $deliveryTime,
        private ?string $deliveryService,
        private int $cityId,
        private string $store1cId,
        private string $address,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'deliveryType'),
            Arr::get($data, 'deliverySubType'),
            Arr::get($data, 'deliveryDate'),
            Arr::get($data, 'deliveryTime'),
            Arr::get($data, 'deliveryService'),
            Arr::get($data, 'cityId'),
            Arr::get($data, 'storeOneCId'),
            Arr::get($data, 'address'),
        );
    }

    public function getDeliveryType(): string
    {
        return $this->deliveryType;
    }

    public function getDeliverySubType(): string
    {
        return $this->deliverySubType;
    }

    public function getDeliveryDate(): string
    {
        return $this->deliveryDate;
    }

    public function getDeliveryTime(): string
    {
        return $this->deliveryTime;
    }

    public function getDeliveryService(): ?string
    {
        return $this->deliveryService;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getCityId(): int
    {
        return $this->cityId;
    }

    public function getStore1cId(): string
    {
        return $this->store1cId;
    }
}

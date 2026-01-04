<?php

namespace Domain\User\DTO\Delivery;

use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class DeliveryAddressChosenDTO extends BaseDTO
{
    public function __construct(
        private readonly string $deliveryType,
        private readonly string $address,
        private readonly int $storeId,
        private readonly int $userId,
    ) {
    }

    public static function make(array $data, int $userId): self
    {
        return new self(
            Arr::get($data, 'deliveryType'),
            Arr::get($data, 'address'),
            Arr::get($data, 'storeId'),
            $userId,
        );
    }

    public function getDeliveryType(): string
    {
        return $this->deliveryType;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getStoreId(): int
    {
        return $this->storeId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}

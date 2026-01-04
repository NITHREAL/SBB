<?php

namespace Domain\Order\DTO\Delivery;

use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;
use Infrastructure\Services\Buyer\Facades\BuyerDeliveryInterval;

class DeliveryDateTimeSetDTO extends BaseDTO
{
    public function __construct(
        private readonly string $date,
        private readonly string $interval,
        private readonly string $deliverySubType
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'date'),
            Arr::get($data, 'time'),
            Arr::get($data, 'deliverySubType'),
        );
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getInterval(): ?string
    {
        return $this->interval;
    }

    public function getDeliverySubType(): string
    {
        return $this->deliverySubType;
    }
}

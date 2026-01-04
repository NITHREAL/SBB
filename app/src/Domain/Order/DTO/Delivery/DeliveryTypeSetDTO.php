<?php

namespace Domain\Order\DTO\Delivery;

use Domain\City\Models\City;
use Domain\Order\Helpers\Delivery\OrderDeliveryHelper;
use Domain\Store\Models\Store;
use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;
use Infrastructure\Services\Buyer\Facades\BuyerDeliveryInterval;
use Infrastructure\Services\Buyer\Facades\BuyerDeliveryType;

class DeliveryTypeSetDTO extends BaseDTO
{
    public function __construct(
        private readonly string  $deliveryType,
        private readonly ?string $deliverySubType,
        private int $cityId,
        private ?int $storeId,
        private readonly ?string $address,
        private readonly ?string $date,
        private readonly ?string $interval,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'deliveryType'),
            Arr::get($data, 'deliverySubType'),
            Arr::get($data, 'cityId'),
            Arr::get($data, 'storeId'),
            Arr::get($data, 'address'),
            Arr::get($data, 'date'),
            Arr::get($data, 'time'),
        );
    }

    public function getDeliveryType(): string
    {
        return $this->deliveryType;
    }

    public function getDeliverySubType(): ?string
    {
        $inputDeliverySubType = $this->deliverySubType;
        $inputDeliveryType = $this->getDeliveryType();

        return empty($inputDeliverySubType) || $inputDeliveryType !== BuyerDeliveryType::getValue()
            ? OrderDeliveryHelper::getDefaultDeliverySubType($inputDeliveryType)
            : $inputDeliverySubType;
    }

    public function getCity(): City
    {
        return City::findOrFail($this->cityId);
    }

    public function getStore(): ?object
    {
        return $this->storeId
            ? Store::query()->active()->whereCity($this->cityId)->findOrFail($this->storeId)
            : null;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function getDate(): string
    {
        return $this->date ?? BuyerDeliveryInterval::getDeliveryDateInterval();
    }

    public function getInterval(): ?string
    {
        return $this->interval;
    }
}

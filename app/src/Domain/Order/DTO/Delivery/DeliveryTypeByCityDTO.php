<?php

namespace Domain\Order\DTO\Delivery;

use Domain\City\Models\City;
use Domain\Order\Enums\Delivery\DeliveryTypeEnum;
use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;
use Infrastructure\Services\Buyer\Facades\BuyerCity;

class DeliveryTypeByCityDTO extends BaseDTO
{
    public function __construct(
        private ?int $cityId,
        private ?string $deliveryType,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'cityId'),
            Arr::get($data, 'deliveryType'),
        );
    }

    public function getCity(): City
    {
        return $this->cityId
            ? City::findOrFail($this->cityId)
            : BuyerCity::getSelectedCity();
    }

    public function getCoordinates(?City $city): array
    {
        $city = $city ?? $this->getCity();

        return [
            'latitude'  => $city->latitude,
            'longitude' => $city->longitude,
        ];
    }

    public function getDeliveryType(): string
    {
        return $this->deliveryType ?? DeliveryTypeEnum::pickup()->value;
    }
}

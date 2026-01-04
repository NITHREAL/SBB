<?php

namespace Domain\Basket\DTO\Delivery;

use Domain\Order\DTO\Delivery\DeliveryTypeSetDTO;
use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class BasketsDeliveryDTO extends BaseDTO
{
    public function __construct(
        private readonly array $baskets,
    ) {
    }

    public static function make(array $data): self
    {
        $baskets = self::prepareBaskets($data);

        return new self($baskets);
    }

    public function isParamsExists(): bool
    {
        return !empty($this->baskets);
    }

    public function getBaskets(): array
    {
        return $this->baskets;
    }

    private static function prepareBaskets(array $data): array
    {
        $result = [];

        $cityId = Arr::get($data, 'cityId');
        $deliveryBaskets = Arr::get($data, 'deliveryParams', []);

        foreach ($deliveryBaskets as $deliveryBasket) {
            $date = Arr::get($deliveryBasket, 'date');

            $result[$date] = new DeliveryTypeSetDTO(
                Arr::get($deliveryBasket, 'deliveryType'),
                Arr::get($deliveryBasket, 'deliverySubType'),
                $cityId,
                Arr::get($deliveryBasket, 'storeId'),
                Arr::get($deliveryBasket, 'address'),
                Arr::get($deliveryBasket, 'date'),
                Arr::get($deliveryBasket, 'time'),

            );
        }

        return $result;
    }
}
